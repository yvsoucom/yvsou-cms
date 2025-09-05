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


@extends('layouts.app')

@section('content')
    <main class="min-h-screen py-6 mt-6 dark:bg-gray-900 dark:text-gray-200">

        <h1 class="text-2xl font-bold dark:text-gray-100 break-words max-w-full overflow-hidden">
            {{ $domaintitle }} ({{ $groupid }})
        </h1>


        <p class="dark:text-gray-300">{{ $domaindescription }}</p>

        @if (session('message'))
            <div class="bg-green-100 dark:bg-green-900 dark:text-green-200 text-green-800 p-2 rounded mt-2">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex flex-wrap gap-4 mt-6">
            @if(isset($viewdomainposts))
                <form id="view-post-form" action="{{ $viewdomainposts->url }}" method="GET">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-medium rounded-xl shadow transition duration-200">
                        📄 {{ __('domain.viewpost') ?? 'View Domain Posts' }}
                    </button>
                </form>
            @endif

            @if(isset($createpost))
                <form id="create-post-form" action="{{ route('post.create', compact('groupid')) }}" method="GET">
                    @csrf
                    <input type="hidden" name="groupid" value="{{ $groupid }}">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-green-600 hover:bg-green-700 text-white text-lg font-medium rounded-xl shadow transition duration-200">
                        ✍️ {{ __('domain.createpost') ?? 'Create New Post' }}
                    </button>
                </form>
            @endif

            @if(isset($createpost))
                <form id="create-local-post-form" action="{{ route('post.localcreate', compact('groupid')) }}" method="GET">
                    @csrf
                    <input type="hidden" name="groupid" value="{{ $groupid }}">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-green-600 hover:bg-green-700 text-white text-lg font-medium rounded-xl shadow transition duration-200">
                        ✍️ {{ __('domain.createlocalpost') ?? 'Create New Local Post' }}
                    </button>
                </form>
            @endif


        </div>

        {{-- Domain Directory Manage --}}
        <div class="border-b dark:border-gray-700 px-4 py-2 text-right">
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <button @click="open = !open" @click.away="open = false"
                    class="inline-flex justify-center w-full px-3 py-1 font-medium bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <span class="text-lg font-medium dark:text-gray-100">📁 {{ __('domain.manage') }}</span>
                    <svg class="w-4 h-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" x-transition x-cloak
                    class="absolute right-0 z-50 mt-2 w-48 origin-top-right bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 rounded-md shadow-lg dark:shadow-gray-900/50">
                    <a href="{{ route('domainview.createsub', ['groupid' => $groupid]) }}"
                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">✏️
                        {{ __('domain.createsub') }}</a>
                    <!-- Other menu items with same dark mode classes -->
                    <a href="{{ route('domainview.editdomain', ['groupid' => $groupid]) }}"
                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">✏️
                        {{ __('domain.editdomain') }}</a>


                    <form method="GET" action="{{ route('domainview.rights.show', compact('groupid')) }}">
                        @csrf
                        <input type="hidden" name="groupid" value="{{ $groupid }}">
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-100">🛡️{{ __('domain.editrights') }}</button>
                    </form>
                    {{--
                    <form method="POST" action="{{ route('domainview.auditcheck', compact('groupid')) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-100">✔️
                            {{ __('domain.auditcheck') }} </button>
                    </form>
                    <form method="POST" action="{{ route('domainview.audituncheck', compact('groupid')) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-100">❌
                            {{ __('domain.audituncheck') }} </button>
                    </form>
                    <form method="POST" action="{{ route('domainview.trash', compact('groupid')) }}"
                        onsubmit="return confirm({{ __('domain.comfirmtrash') }});">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">🗑️
                            {{ __('domain.trashdomain') }} </button>
                    </form>
                    <form method="POST" action="{{ route('domainview.untrash', compact('groupid')) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-100">♻️
                            {{ __('domain.restoredomain') }} </button>
                    </form>
                    <form method="POST" action="{{ route('domainview.destroy', compact('groupid')) }}"
                        onsubmit="return confirm({{ __('domain.permanetdelete') }}  );">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-200">❌
                            {{ __('domain.deletedomain') }} </button>
                    </form>
                    --}}

                </div>
            </div>
        </div>

        {{-- Group Manage --}}
        <div class="border-b dark:border-gray-700 px-4 py-2 text-right">
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <button @click="open = !open" @click.away="open = false"
                    class="inline-flex justify-center w-full px-3 py-2   text-lg font-medium bg-gray-100 dark:bg-gray-700 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <span class="dark:text-gray-100">👥 {{ __('domain.group') }}</span>
                    <svg class="w-5 h-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                @php
                    $groupActions = [
                        ['route' => 'groupmessage', 'label' => __('domain.broadcastmsg')],
                        ['route' => 'sendMessage2Users', 'label' => __('domain.sendMessage2Users')],
                        ['route' => 'approvegroup', 'label' => __("domain.approvegroup")],
                        /*
                        ['route' => 'invitegroup', 'label' => __("domain.invitegroup")],
                        ['route' => 'auditcheckgroup', 'label' => __("domain.auditcheckgroup")],
                        ['route' => 'unauditcheckgroup', 'label' => __("domain.audituncheckgroup")],
                        */
                    ];

                @endphp
                <div x-show="open" x-transition x-cloak
                    class="absolute right-0 z-50 mt-2 w-48 origin-top-right bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 rounded-md shadow-lg dark:shadow-gray-900/50">
                    @foreach ($groupActions as $action)
                        <form method="GET" action="{{ route('group.' . $action['route']) }}">
                            @csrf
                            <input type="hidden" name="groupid" value="{{$groupid}}">
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                {{ $action['label'] }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>

        <h1 class="text-xl font-bold mt-6 dark:text-gray-100">{{ __('domain.groupstatus') }}</h1>

        <p class="text-sm text-gray-700 dark:text-gray-400 mt-2 flex flex-wrap gap-x-4 gap-y-1">
            <span class="flex items-center">👤 {{ __('domain.joined') }}: {{ $joincounts['joinnumbers'] ?? 0 }}</span>
            <span class="flex items-center">⏳ {{ __('domain.pending') }}: {{ $joincounts['pendingUsers'] ?? 0 }}</span>
            <span class="flex items-center">🚫 {{ __('domain.blocked') }}: {{ $joincounts['blockedUsers'] ?? 0 }}</span>
        </p>

        {{-- Join/Leave Group --}}
        @if(auth()->check())
            @if(!auth()->user()->hasApplyJoinGroup($groupid))
                <form method="POST" action="{{ route('group.joingroup', $groupid) }}" class="mt-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors">
                        {{ __("domain.joingroup") }}
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('group.quitgroup', $groupid) }}" class="mt-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors">
                        {{ __("domain.quitgroup") }}
                    </button>
                </form>
            @endif
        @endif

        {{-- set pub/set private Group --}}
        @if(auth()->check())
            @if(!auth()->user()->withDomainPublicStatus($groupid))
                <form method="POST" action="{{ route('group.setprivate') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="groupid" value="{{ $groupid }}">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition-colors">
                        {{ __("domain.setprivate") }}
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('group.setpublic')}}" class="mt-2">
                    @csrf
                    <input type="hidden" name="groupid" value="{{ $groupid }}">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors">
                        {{ __("domain.setpublic") }}
                    </button>
                </form>
            @endif
        @endif

        <h1 class="text-xl font-bold mt-6 dark:text-gray-100">{{ __('domain.applications') }}</h1>
        @php
            $locale = app()->getLocale(); // 'en', 'zh', 'ja', etc.
        @endphp

        @foreach (get_all_plugins() as $plugin)
            <div class="card p-4 rounded shadow">

                @if (!empty($plugin['menus']))
                    @php
                        $menu = $plugin['menus'];

                        $icon = $menu['icon'] ?? '🧩';
                        // $name = $menu['name'] ?? '🧩';

                        $name = __($plugin['slug'] . '::menu.' . $menu['name']);

                        $route = "plugins." . $plugin['slug'] . ".index";
                        $url = route($route);
                        echo "<li>$icon <a href='{$url}'><strong>" . htmlspecialchars($name) . "</strong></a> — <code>/</code></li>";
                    @endphp
                @endif

            </div>
        @endforeach



        <!-- Wrapping breadcrumb layout -->

        <div class="mt-4 dark:text-gray-300 w-full">
            <div class="flex flex-wrap gap-x-1 gap-y-1 break-all">
                {!! $domainlinks !!}
                <span>||{{ $groupid }}</span>
            </div>
        </div>




        {{-- Subdomains --}}
        <h3 class="text-xl font-bold mt-6 dark:text-gray-100">{{ __("domain.subdomains") }} ({{ count($subdomain) }})</h3>
        <ul class="list-disc ml-6 mt-2 dark:text-gray-300">
            @foreach ($subdomain as $sub)
                <li class="dark:text-gray-300">
                    <a href="{{ $sub['domainViewUrl'] }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                        {{ $sub['title'] }} by {{ $sub['owner'] }}
                    </a> |
                    <a href="{{ $sub['subPostViewUrl'] }}" class="text-gray-600 dark:text-gray-400 hover:underline">View
                        SubGroup Posts</a>
                </li>
            @endforeach
        </ul>

    </main>

    <!-- #endregion -->

@endsection