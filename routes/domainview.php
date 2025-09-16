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


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomainViewController;
use App\Http\Controllers\DomainItemController;

// Public
Route::prefix('domainview')->name('domainview.')->group(function () {
  Route::get('/{groupid}', [DomainViewController::class, 'index'])->name('index');
});

// Authenticated

Route::middleware(['auth'])->prefix('domainview')->name('domainview.')->group(function () {
  Route::get('/createsub/{groupid}', [DomainViewController::class, 'createsub'])
    ->name('createsub');
  Route::post('storesub', [DomainViewController::class, 'storesub'])
    ->name('storesub');
  Route::get('/editdomain/{groupid}', [DomainViewController::class, 'editdomain'])->name('editdomain');
  Route::post('updatedomain', [DomainViewController::class, 'updatedomain'])
    ->name('updatedomain');
  Route::post('destroy', [DomainViewController::class, 'destroy'])
    ->name('destroy');
  Route::delete('/{groupid}', [DomainViewController::class, 'destroydomain'])->name('destroydomain');
  Route::patch('/trash/{groupid}', [DomainViewController::class, 'trash'])->name('trash');
  Route::patch('/{groupid}/untrash', [DomainViewController::class, 'untrash'])->name('untrash');
  Route::patch('/{groupid}/auditcheck', [DomainViewController::class, 'auditcheck'])->name('auditcheck');
  Route::patch('/{groupid}/audituncheck', [DomainViewController::class, 'audituncheck'])->name('audituncheck');

  
  Route::get('/{groupid}/rights', [DomainViewController::class, 'rightsshow'])->name('rights.show');    
  Route::post('/{groupid}/rights', [DomainViewController::class, 'rightsupdate'])->name('rights.update');

});
