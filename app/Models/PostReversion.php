<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.,     All rights reserved.
 * Author: Lican Huang
 * 
 * SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary
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


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostReversion extends Model
{
    protected $table = 'post_reversions';

    protected $fillable = [
        'postid',
        'userid',
        'post_title',
        'version',
        'base_content',
        'diff',
        'ip',
        'updated_at',
        'md5code',
    ];

    protected $casts = [
        'postid'     => 'integer',
        'userid'     => 'integer',
        'version'    => 'integer',
        'updated_at' => 'datetime',
        'diff'       => 'array', // Laravel will json_decode this
    ];

    public $timestamps = false;

    // Relationships
    public function post(): BelongsTo
    {
        return $this->belongsTo(DomainPost::class, 'postid');
    }
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid');
    }

     // Define the 'modifiedBy' relationship
    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}
