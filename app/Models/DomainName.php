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

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DomainName
 * 
 * @property string $userid
 * @property bool $checked
 * @property string $domainid
 * @property bool $rights
 * @property string $name4group
 *
 * @package App\Models
 */
class DomainName extends Model
{
	protected $table = 'domain_names';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'checked' => 'bool',
		'rights' => 'bool'
	];

	protected $fillable = [
		'userid',
		'domainid',
		'checked',
		'rights',
		'name4group'
	];


	public static function getJoinMembers(string $groupid): array
	{
		return self::where('checked', 0)
			->where('domainid', $groupid)
			->pluck('userid')
			->toArray();
	}

	public static function getJoinUsers(string $groupid): Collection
	{
		return self::join('users', 'domain_names.userid', '=', 'users.id')
			->where('domain_names.checked', 0)
			->where('domain_names.domainid', $groupid)
			->select('users.*')
			->get();
	}
	public static function getNeedApproveMembers(string $groupid): array
	{
		return self::where('checked', 2)
			->where('domainid', $groupid)
			->pluck('userid')
			->toArray();
	}

	public static function getApplyUsers(string $groupid)
	{
		return self::join('users', 'domain_names.userid', '=', 'users.id')
			->where('domain_names.checked', 2)
			->where('domain_names.domainid', $groupid)
			->select('users.*')
			->get();
	}

	public static function countJoinGroup(string $groupid): int
	{
		return self::where('domainid', $groupid)->where('checked', 0)->count();
	}

	public static function countRequestedGroup(string $groupid): int
	{
		return self::where('domainid', $groupid)->where('checked', 2)->count();
	}

	public static function countBlockGroup(string $groupid): int
	{
		return self::where('domainid', $groupid)->where('checked', 1)->count();
	}


	public static function getJoinStatus(string $groupid): int
	{
		$userid = auth()->user()->id ?? null;
		if (!$userid) {
			return -1;
		}
		$checked = self::where('domainid', $groupid)
			->where('userid', $userid)
			->value('checked');

		return $checked !== null ? (int) $checked : -1;
	}


	public static function joinGroup(string $groupid): bool
	{
		$userid = auth()->id();
		if (!$userid) {
			return false; // Not logged in
		}
		$bHide = DomainManager::where('domainid', $groupid)
			->value('bHide');
		if ($bHide == 1)
			$check = 2;
		else
			$check = 0;
		$membership = self::create([
			'domainid' => $groupid,
			'userid' => $userid,
			'checked' => $check,
			'name4group' => auth()->user()->alias_name,
		]);

		return true;
	}

	public static function quitGroup(string $groupid): bool
	{
		$userid = auth()->id();
		if (!$userid) {
			return false; // Not logged in
		}
		// Find membership(s) and delete
		self::where('domainid', $groupid)
			->where('userid', $userid)
			->delete();

		return true;
	}


	public static function approveGroup(string $groupid, int $userid): bool
	{
		$update = [
			'checked' => 0,

		];

		self::where('domainid', $groupid)
			->where('userid', $userid)
			->update($update);

		return true;
	}


}
