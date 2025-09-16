<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
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