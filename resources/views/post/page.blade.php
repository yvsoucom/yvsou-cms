{{--
@copyright (c) 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo
@author Lican Huang
@created 2025-06-26
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

<div class="mt-4 space-y-6">

    {{-- Domain Links --}}
    <div>{!! $domain_links !!}</div>

    {{-- Post Info Table --}}
    <table class="w-full table-auto border-separate border-spacing-y-2">
        <tbody>
            <tr class="border-b">
                <td class="px-4 py-2 align-top">{!! $post_title !!}</td>
                <td class="px-4 py-2 text-right">
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" @click.away="open = false"
                            class="inline-flex items-center px-3 py-1 text-sm font-medium bg-gray-100 rounded hover:bg-gray-200">
                            {{ __('post.Actions') }}
                            <svg class="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition
                            class="absolute right-0 z-50 mt-2 w-48 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg">

                            <ul class="text-sm text-gray-700">
                                @if (Auth::user() && Auth::user()->canManagePaper($pid))
                                    <li><a href="{{ route('post.edit', ['groupid' => $groupid, 'pid' => $pid]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100">{{ __('post.edit') }} </a></li>
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

                            <ul class="text-sm text-red-600">
                                @if (Auth::user() && Auth::user()->canManagePaper($pid))
                                    @if ($post->post_status == 0)
                                        <li>
                                            <form method="POST"
                                                action="{{ route('post.auditcheck', compact('groupid', 'pid')) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100">{{ __('post.auditcheck') }}
                                                </button>
                                            </form>
                                        </li>
                                    @else
                                        <li>
                                            <form method="POST"
                                                action="{{ route('post.audituncheck', compact('groupid', 'pid')) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100">{{ __('post.uncheck') }}
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
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100">{{ __('post.trash') }}
                                                </button>
                                            </form>
                                        </li>
                                    @else
                                        <li>
                                            <form method="POST" action="{{ route('post.untrash', compact('groupid', 'pid')) }}">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100">{{ __('post.restorewithicon') }}
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
                                                    class="w-full text-left px-4 py-2 hover:bg-red-100">{{ __('post.deletepermanent') }}</button>
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
                <td colspan="2" class="px-4 py-2 text-sm text-gray-600">👤 Author: {{ $author_by }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Post Content --}}
    <div class="prose max-w-none">{!! $content !!}</div>

</div>