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
 * Class DomainDownloadZip
 * 
 * @property string $groupid
 * @property int $ziptype
 * @property string $filename
 * @property string $filedownloadname
 * @property string $realfilename
 * @property Carbon $updatedate
 *
 * @package App\Models
 */
class DomainDownloadZip extends Model
{
	protected $table = 'domain_download_zips';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'ziptype' => 'int',
		'updatedate' => 'datetime'
	];

	protected $fillable = [
		'filename',
		'filedownloadname',
		'realfilename',
		'updatedate'
	];
}
