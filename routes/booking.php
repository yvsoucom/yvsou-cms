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
use App\Http\Controllers\Post\BookingController;

Route::middleware(['auth'])->prefix('book')->name('book.')->group(function () {
  
  Route::post('/posts/{post}/book', [BookingController::class, 'requestFile']);
  Route::post('/bookings/{booking}/upload', [BookingController::class, 'relayUpload']);
  Route::get('/bookings/{booking}/download', [BookingController::class, 'download']);

});