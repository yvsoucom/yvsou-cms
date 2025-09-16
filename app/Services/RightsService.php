<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.,     All rights reserved.
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


namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DomainName;
use App\Models\DomainGrantUser;
use App\Models\DomainPost;
use App\Models\DomainManager;
use App\Models\DomainUnreadSub;
use App\Models\DomainGrantGroup;
use App\Models\DomainUploadAttach;


class RightsService
{

    /**
     * Get the userid of the domain owner.
     */

    public function getManageDomainOwner(string $domainID): ?string
    {
        return DomainManager::where('domainid', $domainID)
            ->where('m_type', 'c')
            ->value('userid');
    }


    function checkPermition($right, $type)
    {
        if ($type === 'ADDSUB') {
            if (($right >> 1) & 1)
                return true;
        }
        if ($type === 'READDIR') {

            if (($right >> 2) & 1)
                return true;
        }
        if ($type === 'WRITEDIR') {

            if (($right >> 3) & 1)
                return true;
        }
        if ($type === 'SHOWDIR') {
            if (($right >> 0) & 1)
                return true;
        }
        return false;
    }



    function checkRightPermission(string $groupId, string $type): bool
    {
        $domain = DomainManager::selectRaw(' owner_rights ,   own_group_rights ,  grant_group_rights ,  grant_user_rights ,  any_user_rights ')
            ->whereRaw('TRIM(domainid) = ?', [$groupId])
            ->where('m_type', 'c')
            ->first();

        if (!$domain) {
            return false;
        }

        if ($this->checkAnyUser($domain->any_user_rights, $groupId, $type)) {
            return true;
        }

        if ($this->checkOwnerRight($domain->owner_rights, $groupId, $type)) {
            return true;
        }

        if ($this->checkOwnGroup($domain->own_group_rights, $groupId, $type)) {
            return true;
        }

        if ($this->checkGrantGroup($domain->grant_group_rights, $groupId, $type)) {
            return true;
        }

        if ($this->checkGrantUser($domain->grant_user_rights, $groupId, $type)) {
            return true;
        }

        return false;
    }

    function checkAnyUser($right, $groupid, $type)
    {

        return $this->checkPermition($right, $type);

    }



    function checkOwnerRight(string $right, string $groupId, string $type): bool
    {
        if (auth()->check())
            // Check if user is admin or manages the domain
            if (ConstantService::$adminHasAllRights) {

                if (auth()->user()->isAdmin() || auth()->user()->isManageDomainOwner($groupId)) {
                    return $this->checkPermition($right, $type);
                }
            } else {
                if (auth()->user()->isManageDomainOwner($groupId)) {
                    return $this->checkPermition($right, $type);
                }
            }
        return false;
    }


    function checkOwnGroup($right, $groupid, $type)
    {
        if (auth()->check()) {

            if (ConstantService::$adminHasAllRights) {

                if (auth()->user()->isAdmin() || auth()->user()->withinGroup($groupid)) {
                    return $this->checkPermition($right, $type);
                }
            } else {
                if (auth()->user()->withinGroup($groupid)) {
                    return $this->checkPermition($right, $type);
                }
            }
        }
        return false;

    }


    function checkGrantGroup($right, $groupid, $type)
    {
        if (auth()->check()) {

            if (ConstantService::$adminHasAllRights) {

                if (auth()->user()->isAdmin() || auth()->user()->withingrantgroup($groupid)) {
                    return $this->checkPermition($right, $type);
                }
            } else {
                if (auth()->user()->withingrantgroup($groupid)) {
                    return $this->checkPermition($right, $type);
                }
            }
        }

        return false;

    }


    function checkGrantUser($right, $groupid, $type)
    {

        if (auth()->check()) {

            if (ConstantService::$adminHasAllRights) {

                if (auth()->user()->isAdmin() || auth()->user()->isGrantuser($groupid)) {
                    return $this->checkPermition($right, $type);
                }
            } else {
                if (auth()->user()->isGrantuser($groupid)) {
                    return $this->checkPermition($right, $type);
                }
            }
        }

        return false;

    }


