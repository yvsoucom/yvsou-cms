{{--
  @copyright (c) 2025  Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
  @author Lican Huang
  @created 2025-06-26
*
* SPDX-License-Identifier: GPL-3.0-or-later
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
--}}
 
{{-- resources/views/comments/partials/partialcomment.blade.php --}}
<div class="comment">
    <div>
        <strong>{{ $comment->user->name ?? 'Guest' }}</strong> • {{ $comment->comment_date }}
    </div>
    <div>
        {!! nl2br(e($comment->comment_content))  . " <ins>PostVer  $comment->post_version  </ins>" !!}
    </div>

    {{-- Add reply form or buttons based on permissions --}}
    {{-- Check if user can reply --}}
    @auth

        @if(Auth::user()->canComment($pid, $groupid, 'WRITE'))

            {{-- Reply Form --}}
            {{-- Reply Button --}}
            <button onclick="toggleReplyForm({{ $comment->id }})" class="text-blue-500 hover:underline">{{ __('post.Reply') }}</button>

            {{-- Hidden Reply Form --}}
            <div id="reply-form-{{ $comment->id }}" style="display: none;" class="mt-2">
                <form method="POST" action="{{ route('post.commentstore') }}" class="comment-reply-form">
                    @csrf
                    <input type="hidden" name="groupid" value="{{ $groupid }}">
                    <input type="hidden" name="comment_postid" value="{{ $pid }}">
                    <input type="hidden" name="comment_parent" value="{{ $comment->id }}">

                    <textarea name="comment_content" rows="5" class="w-full mt-2 border rounded p-2"></textarea>
                    <button type="submit"
                        class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">{{ $rsubmit ?? __('post.submitreply') }}</button>
                </form>
            </div>

            <script>
                function toggleReplyForm(commentId) {
                    const form = document.getElementById(`reply-form-${commentId}`);
                    if (form.style.display === 'none') {
                        form.style.display = 'block';
                        form.scrollIntoView({ behavior: 'smooth' });
                    } else {
                        form.style.display = 'none';
                    }
                }
            </script>

        @endif
    @else
        {{-- User is not logged in --}}
        <p>{{ $cneedloginreply ?? __('post.plslogin') }}
            <a href="{{ route('login') }}" class="text-blue-500 underline">{{ $rlogin ?? __('post.Login') }}</a>
        </p>
    @endauth


    @if($comment->children->count())
        <div class="ml-6">
            @foreach($comment->children as $child)
                @include('comment.partials.partialcomment', ['comment' => $child])
            @endforeach
        </div>
    @endif
</div>