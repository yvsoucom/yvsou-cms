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
<header class="w-full bg-white dark:bg-gray-900 border-b shadow-sm fixed top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <!-- Logo -->
      <div class="flex-shrink-0">
        <a href="{{ route('home') }}">
          <img class="h-8 w-auto" src="{{ app()->isLocal() ? asset('images/yvsoulogo.svg') : Vite::asset('images/yvsoulogo.svg') }}" alt="Logo">
        </a>
      </div>

      <!-- Desktop Navigation -->
      <nav class="hidden md:flex items-center gap-6">
        <!-- Language Dropdown -->
        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open"
                  aria-haspopup="true"
                  :aria-expanded="open"
                  class="px-3 py-2 bg-gray-200 dark:bg-gray-800 rounded inline-flex items-center transition select-none">
            🌐
            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
              <path d="M5.25 7L10 11.75 14.75 7z" />
            </svg>
          </button>
          <div x-show="open"
               @click.away="open = false"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="opacity-0 transform scale-95"
               x-transition:enter-end="opacity-100 transform scale-100"
               x-transition:leave="transition ease-in duration-75"
               x-transition:leave-start="opacity-100 transform scale-100"
               x-transition:leave-end="opacity-0 transform scale-95"
               class="absolute left-0 z-10 mt-2 w-32 bg-white dark:bg-gray-800 border rounded shadow-md">
            @foreach ($getlangSet as $code => $language)
              <a href="{{ route('lang.setLang', $code) }}"
                 class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ app()->getLocale() === $code ? 'font-bold text-blue-600' : '' }}">
                {{ $language }}
              </a>
            @endforeach
          </div>
        </div>

        <!-- Root Domain Tool -->
        <a href="{{ route('domainview.index', ['groupid' => '0']) }}"
           class="px-4 py-2 text-sm border border-transparent rounded hover:border-gray-300 dark:hover:border-gray-600 dark:text-gray-100 transition">
          {{ __('header.rootdomaintool') }}
        </a>

        <!-- Help Dropdown -->
        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open"
                  aria-haspopup="true"
                  :aria-expanded="open"
                  class="px-3 py-2 flex items-center   text-sm border border-transparent rounded hover:border-gray-300 dark:hover:border-gray-600 dark:text-gray-100 transition select-none">
            {{ __('header.help') }}
            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
              <path d="M5.25 7L10 11.75 14.75 7z" />
            </svg>
          </button>
          <div x-show="open"
               @click.away="open = false"
               x-transition
               class="absolute left-0 z-10 mt-2 w-48 bg-white dark:bg-gray-800 border rounded shadow-md p-2 space-y-1">
            <a href="{{ route('help.about') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ __('help.about') }}</a>
            <a href="{{ route('help.menu') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ __('help.menu') }}</a>
          </div>
        </div>

        <!-- Auth Links -->
        @auth
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="flex items-center text-sm font-medium text-gray-700 dark:text-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md">
                <span class="mr-2">{{ Auth::user()->name }}</span>
              </button>
            </x-slot>
            <x-slot name="content">
              <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('profile.title') }}</a>
              <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('dashboard.dashboard') }}</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                  {{ __('auth.logout') }}
                </button>
              </form>
            </x-slot>
          </x-dropdown>
        @else
          <a href="{{ route('login') }}" class="px-4 py-2 text-sm border border-transparent rounded hover:border-gray-300 dark:hover:border-gray-600 dark:text-gray-100 transition">{{ __('auth.login') }}</a>
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="px-4 py-2 text-sm border border-transparent rounded hover:border-gray-300 dark:hover:border-gray-600 dark:text-gray-100 transition">{{ __('auth.register') }}</a>
          @endif
        @endauth

        <a href="{{ route('message.index') }}"
           class="px-4 py-2 text-sm border border-transparent rounded hover:border-gray-300 dark:hover:border-gray-600 dark:text-gray-100 transition">
          {{ __('message.message') }}
        </a>
      </nav>

      <!-- Mobile Hamburger -->
      <div class="md:hidden">
        <button id="mobile-menu-button"
                aria-controls="mobile-menu"
                aria-expanded="false"
                class="text-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Nav -->
  <nav id="mobile-menu"
     class="flex md:hidden flex-col space-y-2 px-4 pt-2 pb-4 bg-white dark:bg-gray-900 border-t shadow-inner"
       @foreach ($getlangSet as $code => $language)
      <a href="{{ route('lang.setLang', $code) }}"
         class="block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200 {{ app()->getLocale() === $code ? 'font-bold text-blue-600' : '' }}"
         @click="document.getElementById('mobile-menu').classList.add('hidden')">
        {{ $language }}
      </a>
    @endforeach

    <a href="{{ route('domainview.index', ['groupid' => '0']) }}"
       class="block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition"
       @click="document.getElementById('mobile-menu').classList.add('hidden')">
      {{ __('header.rootdomaintool') }}
    </a>

    <a href="{{ route('help.about') }}" class="block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ __('help.about') }}</a>
    <a href="{{ route('help.menu') }}" class="block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ __('help.menu') }}</a>

    @auth
      <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ __('profile.title') }}</a>
      <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ __('dashboard.dashboard') }}</a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-left block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">
          {{ __('auth.logout') }}
        </button>
      </form>
    @else
      <a href="{{ route('login') }}" class="block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ __('auth.login') }}</a>
      @if (Route::has('register'))
        <a href="{{ route('register') }}" class="block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ __('auth.register') }}</a>
      @endif
    @endauth

    <a href="{{ route('message.index') }}"
       class="block px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition">{{ __('message.message') }}</a>
  </nav>

  <script>
    const button = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');
    button.addEventListener('click', function () {
      const isOpen = !menu.classList.contains('hidden');
      menu.classList.toggle('hidden');
      this.setAttribute('aria-expanded', !isOpen);
    });
  </script>
</header>
