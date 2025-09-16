<?php
/**
 * SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
 * SPDX-FileContributor: Lican Huang
 * @created 2025-08-23
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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('domain_msg_centers')) {
            Schema::create('domain_msg_centers', function (Blueprint $table) {
                $table->bigIncrements('msgid');                  // Auto-increment primary key
                $table->mediumText('msg_content');              // Message content
                $table->integer('from_userid');                 // Sender user ID
                $table->integer('to_userid')->index('touser'); // Recipient user ID with index
                $table->integer('msg_handled')->default(0)     // 0 = unread, 1 = read
                    ->comment('0, unread; 1, read');
                $table->string('to_domainid')->index('todomainid'); // Recipient domain ID with index
                $table->tinyInteger('cast_type')->default(0)    // 0=user, 1=domain, 2=domain+sub, 3=all
                    ->comment('0 user , 1 domain 2 domain and sub domain 3 all');
                $table->integer('lang');                        // Language code
                $table->timestamp('dtime')->useCurrent();       // Default current timestamp
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_msg_centers');
    }
};
