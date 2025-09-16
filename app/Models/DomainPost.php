<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.    All rights reserved.
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

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DomainPost
 * 
 * @property string $post_title
 * @property int $post_author
 * @property int $revised_author
 * @property int $id
 * @property string $post_content
 * @property Carbon $post_date
 * @property Carbon $updated_at
 * @property string $post_status
 * @property string $ip
 * @property string $md5code
 * @property string $rights
 * @property string $comment_rights
 * @property bool $canzip
 *
 * @package App\Models
 */
class DomainPost extends Model
{
	protected $table = 'domain_posts';
	protected $primaryKey = 'id';
	public $incrementing = true;
	public $timestamps = false;

	protected $casts = [
		'post_author' => 'int',
		'revised_author' => 'int',
		'post_status'=> 'int',
		'post_date' => 'datetime',
		'updated_at' => 'datetime',
		'canzip' => 'bool'
	];

	protected $fillable = [
		'post_title',
		'post_author',
		'revised_author',
		'post_content',
		'post_date',
		'updated_at',
		'post_status',
		'ip',
		'md5code',
		'rights',
		'comment_rights',
		'canzip'
	];

	public function postgroups()
	{
		return $this->hasMany(DomainPostID::class);
	}

	public function modifiedBy()
	{
		return $this->belongsTo(User::class, 'revised_author');
	}
	public function reversions()
	{
		return $this->hasMany(PostReversion::class, 'postid'); // Explicitly define foreign key
	}

}