    /**
     * Check comment right permission on a post.
     *
     * @param int $postId
     * @param string $groupId
     * @param string $type
     * @return bool
     */
    public function checkCommentRightPermission(int $postId, string $groupId, string $type): bool
    {

        $post = DomainPost::find($postId);

        if (!$post) {
            return false;
        }

        $rights = trim($post->comment_rights);

        if (!$rights || strlen($rights) < 5) {
            return false;
        }

        $rightArr = str_split($rights);

        // Make sure these helper functions exist or implement them in this class:
        if ($this->checkfileanyuser($rightArr[4], $groupId, $type)) {
            return true;
        }

        if ($this->checkfileownerright($rightArr[0], $groupId, $type)) {
            return true;
        }

        if ($this->checkfileowngroup($rightArr[1], $groupId, $type)) {
            return true;
        }

        if ($this->checkfilegrantgroup($rightArr[2], $groupId, $type)) {
            return true;
        }

        if ($this->checkfilegrantuser($rightArr[3], $groupId, $type)) {
            return true;
        }

        return false;

    }



    function check_cotablerightpermision($id, $groupid, $type, $rights)
    {
        // type : READ //WRITE //EXCUTE 

        $rightarr = str_split(trim($rights));
        // print_r($rightarr);
        if ($this->checkfileanyuser($rightarr[4], $groupid, $type))
            return true;
        if ($this->checkfileownerright($rightarr[0], $groupid, $type))
            return true;
        if ($this->checkfileowngroup($rightarr[1], $groupid, $type))
            return true;
        if ($this->checkfilegrantgroup($rightarr[2], $groupid, $type))
            return true;
        if ($this->checkfilegrantuser($rightarr[3], $groupid, $type))
            return true;
        return false;

    }

    public function fileAccess(string $groupid, string $pid): string
    {
        $accesspermition = $this->checkFileAccess($groupid, $pid);
        //    logger("accesspermition ", [$accesspermition]);

        if ($accesspermition === false) {
            if (!auth()->check()) {
                return redirect()->guest(route('login', ['redirect' => request()->fullUrl()]));
            } else {
                $joinUrl = route('group.joingroup'  , ['groupid' => $groupid, 'redirect' => request()->fullUrl()]);
                return response("Access denied. <a href=\"$joinUrl\">Join group</a>", 403);
            }
        }

        return true;
    }


    function getUploadOwnerId(string $filename): ?int
    {
        $path = parse_url($filename, PHP_URL_PATH);  // e.g. "/storage/uploads/example.jpg"
        $filenameWithoutExtension = pathinfo($path, PATHINFO_FILENAME); // "example"

        $record = DomainUploadAttach::where('md5filename', $filenameWithoutExtension)->first();

        return $record?->userid; // Returns user ID or null if not found
    }

    public function attachfileAccess(string $filename, string $groupid, string $pid): string
    {
        $user = auth()->user();
        $attachedfileownerid = $this->getUploadOwnerId($filename);
        // for migrate 
        if (!$attachedfileownerid)
            return true;
        //
        if ($attachedfileownerid === $user->id)
            return true;
        if ($user) {
            //     logger("userid, attachedfileownerid", [$user->id, $attachedfileownerid]);
            if (ConstantService::$adminHasAllRights) {
                if ($user->isAdmin() || $user->id === $attachedfileownerid)
                    return true;
            } else {
                if ($user->id === $attachedfileownerid)
                    return true;
            }
        }
        $accesspermition = (new RightsService())->checkFileAccess($groupid, $pid);
        //    logger("accesspermition ", [$accesspermition]);
        return $accesspermition;

    }
    public function checkFileAccess(string $groupid, string $pid): bool
    {
        // Only admins OR authors OR users with all 3 permissions can view
        $user = auth()->user();

        if ($user) {
            if (ConstantService::$adminHasAllRights) {
                if ($user->isAdmin() || $user->isAuthorOfPost($pid))
                    return true;
            } else {
                if ($user->isAuthorOfPost($pid))
                    return true;
            }
        }
        //   logger("user ", [$user]);

        $isReadfile = $this->check_filerightpermision($pid, $groupid, 'READ');
        //   logger("isReadfile", [$isReadfile]);
        if (!$isReadfile)
            return false;
        $isReaddir = $this->checkRightPermission($groupid, 'READDIR');
        //  logger("isReaddir", [$isReaddir]);
        if (!$isReaddir)
            return false;

        //  logger("isReadfile, isReaddir", [$isReadfile, $isReaddir]);
        /*
                 $isCheckUnread = (new RightsService())->unreadcheck( $groupid);
                 if (!$isCheckUnread)
                    return false;
            */
        return true;
    }


