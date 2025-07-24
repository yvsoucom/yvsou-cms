<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025 Institute of Future Science and Technology G.K., Tokyo
// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>
<div class="dark:text-gray-200">
    {{-- Add reply form or buttons based on permissions --}}
    {{-- Check if user can reply new top comment--}}
    @auth
        @if(Auth::user()->canComment($pid, $groupid, 'WRITE'))
            {{-- Reply Button --}}
            <button onclick="document.getElementById('reply-form-{{ $pid}}').classList.toggle('hidden')"
                class="mt-2 px-4 py-1 bg-green-500 hover:bg-green-600 text-white rounded transition-colors">
                {{ $rfreply ?? __('post.Reply') }}
            </button>

            {{-- Hidden Reply Form --}}
            <div id="reply-form-{{ $pid }}" class="hidden mt-4">
                <form method="POST" action="{{ route('post.commentstore') }}" class="comment-reply-form">
                    @csrf
                    <input type="hidden" name="groupid" value="{{$groupid}}">
                    <input type="hidden" name="comment_postid" value={{$pid}}>
                    <textarea name="comment_content" rows="5" 
                        class="w-full mt-2 border border-gray-300 dark:border-gray-600 rounded p-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200"
                        placeholder="{{__('post.yourreply')}}"></textarea>
                    <button type="submit"
                        class="mt-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition-colors">
                        {{ $rsubmit ?? __('post.submitreply') }}
                    </button>
                </form>
            </div>
        @endif
    @else
        {{-- User is not logged in --}}
        <p class="dark:text-gray-300">
            {{ $cneedloginreply ?? __('post.plslogin') }}
            <a href="{{ route('login') }}" class="text-blue-500 dark:text-blue-400 underline hover:text-blue-700 dark:hover:text-blue-300">
                {{ $rlogin ?? __('post.Login') }}
            </a>
        </p>
    @endauth
    
    @foreach($comments as $comment)
        @include('comment.partials.partialcomment', ['comment' => $comment])
    @endforeach
</div>  