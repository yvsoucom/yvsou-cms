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


use App\Http\Controllers\Admin\PluginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Toggle\ToggleController;


// Public


Route::prefix('toggle')->name('toggle.')->group(function () {

    Route::post('/alist', [ToggleController::class, 'toggleAlist'])->name('alist');


});



Route::middleware(['auth'])->prefix('toggle')->name('toggle.')->group(function () {

   
});

