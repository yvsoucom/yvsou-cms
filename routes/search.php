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



use App\Http\Controllers\Search\SearchController;


Route::prefix('search')->name('search.')->group(function () {

    // Public route
    Route::post('keyword', [SearchController::class, 'search'])->name('search');

    // Auth route
   // Route::middleware('auth')->post('mykeyword', [SearchController::class, 'mykeyword'])->name('mykeyword');

});


