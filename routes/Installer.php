<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */


use App\Http\Controllers\InstallController;

Route::middleware('prevent.reinstall')->prefix('install')->name('install.')->group(function () {

  Route::get('/', [InstallController::class, 'welcome']);
  Route::get('/envForm', [InstallController::class, 'envForm'])->name('envForm');
  Route::post('/saveEnv', [InstallController::class, 'saveEnv'])->name('saveEnv');
 # Route::post('/createAdmin', [InstallController::class, 'createAdmin'])->name('createAdmin');
 # Route::post('/saveCustomConfig', [InstallController::class, 'saveCustomConfig'])->name('saveCustomConfig');
 
});
