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


use App\Http\Controllers\Admin\PluginController;
use App\Http\Controllers\Admin\MailSettingsController;
use App\Http\Controllers\Admin\CastMsgController;
use App\Http\Controllers\Admin\CustomConfigSettingsController;
use App\Http\Controllers\Admin\UserCenterController;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Profile Routes (any verified user)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('destroy');
    });


});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... existing routes ...

    Route::prefix('setmail')->name('setmail.')->group(function () {
        Route::get('edit', [MailSettingsController::class, 'edit'])->name('edit');
        Route::post('update', [MailSettingsController::class, 'update'])->name('update');
    });

});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... existing routes ...
    Route::prefix('usercenter')->name('usercenter.')->group(function () {
        Route::get('/', [UserCenterController::class, 'index'])->name('index');
    });
    Route::prefix('plugins')->name('plugins.')->group(function () {
        Route::get('/', [PluginController::class, 'index'])->name('index');
        Route::post('/upload', [PluginController::class, 'upload'])->name('upload');
        Route::get('/toggle/{plugin}', [PluginController::class, 'toggle'])->name('toggle');
        Route::get('/delete/{plugin}', [PluginController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('setcustomconfig')->name('setcustomconfig.')->group(function () {
        Route::get('edit', [CustomConfigSettingsController::class, 'edit'])->name('edit');
        Route::post('update', [CustomConfigSettingsController::class, 'update'])->name('update');
    });

    Route::prefix('updater')->name('updater.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\UpdaterController::class, 'check'])->name('check');
        Route::post('/run', [\App\Http\Controllers\Admin\UpdaterController::class, 'run'])->name('run');
    });

    Route::prefix('castmsg')->name('castmsg.')->group(function () {
        Route::get('edit', [CastMsgController::class, 'edit'])->name('edit');
        Route::post('update', [CastMsgController::class, 'update'])->name('update');
    });
});


