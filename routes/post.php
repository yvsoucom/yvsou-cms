<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo   All rights reserved.
 * Author: Lican Huang
 * 
 * SPDX-License-Identifier: GPL-3.0-or-later
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
use App\Http\Controllers\Post\UploadController;
use App\Http\Controllers\Post\FileLibraryController;
use App\Http\Controllers\Post\FileRightsController;
use App\Http\Controllers\Post\CommentRightsController;

use App\Http\Livewire\PostreversionDiff;

// Public


Route::prefix('post')->name('post.')->group(function () {
  Route::get('index/{groupid}/{pid}', [PostController::class, 'index'])
    ->name('index');
  Route::get('postview/{groupid}/{srec?}', [PostController::class, 'postview'])->where('groupid', '.*')->name('postview');

});



Route::middleware(['auth'])->prefix('post')->name('post.')->group(function () {

  Route::get('/create/{groupid}', [PostController::class, 'create'])
    ->name('create');


  Route::post('store', [PostController::class, 'store'])
    ->name('store');

  Route::post('commentstore', [PostController::class, 'commentstore'])
    ->name('commentstore');

  Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
  Route::post('/processUpload', [UploadController::class, 'processUpload'])->name('processUpload');

  Route::get('/file-library', [FileLibraryController::class, 'index']);


  Route::get('/edit/{groupid}/{pid}', [PostController::class, 'edit'])->name('edit');

  Route::get('/movegroup/{groupid}/{pid}', [PostController::class, 'movegroup'])->name('movegroup');
  Route::get('/copygroup/{groupid}/{pid}', [PostController::class, 'copygroup'])->name('copygroup');
  Route::get('/movelang/{groupid}/{pid}', [PostController::class, 'movelang'])->name('movelang');

  //Route::post('movegroupupdate', [PostController::class, 'movegroupupdate'])
  //  ->name('movegroupupdate');

  Route::patch('/{groupid}/{pid}/movegroupupdate', [PostController::class, 'movegroupupdate'])->name('movegroupupdate');


  //Route::post('copygroupupdate', [PostController::class, 'copygroupupdate'])
  //  ->name('copygroupupdate');

  Route::patch('/{groupid}/{pid}/copygroupupdate', [PostController::class, 'copygroupupdate'])->name('copygroupupdate');


  #Route::post('movelangupdate', [PostController::class, 'movelangupdate'])
  #  ->name('movelangupdate');


  Route::patch('/{groupid}/{pid}/movelangupdate', [PostController::class, 'movelangupdate'])->name('movelangupdate');


  Route::post('update', [PostController::class, 'update'])
    ->name('update');

  Route::post('reversions', [PostController::class, 'reversions'])
    ->name('reversions');


  Route::get('/{post}/reversions-json', [PostController::class, 'reversionsJson']);

  Route::post('/restore/{reversion}', [PostController::class, 'restorereversion']);

  Route::get('/reversion-diff/{reversionId}', function ($reversionId) {
    return view('post.reversion-diff-page', ['reversionId' => $reversionId]);
  })->name('reversion-diff');



  // Route::post('destroy', [PostController::class, 'destroy'])
  //   ->name('destroy');

  Route::delete('/{groupid}/{pid}', [PostController::class, 'destroy'])->name('destroy');



  //Route::post('trash', [PostController::class, 'trash'])
  //  ->name('trash');

  Route::patch('/{groupid}/{pid}/trash', [PostController::class, 'trash'])->name('trash');



  //Route::post('untrash', [PostController::class, 'untrash'])
  //  ->name('untrash');

  Route::patch('/{groupid}/{pid}/untrash', [PostController::class, 'untrash'])->name('untrash');



  //Route::post('auditcheck', [PostController::class, 'auditcheck'])
  //  ->name('auditcheck');

  Route::patch('/{groupid}/{pid}/auditcheck', [PostController::class, 'auditcheck'])->name('auditcheck');



  //Route::post('audituncheck', [PostController::class, 'audituncheck'])
  //  ->name('audituncheck');

  Route::patch('/{groupid}/{pid}/audituncheck', [PostController::class, 'audituncheck'])->name('audituncheck');


  Route::get('/file-rights/{groupid}/{pid}', [FileRightsController::class, 'show'])->name('file-rights.show');
  Route::post('/file-rights/{id}', [FileRightsController::class, 'update'])->name('file-rights.update');

  Route::get('/comment-rights/{groupid}/{pid}', [CommentRightsController::class, 'edit'])->name('comment-rights.show');
  Route::post('/comment-rights/{id}', [CommentRightsController::class, 'update'])->name('comment-rights.update');


});