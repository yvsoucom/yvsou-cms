<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo   All rights reserved.
 * Author: Lican Huang
 * 
 * SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary
 * License: Dual Licensed – GPLv3 or Commercial
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * As an alternative to GPLv3, commercial licensing is available for organizations
 * or individuals requiring proprietary usage, private modifications, or support.
 *
 * Contact: yvsoucom@gmail.com
 * GPL License: https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Services\LocaleService;
use Illuminate\Http\Request;
use App\Services\PagelineService;
use App\Services\PostService;
use App\Services\RightsService;
use App\Services\UserService;
use App\Services\DomainService;
use App\Models\DomainPostId;
use App\Models\DomainManager;
use App\Models\DomainComment;
use App\Models\User;
use App\Models\DomainPost;
use App\Models\PostReversion;


use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentPublished;
use App\Notifications\CommentReplied;
use App\Notifications\postCommented;

use Illuminate\Support\Facades\Cookie;
use App\Services\ConstantService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use HTMLPurifier;
use HTMLPurifier_Config;

//use Jfcherng\Diff\DiffHelper;
// use SebastianBergmann\Diff\Differ;
//use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

use Illuminate\Support\Str;
use App\Services\ReversionService;
use Illuminate\Support\Facades\Log;
use function app\Helpers\apply_filters;

//use Jfcherng\Diff\Factory\ParserFactory;




class PostController extends Controller
{

    function convertImageUrlsToRelative($html)
    {
        return preg_replace_callback(
            '/<img[^>]+src=["\'](https?:)?\/\/([^"\']+)["\']/i',
            function ($matches) {
                $host = $matches[2];
                $parsed = parse_url('https://' . $host);
                // Extract the path from the URL (e.g., /wp-content/uploads/...)
                $path = isset($parsed['path']) ? $parsed['path'] : '';
                return str_replace($matches[0], str_replace($matches[1] . '//' . $host, '', $matches[0]), $matches[0]);
            },
            $html
        );
    }
    function convertHrefToRelative($html)
    {
        return preg_replace_callback(
            '/<a\s+[^>]*href=["\'](?:https?:)?\/\/(?:www\.)?yvsou\.com([^"\']+)["\']/i',
            function ($matches) {
                $relativePath = $matches[1];
                return str_replace($matches[0], preg_replace('/href=["\'][^"\']+["\']/', 'href="' . $relativePath . '"', $matches[0]), $matches[0]);
            },
            $html
        );
    }

    function convertToMigrateRelative($html)
    {
        $domainhosts = config('yvsou_config.MIGRATEDOMAINHOST');
        
        logger('domainhosts', [$domainhosts]);
        $html = $this->removeMigarateContentUrls($html, $domainhosts);
        $html = $this->convertImageSrcToRelative($html, $domainhosts);
        return $html;
    }


    function removeMigarateContentUrls($html, array $domainHosts)
    {
        // Escape and combine domains for regex
        $escapedDomains = array_map(function ($host) {
            return preg_quote($host, '#');
        }, $domainHosts);
        $domainPattern = implode('|', $escapedDomains);

        return preg_replace_callback(
            '#<a\s+[^>]*href=["\'](?:https?:)?\/\/(?:www\.)?(?:' . $domainPattern . ')([^"\']*)["\']#i',
            function ($matches) {
                $relativePath = $matches[1] ?: '/';
                return preg_replace('/href=["\'][^"\']+["\']/', 'href="' . $relativePath . '"', $matches[0]);
            },
            $html
        );
    }

    function convertImageSrcToRelative($html, array $domainHosts)
    {
        // Escape domains for regex
        $escapedDomains = array_map(function ($host) {
            return preg_quote($host, '#');
        }, $domainHosts);
        $domainPattern = implode('|', $escapedDomains);

        // Replace only <img src="..."> where domain matches
        return preg_replace_callback(
            '#<img\s+[^>]*src=["\'](?:https?:)?\/\/(?:www\.)?(?:' . $domainPattern . ')([^"\']*)["\']#i',
            function ($matches) {
                $relativePath = $matches[1] ?: '/';
                return preg_replace('/src=["\'][^"\']+["\']/', 'src="' . $relativePath . '"', $matches[0]);
            },
            $html
        );
    }


