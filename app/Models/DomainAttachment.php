<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd., Institute of Future Science and Technology G.K., Tokyo   All rights reserved.
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
 * Class DomainAttachment
 * 
 * @property int $ID
 * @property int $post_author
 * @property Carbon $post_date
 * @property string $guid
 * @property string $post_mime_type
 * @property string $post_status
 * @property string $attachment_metadata
 * @property string $attached_file
 *
 * @package App\Models
 */
class DomainAttachment extends Model
{
	protected $table = 'domain_attachments';
	protected $primaryKey = 'ID';
	public $timestamps = false;

	protected $casts = [
		'post_author' => 'int',
		'post_date' => 'datetime'
	];

	protected $fillable = [
		'post_author',
		'post_date',
		'guid',
		'post_mime_type',
		'post_status',
		'attachment_metadata',
		'attached_file'
	];
}
