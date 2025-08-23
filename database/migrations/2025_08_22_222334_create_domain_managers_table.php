<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
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

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('domain_managers', function (Blueprint $table) {
            $table->integer('userid')->nullable();
            $table->enum('m_type', ['c', 'm', 's']);
            $table->string('domainid', 600)->primary();
            $table->unsignedTinyInteger('owner_rights')->default(255);
            $table->unsignedTinyInteger('own_group_rights')->default(255);
            $table->unsignedTinyInteger('grant_group_rights')->default(255);
            $table->unsignedTinyInteger('grant_user_rights')->default(255);
            $table->unsignedTinyInteger('any_user_rights')->default(255);
            $table->boolean('bChange')->default(true);
            $table->boolean('bAddchild')->default(true);
            $table->boolean('dDelchild')->default(true);
            $table->boolean('bHide')->default(false)->comment('0, public, 1, private, 2 , hide');
            $table->boolean('bTrash')->default(false);
            $table->string('IP', 256)->default('0.0.0.0');
            $table->dateTime('cDate')->index('cdate');
            $table->unsignedInteger('sem')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_managers');
    }
};