    public function addProtectedUrls(string $content): string
    {
        // Process <img src="...">
        $content = preg_replace_callback('/<img\s+[^>]*src=["\']((?!https?:|\/\/)[^"\']+)["\']/i', function ($matches) {
            $src = ltrim($matches[1], '/');
            $relativePath = str_starts_with($src, 'protected/') ? $src : "protected/{$src}";
            $fullUrl = url($relativePath);

            return str_replace($matches[1], $fullUrl, $matches[0]);
        }, $content);

        // Process <a href="...">
        $content = preg_replace_callback('/<a\s+[^>]*href=["\']((?!https?:|mailto:|\/\/)[^"\']+)["\']/i', function ($matches) {
            $href = ltrim($matches[1], '/');
            $relativePath = str_starts_with($href, 'protected/') ? $href : "protected/{$href}";
            $fullUrl = url($relativePath);

            return str_replace($matches[1], $fullUrl, $matches[0]);
        }, $content);

        return $content;
    }


    public function convertToProtectedUrls(string $content, string $groupid, string $pid): string
    {
        // Replace local <img src="..."> with fully qualified protected URLs including query params
        $content = preg_replace_callback('/<img\s+[^>]*src=["\']((?!https?:|\/\/)[^"\']+)["\']/i', function ($matches) use ($groupid, $pid) {
            $src = ltrim($matches[1], '/');
            // Skip if already protected
            if (str_starts_with($src, 'protected/')) {
                $fullUrl = url("$src") . "?groupid={$groupid}&pid={$pid}";
                if (!$this->isAtatchSamewithPost($pid, $fullUrl))
                    return "-1";
            } else {
                // Build protected URL
                $fullUrl = url("/protected/{$src}") . "?groupid={$groupid}&pid={$pid}";
                if (!$this->isAtatchSamewithPost($pid, $fullUrl))
                    return "-1";
            }
            return '<img src="' . $fullUrl . '"';
        }, $content);
        // Replace local <a href="..."> with fully qualified protected URLs including query params
        $content = preg_replace_callback('/<a\s+[^>]*href=["\']((?!https?:|mailto:|\/\/)[^"\']+)["\']/i', function ($matches) use ($groupid, $pid) {
            $href = ltrim($matches[1], '/');
            // Skip if already protected
            if (str_starts_with($href, 'protected/')) {
                // Build protected URL
                $fullUrl = url("$href") . "?groupid={$groupid}&pid={$pid}";
                if (!$this->isAtatchSamewithPost($pid, $fullUrl))
                    return "-1";
            } else {
                $fullUrl = url("/protected/{$href}") . "?groupid={$groupid}&pid={$pid}";
                if (!$this->isAtatchSamewithPost($pid, $fullUrl))
                    return "-1";
            }
            return '<a href="' . $fullUrl . '"';
        }, $content);
        return $content;
    }



    public function showComments($pid)
    {
        $comments = DomainComment::with(['children', 'user'])
            ->where('postid', $pid)
            ->where('comment_parent', 0)
            ->get();

        return $comments;
    }



    public function isAtatchSamewithPost($pid, $filename)
    {
        $post = DomainPost::find($pid);
        $postuserid = $post ? $post->post_author : null;
        $attachedfileownerid = (new RightsService())->getUploadOwnerId($filename);
        if ($postuserid === null)
            return false;
        if ($attachedfileownerid === null)
            return true;   // for before version
        if ($attachedfileownerid === $postuserid)
            return true;
        return false;

    }

