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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Group\GroupController;



// Public


Route::prefix('group')->name('group.')->group(function () {

});



Route::middleware(['auth'])->prefix('group')->name('group.')->group(function () {

    Route::post('/setpublic', [GroupController::class, 'setpublic'])->name('setpublic');
    Route::post('/setprivate', [GroupController::class, 'setprivate'])->name('setprivate');

    Route::patch('/{groupid}/joingroup', [GroupController::class, 'joingroup'])->name('joingroup');
    Route::patch('/{groupid}/quitgroup', [GroupController::class, 'quitgroup'])->name('quitgroup');

    Route::patch('/{groupid}/invitegroup', [GroupController::class, 'invitegroup'])->name('invitegroup');
    Route::patch('/{groupid}/auditcheckgroup', [GroupController::class, 'auditcheckgroup'])->name('auditcheckgroup');
    Route::patch('/{groupid}/unauditcheckgroup', [GroupController::class, 'unauditcheckgroup'])->name('unauditcheckgroup');


    Route::get('/groupmessage', [GroupController::class, 'groupmessage'])->name('groupmessage');

    Route::get('/sendMessage2Users', [GroupController::class, 'sendMessage2Users'])->name('sendMessage2Users');

    Route::post('/editcastmessage', [GroupController::class, 'editcastmessage'])->name('editcastmessage');

    Route::get('/editmessage', [GroupController::class, 'editmessage'])->name('editmessage');


    Route::post('/castmessagestore', [GroupController::class, 'castmessagestore'])->name('castmessagestore');

    Route::post('/messagestore', [GroupController::class, 'messagestore'])->name('messagestore');

    Route::get('/approvegroup', [GroupController::class, 'approvegroup'])->name('approvegroup');

    Route::post('/storeapprove', [GroupController::class, 'storeapprove'])->name('storeapprove');

});