    function unreadcheck(string $groupid): bool
    {
        $results = DomainUnreadSub::where('bUnread', 1)
            ->whereRaw("LOCATE(domainid, ?) = 1", [$groupid])
            ->get();

        if ($results->isEmpty()) {
            return true;
        }

        $groupid = trim($groupid);
        $flag = true;

        foreach ($results as $val) {
            $domainname = trim($val->domainID);
            $domainnamedot = $domainname . '.';
            $substrvalue = substr($groupid, 0, strlen($domainnamedot));

            if ($domainname === $groupid || $substrvalue === $domainnamedot) {
                $flag = false;

                if (!Auth::check()) {
                    return false;
                }

                if (ConstantService::$adminHasAllRights) {
                    if (Auth::user()->isAdmin())
                        return true;
                }
                if (Auth::user()->isManageDomainOwner($groupid)) {
                    return true;
                }

                if ($val->userid == Auth::id()) {
                    return true;
                }
            }
        }

        return $flag;
    }



    function check_filerightpermision($id, $groupid, $type)
    {
        // type : READ //WRITE //EXCUTE 

        $post = DomainPost::find($id);

        if (!$post) {
            return false;
        }

        $rights = trim($post->rights);

        if (!$rights || strlen($rights) < 5) {
            return false;
        }

        $rightarr = str_split(trim($rights));
        // print_r($rightarr);
        if ($this->checkfileanyuser($rightarr[4], $groupid, $type))
            return true;
        if ($this->checkfileownerright($rightarr[0], $groupid, $type))
            return true;
        if ($this->checkfileowngroup($rightarr[1], $groupid, $type))
            return true;
        if ($this->checkfilegrantgroup($rightarr[2], $groupid, $type))
            return true;
        if ($this->checkfilegrantuser($rightarr[3], $groupid, $type))
            return true;
        return false;

    }


    function checkfilepermition($right, $type)
    {
        $ordnumber = ord(strtoupper($right));
        if ($ordnumber > 47 && $ordnumber < 58)
            $right = dechex($ordnumber - 48);
        else if ($ordnumber > 64 && $ordnumber < 71)
            $right = dechex($ordnumber - 55);
        else
            return false;

        if ($type === 'AUDIT') {
            if (($right >> 3) & 1)
                return true;
        }
        if ($type === 'READ') {
            if (($right >> 2) & 1)
                return true;
        }

        if ($type === 'WRITE') {


            if (($right >> 1) & 1)
                return true;
        }
        if ($type === 'EXECUTE') {

            if ($right & 1)
                return true;
        }

        return false;

    }



    function checkfileanyuser($rights, $groupid, $type)
    {

        return $this->checkfilepermition($rights, $type);

    }


    function checkfileownerright($rights, $groupid, $type)
    {
        if (!Auth::check())
            return false;
        $user = Auth::user();
        if (ConstantService::$adminHasAllRights) {
            if (auth()->user()->isAdmin() || auth()->user()->ismanageDomainOwner($groupid))
                return $this->checkfilepermition($rights, $type);
        } else {
            if (auth()->user()->ismanageDomainOwner($groupid))
                return $this->checkfilepermition($rights, $type);
        }
        return false;

    }


    function checkfileowngroup($rights, $groupid, $type)
    {
        if (!Auth::check())
            return false;
        if (ConstantService::$adminHasAllRights) {
            if (auth()->user()->isAdmin() || auth()->user()->withinGroup($groupid))
                return $this->checkfilepermition($rights, $type);
        } else {
            if (auth()->user()->withinGroup($groupid))
                return $this->checkfilepermition($rights, $type);
        }
        return false;
    }


    function checkfilegrantgroup($rights, $groupid, $type)
    {
        if (!Auth::check())
            return false;
        if (ConstantService::$adminHasAllRights) {
            if (auth()->user()->isAdmin() || auth()->user()->withingrantgroup($groupid))
                return $this->checkfilepermition($rights, $type);
        } else {
            if (auth()->user()->withingrantgroup($groupid))
                return $this->checkfilepermition($rights, $type);
        }
        return false;
    }


    function checkfilegrantuser($rights, $groupid, $type)
    {
        if (!Auth::check())
            return false;
        if (ConstantService::$adminHasAllRights) {
            if (auth()->user()->isAdmin() || auth()->user()->isGrantUser($groupid))
                return $this->checkfilepermition($rights, $type);
        } else {
            if (auth()->user()->isGrantUser($groupid))
                return $this->checkfilepermition($rights, $type);
        }

        return false;
    }


}
