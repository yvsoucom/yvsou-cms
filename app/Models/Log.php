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
 * Class Log
 * 
 * @property int $logID
 * @property string $user_login
 * @property Carbon $dtime
 * @property string $type
 * @property string $contents
 * @property string $where
 * @property string $fromUser
 * @property string $toUser
 * @property string $msgtype
 * @property int $creattime
 *
 * @package App\Models
 */
class Log extends Model
{
	protected $table = 'logs';
	protected $primaryKey = 'logID';
	public $timestamps = false;

	protected $casts = [
		'dtime' => 'datetime',
		'creattime' => 'int'
	];

	protected $fillable = [
		'user_login',
		'dtime',
		'type',
		'contents',
		'where',
		'fromUser',
		'toUser',
		'msgtype',
		'creattime'
	];
}
