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

/**
 * Class DomainRightManager
 * 
 * @property string $username
 * @property string $domainID
 * @property bool $bAddchild
 * @property bool $dDelchild
 * @property bool $bHide
 * @property bool $bTrash
 *
 * @package App\Models
 */
class DomainRightManager extends Model
{
	protected $table = 'domain_right_managers';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'bAddchild' => 'bool',
		'dDelchild' => 'bool',
		'bHide' => 'bool',
		'bTrash' => 'bool'
	];

	protected $fillable = [
		'username',
		'domainID',
		'bAddchild',
		'dDelchild',
		'bHide',
		'bTrash'
	];
}