    public function index($groupid, $pid)
    {
        $post = (new PostService())->getPostFromPostid($pid);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }
        if ($post->post_status === 0) {
            if (!(new RightsService())->fileAccess($groupid, $pid)) {
                return;
            }
            $content = nl2br($post->post_content);
            $content = $this->convertToMigrateRelative($content);
            logger("create post content migration");
            logger($content);
            $content = $this->convertToProtectedUrls($content, $groupid, $pid);
            logger($content);
            logger("create post content prptecturl");
            if ($content === "-1")
                return redirect()->route('error.attachedfile', compact('errorno'));
            $content = do_shortcode($content);
            $content = do_plugin_shortcode($content);
            $lastVersion = PostReversion::where('postid', $post->id)->max('version') ?? 0;
            $post_title = $post->post_title ;
          
            $post_title = apply_plugin_filters(
                'DefaultTheme',
                'post_version',
                $post_title,          // base value
                $lastVersion    // dynamic arg  
            );
            $authorby = (new User())->getAliasNameByID($post->post_author);
            $lastauthorby = (new User())->getAliasNameByID($post->revised_author);

            $author_by = " by  $authorby   $post->post_date ";

            if ($lastauthorby) {
                //  apply_filters('author_by', $author_by, $post->post_date, $authorby);
                $author_by = apply_filters(
                    'modify_author_by',
                    $author_by,          // base value
                    $lastauthorby,       // dynamic arg 1
                    $post->updated_at    // dynamic arg 2
                );
                //     $author_by .= " last modified by  $lastauthorby    $post->updated_at    ";
            }
            $domain_links = (new DomainService())->get_joinGroupLink_by_uniqid($groupid);
            $comments = $this->showComments($pid);

        } elseif ($post->post_status === 1) {
            if (!(new RightsService())->fileAccess($groupid, $pid)) {
                return;
            }
            $content = nl2br($post->post_content);
            $content = $this->convertToMigrateRelative($content);
            $content = $this->convertToProtectedUrls($content, $groupid, $pid);
            if ($content === "-1")
                return redirect()->route('error.attachedfile', compact('errorno'));
            //    $content = do_shortcode($content);
            $post_title = " $post->post_title  <ins>auditing...</ins>";
            $author_by = $post->post_date . " by " . (new User())->getAliasNameByID($post->post_author);
            $domain_links = (new DomainService())->get_joinGroupLink_by_uniqid($groupid);
            $comments = $this->showComments($pid);
        } elseif ($post->post_status === 2) {
            if (!(new RightsService())->fileAccess($groupid, $pid)) {
                return;
            }
            $content = nl2br($post->post_content);
            $content = $this->convertToMigrateRelative($content);
            $content = $this->convertToProtectedUrls($content, $groupid, $pid);
            if ($content === "-1")
                return redirect()->route('error.attachedfile', compact('errorno'));
            $content = do_shortcode($content);
            $content = do_plugin_shortcode($content);

            $post_title = "<del>$post->post_title  </del>";
            $author_by = $post->post_date . " by " . (new User())->getAliasNameByID($post->post_author);
            $domain_links = (new DomainService())->get_joinGroupLink_by_uniqid($groupid);
            $comments = $this->showComments($pid);
        } else {

        }

