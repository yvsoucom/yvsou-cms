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


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\ProtectedFileController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\Lang\LangController;
use App\Http\Controllers\HomeController;
use App\Services\VersionCheckService;

 
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

 
Route::get('/lang/{locale}', [LangController::class, 'setLang'])->name('lang.setLang');

Route::get('/upgrade', function () {
    return view('upgrade'); // or redirect to your commercial landing page
});

Route::post('/install/set-locale', function (Illuminate\Http\Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['en', 'zh', 'ja', 'fr'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('install.setLocale');


/*
Route::middleware(['SetLocale'])->group(function () {
   Route::get('/', [HomeController::class, 'index']);
   // other routes...
});

Route::middleware('prevent.install')->group(function () {
   Route::get('/install', [InstallController::class, 'welcome'])->name('install.welcome');
  # Route::post('/install', [InstallController::class, 'submit'])->name('install.submit');
});

*/
Route::middleware(['auth'])->get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard'); // ← this "name" is required



#Route::post('/search', [SearchController::class, 'search'])->name('search');

Route::get('/headlines', [PostController::class, 'showHeadlines'])->name('headlines.show');
/*
Route::get('/protected/{filename}', [ProtectedFileController::class, 'show'])
    ->where('filename', '.*')  // allow slashes in filename
    ->middleware(['auth']);
*/

Route::get('/protected/{filename}', [ProtectedFileController::class, 'show'])
    ->where('filename', '.*');  // allow slashes in filename

Route::get('/verify', [CaptchaController::class, 'show']);
Route::post('/verify', [CaptchaController::class, 'verify'])->name('verify');
 
 

require __DIR__ . '/auth.php';
require __DIR__ . '/domainview.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/post.php';
require __DIR__ . '/group.php';
require __DIR__ . '/toggle.php';
require __DIR__ . '/error.php';
require __DIR__ . '/Installer.php';
require __DIR__ . '/search.php';
require __DIR__ . '/message.php';
require __DIR__ . '/help.php';


