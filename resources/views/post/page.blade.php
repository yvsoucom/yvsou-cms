<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025  
// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>
<div class="mt-4 space-y-2 dark:text-gray-200">

    {{-- Domain Links --}}
   
    <div class=" dark:text-gray-300 w-full">
        <div class="flex flex-wrap gap-x-1 gap-y-1 break-all">
            {!! $domain_links !!}
        </div>
    </div>


    {{-- Post Info Table --}}
    <table class="w-full table-auto border-separate border-spacing-y-2">
        <tbody>
            <tr class="border-b dark:border-gray-700">
                <td class="px-4 py-2 align-top dark:text-gray-100">{!! $post_title !!}</td>
                <td class="px-4 py-2 text-right">
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" @click.away="open = false"
                            class="inline-flex items-center px-3 py-1 text-sm font-medium bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            {{ __('post.Actions') }}
                            <svg class="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition
                            class="absolute right-0 z-50 mt-2 w-48 origin-top-right bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 rounded-md shadow-lg dark:shadow-gray-900/50">

                            <ul class="text-sm text-gray-700 dark:text-gray-300">
                                @if (Auth::user() && Auth::user()->canManagePaper($pid))
                                    <li><a href="{{ route('post.edit', ['groupid' => $groupid, 'pid' => $pid]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('post.edit') }}</a>
                                    </li>
                                    <li><a href="{{ route('post.file-rights.show', ['groupid' => $groupid, 'pid' => $pid]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100">{{ __('post.showrights') }} </a></li>
                                    <li><a href="{{ route('post.comment-rights.show', ['groupid' => $groupid, 'pid' => $pid]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100">{{ __('post.showcommentrights') }}
                                        </a></li>

                                    <li><a href="{{ route('post.movegroup', ['groupid' => $groupid, 'pid' => $pid]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100">{{ __('post.move2group') }}</a></li>
                                    <li><a href="{{ route('post.copygroup', ['groupid' => $groupid, 'pid' => $pid]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100">{{ __('post.copy2group') }}</a></li>
                                    <li><a href="{{ route('post.movelang', ['groupid' => $groupid, 'pid' => $pid]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100">{{ __('post.movelang') }}</a></li>

                                    <li><button onclick="window.reversionModalInstance?.open({{ $pid }})"
                                            class="w-full text-left px-4 py-2 hover:bg-gray-100">{{ __('post.history') }}
                                        </button></li>

                                @endif
                            </ul>

                            <ul class="text-sm text-red-600 dark:text-red-400">
                                @if (Auth::user() && Auth::user()->canManagePaper($pid))
                                    <!-- Red menu items with dark mode hover states -->


                                    @if ($post->post_status == 0)
                                        <li>
                                            <form method="POST"
                                                action="{{ route('post.auditcheck', compact('groupid', 'pid')) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/30">{{ __('post.auditcheck') }}
                                                </button>
                                            </form>
                                        </li>
                                    @else
                                        <li>
                                            <form method="POST"
                                                action="{{ route('post.audituncheck', compact('groupid', 'pid')) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/30">{{ __('post.uncheck') }}
                                                </button>
                                            </form>
                                        </li>
                                    @endif

                                    @if (!\App\Models\DomainPostId::isTrashedFor($pid, $groupid))
                                        <li>
                                            <form method="POST" action="{{ route('post.trash', compact('groupid', 'pid')) }}"
                                                onsubmit="return confirm('{{ __('post.comfirmtrash') }}');">

                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/30">{{ __('post.trash') }}
                                                </button>
                                            </form>
                                        </li>
                                    @else
                                        <li>
                                            <form method="POST" action="{{ route('post.untrash', compact('groupid', 'pid')) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/30">{{ __('post.restorewithicon') }}
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                    @if ($post->post_status == 2)
                                        <li>
                                            <form method="POST" action="{{ route('post.destroy', compact('groupid', 'pid')) }}"
                                                onsubmit="return confirm('{{ __('post.comfirmdelete') }}');">

                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100 dark:hover:bg-red-900/30">{{ __('post.deletepermanent') }}</button>
                                            </form>
                                        </li>
                                    @endif

                                @endif
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>

            {{-- Author Row --}}
            <tr>
                <td colspan="2" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">👤 Author: {{ $author_by }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Post Content --}}
    <div class="prose max-w-none dark:prose-invert dark:text-gray-300">{!! $content !!}</div>

</div>