        return view('post.index', compact('post', 'pid', 'groupid', 'post_title', 'content', 'author_by', 'domain_links', 'comments'));
    }

    public function postview($groupid, $srec = 0)
    {
        $groupid = urldecode($groupid);
        $domain_links = (new DomainService())->get_joinGroupLink_by_uniqid($groupid);
        $posts = (new PostService())->getPosts($groupid, $srec);

        // logger('posts', $posts);
        $postallnumbers = (new PostService())->getPostCounts($groupid, 1);
        $postnumbers = (new PostService())->getPostCounts($groupid, 0);
        logger("postnumbers", [$postnumbers]);
        $alist = json_decode(Cookie::get('alist'), true) ?? ConstantService::$alist;
        //  $alist = json_decode(Cookie::get('alist'), true) ?? ConstantService::$alist;

        return view('post.postview', compact('groupid', 'domain_links', 'posts', 'postnumbers', 'postallnumbers', 'alist')); // resources/views/domainview/index.blade.php
    }

    function replaceWithRelativeUrls(string $content): string
    {
        // Parse your app URL from config
        $appUrl = config('app.url');
        $parsedUrl = parse_url($appUrl);

        $host = $parsedUrl['host'] ?? '';
        $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';

        // Regex to match http/https + your host + optional port
        $pattern = '#https?://(?:www\.)?' . preg_quote($host . $port, '#') . '#i';

        // Replace with empty string to get relative URLs
        return preg_replace($pattern, '', $content);
    }



    public function commentstore(Request $request)
    {

        //    logger('Logging comment submission', ['request_data' => $request->all()]);


        $request->validate([
            'groupid' => 'required|string',
            'comment_content' => 'required|string',
            'comment_postid' => 'required|integer|exists:domain_posts,id', // ✅ fixed here
            'comment_parent' => 'nullable|integer|exists:domain_comments,id',
        ]);

        $pid = $request->comment_postid;
        $groupid = $request->groupid;

        // dd($groupid);

        if ((new RightsService())->checkCommentRightPermission($pid, $groupid, 'WRITE')) {

            $user = Auth::user();
            $ip = $request->ip();
            $lastVersion = PostReversion::where('postid', $pid)->max('version') ?? 0;

            $comment = DomainComment::create([
                'postid' => $request->comment_postid,
                'comment_ip' => $ip,
                'comment_date' => now(),
                'comment_content' => $request->comment_content,
                'comment_parent' => $request->comment_parent ?? 0,
                'userid' => $user->id,
                'post_version' => $lastVersion,
            ]);


            // Notifications
            $route = "route('post.index', compact('groupid', 'pid'))";
            Notification::route('mail', $user->email)
                ->notify(new CommentPublished($user, $comment, $route));

            Notification::route('mail', $user->email)
                ->notify(new PostCommented($user, $comment, $route));

            Notification::route('mail', $user->email)
                ->notify(new CommentReplied($user, $comment, $route));


            return redirect()->route('post.index', compact('groupid', 'pid'));

        }

        return redirect('/')->with('message', 'No right to create new post in this domain.');


    }


    public function create(Request $request)
    {
        $validated = $request->validate([
            'groupid' => 'required|string',
        ]);
        $groupid = $request->groupid;
        return view('post.create', compact('groupid')); // resources/views/domainview/index.blade.php
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'groupid' => 'required|string',
        ]);

        $groupid = $request->groupid;
        $user = Auth::user();
        $ip = $request->ip();
        $pubstatus = 0;  //'publish' 

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        $title = $purifier->purify($request->title);
        $content = $purifier->purify($request->input('content'));
        $content = $this->replaceWithRelativeUrls($content);
        $md5code = md5($content);

        $langid = (new LocaleService())->getcurlang();
        $pid = 0;
        DB::beginTransaction();
        try {
            $curtime = now();
            logger("create post store before");
            $post = DomainPost::create([
                'post_title' => $title,
                'post_content' => $content,
                'post_author' => $user->id,
                'ip' => $ip,
                'post_date' => $curtime,
                'post_status' => $pubstatus,
                'md5code' => $md5code,
            ]);
            logger("create post store after");
            $pid = $post->id;
            logger($pid);
            $insertSuccess = DB::table('domain_post_ids')->insert([
                'postid' => $pid,
                'groupid' => $groupid,
                'lang' => $langid,
                'guserid' => $user->id,
                'gDate' => $curtime,
                'isTrash' => 0,
            ]);



            //   $this->log('publish', $data['user'], "{$post->ID}_{$post->post_title}"); // log

            // Deduct balance and save to logs
            //   $this->handleSemTransaction('publishpaper', $data['user'], 20, $pid);

            // Send notification
            //   $this->sendPublishedNotification($post);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            //      return back()->withErrors(['msg' => 'Insert to domain_postIds failed. Possibly duplicate or constraint violation.']);
            $errorno = '1';
            Log::error('Post create error: ' . $e->getMessage());
            return redirect()->route('error.attachedfile', compact(['errorno']));

        }

        return redirect()->route('post.index', compact(['groupid', 'pid']));
    }

    public function edit($groupid, $postid)
    {

        $post = DomainPost::findOrFail($postid);
        $post_content = $this->convertToMigrateRelative($post->post_content);
        $post_content = $this->addProtectedUrls($post_content);

        return view('post.edit', compact(['groupid', 'post', 'post_content'])); // resources/views/domainview/index.blade.php
    }
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);
        $post = DomainPost::findOrFail($request->postid);
        $groupid = $request->groupid;
        $user = Auth::user();
        $ip = $request->ip();

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $title = $purifier->purify($request->title);
        $content = $purifier->purify($request->input('content'));
        $content = $this->replaceWithRelativeUrls($content);
        $md5code = md5($content);

        $curtime = now();

        // Save version 0 as base content


        // Save current version to reversions
        DB::beginTransaction();
        try {

            $oldContent = $post->post_content;

            $oldTitle = $post->post_title;
            $diff = null;
            if ($oldContent === $content && $oldTitle === $title) {
                return back()->with('info', 'No changes detected.');
            }
            $lastVersion = PostReversion::where('postid', $post->id)->max('version') ?? 0;
            if ($lastVersion === 0) {
                PostReversion::create([
                    'postid' => $post->id,
                    'post_title' => $post->post_title,
                    'userid' => $post->post_author,
                    'version' => 0,
                    'base_content' => $post->post_content,
                    'md5code' => $post->md5code,
                    'diff' => null,
                    'ip' => $post->ip,
                ]);
            }

            if ($oldContent !== $content) {

                logger("old and new Content:", [$oldContent, $content]);

                $diff = (new ReversionService())->generateCompareJson(
                    $oldContent,
                    $content,
                );
                logger($diff);

                $reconstruct = (new ReversionService())->reconstructFromDiffRanges($oldContent, $diff);
                if ($reconstruct != $content) {
                    //  DB::rollBack();
                    //  $errorno = '5';
                    logger("new Content:", [$content]);

                    logger('Post reconstruct error: ', [$reconstruct]);
                    //   return redirect()->route('error.attachedfile', compact(['errorno']));

                }
            }


            $lastVersion = PostReversion::where('postid', $post->id)->max('version') ?? 0;

            if (!$diff) {
                DB::rollBack();
                $errorno = '3';
                Log::error('Post create error: no diff ');
                return redirect()->route('error.attachedfile', compact(['errorno']));

            } else {
                //           logger("herein sidefirst", [json_encode($diff)]);
                PostReversion::create([
                    'postid' => $post->id,
                    'userid' => auth()->id(),
                    'post_title' => $title,
                    'version' => $lastVersion + 1,
                    'base_content' => null,
                    'diff' => $diff,
                    'ip' => $ip,
                    'updated_at' => $curtime,
                    'md5code' => $md5code,
                ]);
            }
            //    logger("here", [$diff]);

            $post->update([
                'post_title' => $title,
                'post_content' => $content,
                'revised_author' => auth()->id(),
                'ip' => $ip,
                'updated_at' => $curtime,
                'md5code' => $md5code,
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $errorno = '2';
            Log::error('Post create error: ' . $e->getMessage());

            return redirect()->route('error.attachedfile', compact(['errorno']));

        }
        $pid = $post->id;
        return redirect()->route('post.index', parameters: compact(['groupid', 'pid']));
    }

    public function reversionsJson(Request $request, int $pid)
    {
        //     logger("reversionsJson", [$pid]);
        $post = DomainPost::findOrFail($pid);
        $reversions = $post->reversions()
            ->with('modifiedBy')
            ->latest('updated_at')
            ->paginate(10); // Paginate!

        $data = $reversions->map(function ($rev) {
            return [
                'id' => $rev->id,
                'updated_at' => $rev->updated_at?->toDateTimeString(),
                'modified_by_name' => $rev->modifiedBy->name ?? 'Unknown',
                'title' => $rev->post_title,
                'preview' => Str::limit(strip_tags($rev->content), 100),
            ];
        });

        return response()->json([
            'reversions' => $data,
            'next_page_url' => $reversions->nextPageUrl(),
        ]);
    }



    public function restoreUpdate($post, $reversion)
    {

        $user = Auth::user();
        $curtime = now();

        DB::beginTransaction();
        try {

            $oldContent = $post->post_content;
            $oldTitle = $post->post_title;

            $lastVersion = $reversion->version;

            $content = (new ReversionService())->reconstructHtmlPostVersion($reversion->postid, $reversion->version);

            // $tiff = DiffHelper::calculate($oldContent, $content, 'SideBySide', ['context' => 0]);

            // $tiff = DiffHelper::calculate($oldContent, $content, 'SideBySide', ['context' => 0]);

            $tiff = (new ReversionService())->generateCompareJson(
                $oldContent,
                $content,
            );
            PostReversion::create([
                'postid' => $post->id,
                'userid' => auth()->id(),
                'post_title' => $post->post_title,
                'version' => $lastVersion + 1,
                'base_content' => null,
                'diff' => $tiff,
                'ip' => $post->ip,
                'updated_at' => $curtime,
                'md5code' => $post->md5code,
            ]);

            // Update main post
            $post->update([
                'post_title' => $reversion->post_title,
                'post_content' => $content,
                'revised_author' => auth()->id(),
                'ip' => $reversion->ip,
                'updated_at' => $curtime,
                'md5code' => $reversion->md5code,
            ]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $errorno = '2';
            Log::error('Post create error: ' . $e->getMessage());

            return redirect()->route('error.attachedfile', compact(['errorno']));

        }
        return true;

    }


    public function restorereversion(int $reversionID)
    {

        $reversion = PostReversion::findOrFail($reversionID);
        $post = DomainPost::findOrFail($reversion->postid);

        $this->restoreUpdate($post, $reversion);
        return response()->json(['success' => true]);
    }


    public function trash($groupid, $pid)
    {

        DB::table('domain_post_ids')
            ->where('postid', $pid)
            ->where('groupid', $groupid)
            ->update(['isTrash' => 1]);

        $exists = DB::table('domain_post_ids')
            ->where('postid', $pid)
            ->where('groupid', $groupid)
            ->where('isTrash', 0)
            ->exists();

        if (!$exists) {
            DB::table('domain_posts')
                ->where('id', $pid)
                ->update(['post_status' => 2]);
        }

        return redirect()->route('post.index', compact('groupid', 'pid'));
    }


    public function untrash($groupid, $pid)
    {

        DB::table('domain_post_ids')
            ->where('postid', $pid)
            ->where('groupid', $groupid)
            ->update(['isTrash' => 0]);

        $exists = DB::table('domain_posts')
            ->where('id', $pid)
            ->where('post_status', 2)
            ->exists();

        if ($exists) {

            DB::table('domain_posts')
                ->where('id', $pid)
                ->update(['post_status' => 0]);
        }

        return redirect()->route('post.index', compact('groupid', 'pid'));
    }


    public function destroy($groupid, $pid)
    {

        $exists = DB::table('domain_post_ids')
            ->where('postid', $pid)
            ->where('groupid', $groupid)
            ->where('isTrash', 0)
            ->exists();

        if (!$exists) {
            // No active (non-trash) record exists
            DB::transaction(function () use ($pid) {
                DB::table('domain_posts')->where('id', $pid)->delete();
                DB::table('domain_post_ids')->where('id', $pid)->delete();
                DB::table('post_reversions')->where('postid', $pid)->delete();
            });
        }


        return redirect()->route('home');
    }



    public function auditcheck($groupid, $pid)
    {


        DB::table('domain_posts')
            ->where('id', $pid)
            ->update(['post_status' => 1]);

        return redirect()->route('post.index', compact('groupid', 'pid'));
    }


    public function audituncheck($groupid, $pid)
    {

        DB::table('domain_posts')
            ->where('id', $pid)
            ->update(['post_status' => 0]);

        return redirect()->route('post.index', compact('groupid', 'pid'));
    }

    public function movegroup($groupid, $pid)
    {
        return view('post.movegroup', compact('groupid', 'pid'));

    }
    public function copygroup($groupid, $pid)
    {
        return view('post.copygroup', compact('groupid', 'pid'));
    }

    public function movelang($groupid, $pid)
    {
        $langIdSet = (new LocaleService())->getlangIdSet();
        return view('post.movelang', compact('groupid', 'pid', 'langIdSet'));
    }


    public function movegroupupdate(Request $request)
    {
        $validated = $request->validate([
            'desgroupid' => 'required|string',
            'pid' => 'required|int',
            'groupid' => 'required|string',
        ]);
        /*
                $request->validate([
                    'desgroupid' => ['required', 'regex:/^\d+(\.\d+)*$/'],
                ]);
        */
        $request->validate([
            'desgroupid' => ['required', 'exists:domain_managers,domainid'],
        ]);

        $groupid = $request->groupid;
        $pid = $request->pid;
        $desgroupid = $request->desgroupid;

        // Update the groupid
        DomainPostId::where('id', $validated['pid'])
            ->where('groupid', $validated['groupid'])
            ->update(['groupid' => $validated['desgroupid']]);
        $groupid = $desgroupid;
        return redirect()->route('post.index', compact('groupid', 'pid'));


    }



    public function copygroupupdate(Request $request)
    {
        $validated = $request->validate([
            'desgroupid' => 'required|string',
            'pid' => 'required|int',
            'groupid' => 'required|string',
        ]);
        /*
                $request->validate([
                    'desgroupid' => ['required', 'regex:/^\d+(\.\d+)*$/'],
                ]);
        */
        $request->validate([
            'desgroupid' => ['required', 'exists:domain_managers,domainid'],
        ]);
        logger("resquest", [$request]);
        $groupid = $request->groupid;
        $pid = $request->pid;
        $desgroupid = $request->desgroupid;
        logger("pid", [$pid]);
        logger("desgroupid", [$desgroupid]);
        // copy  to the groupid
        $userid = Auth::user()->id;
        $lang = (new LocaleService())->getcurlang();
        DomainPostId::create([
            'id' => $pid,
            'groupid' => $desgroupid,
            'guserid' => $userid,
            'lang' => $lang,
        ]);

        $groupid = $desgroupid;
        return redirect()->route('post.index', compact('groupid', 'pid'));


    }

    public function movelangupdate(Request $request)
    {
        $validated = $request->validate([
            'language' => 'required|int',
            'pid' => 'required|int',
            'groupid' => 'required|string',
        ]);


        $groupid = $request->groupid;
        $pid = $request->pid;
        $lang = $request->language;
        logger("pid", [$pid]);
        logger("lang", [$lang]);
        // move  to target language in the groupid
        DomainPostId::where('id', $validated['pid'])
            ->where('groupid', $validated['groupid'])
            ->update(['lang' => $validated['language']]);

        return redirect()->route('post.index', compact('groupid', 'pid'));


    }

}