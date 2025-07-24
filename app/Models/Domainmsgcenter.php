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
 
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DomainMsgCenter
 * 
 * @property int $msgid
 * @property string $msgcontent
 * @property string $fromuser
 * @property string $touser
 * @property int $msghandled
 * @property string $to_domainid
 * @property int $casttype
 * @property int $lang
 * @property Carbon $dtime
 *
 * @package App\Models
 */
class DomainMsgCenter extends Model
{
	protected $table = 'domain_msg_centers';
	protected $primaryKey = 'msgid';
	public $timestamps = false;

	protected $casts = [
		'from_userid' => 'int',
		'to_userid' => 'int',
		'msg_handled' => 'int',
		'cast_type' => 'int',
		'lang' => 'int',
		'dtime' => 'datetime'
	];

	protected $fillable = [
		'msg_content',
		'from_userid',
		'to_userid',
		'msg_handled',
		'to_domainid',
		'cast_type',
		'lang',
		'dtime'
	];

	
}
