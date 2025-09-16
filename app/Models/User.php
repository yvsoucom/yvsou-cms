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
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Services\RightsService;
use App\Services\ConstantService;
use App\Services\DomainService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $alias_name 
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $role
 *
 * @package App\Models
 */
class User extends Authenticatable implements MustVerifyEmail
{

	use HasFactory, Notifiable;
	protected $table = 'users';

	protected $casts = [
		'email_verified_at' => 'datetime'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'alias_name',
		'email',
		'email_verified_at',
		'password',
		'remember_token',
		'role'
	];


	// In app/Models/User.php
	public function isAdmin()
	{
		return $this->role === 'admin';
	}


	public function isAuthorOfPost($pid): bool
	{

		return DomainPost::where('id', $pid)
			->where(function ($query) {
				$query->where('post_author', $this->id)
					->orWhere('revised_author', $this->id);
			})
			->exists()
			||
			PostReversion::where('postid', $pid)
				->where('userid', $this->id)
				->exists();

	}


	public function withinGroup($groupid): bool
	{
		return DomainName::where('userid', $this->id)
			->where('domainid', trim($groupid))
			->where('checked', 0)
			->exists();
	}

	public function hasApplyJoinGroup($groupid): bool
	{
		return DomainName::where('userid', $this->id)
			->where('domainid', trim($groupid))
			->exists();
	}

	public function withingrantgroup($groupid)
	{
		$grantGroups = DomainGrantGroup::where('domainid', $groupid)->pluck('grant_domain');

		foreach ($grantGroups as $grantDomainId) {
			if ($this->withinGroup($grantDomainId)) {
				return true;
			}
		}
		return false;
	}

	public function isPaperOwner($pid)
	{
		return DomainPost::where('post_author', $this->id)
			->where('id', $pid)
			->exists();
	}

	public function canManagePaper($pid )
	{
		if (ConstantService::$adminHasAllRights)
			if ($this->isAdmin())
				return true;
		return $this->isPaperOwner($pid);
	}
	public function isGrantUser($groupId)
	{

		return DomainGrantUser::where('userid', $this->id)
			->where('domainid', $groupId)
			->exists();
	}

	public function isManageDomainOwner($domainID): bool
	{

		return DomainManager::where('domainid', $domainID)
			->where('userid', $this->id)
			->where('m_type', 'c')
			->exists();
	}

	public function withDomainPublicStatus($domainID): bool
	{
		if ($this->isManageDomainOwner($domainID)) {
			$status = (new DomainService())->checkDomainPublicStatus($domainID);
			return $status;
		}
		return -1;
	}




	public function getAliasNameByID($id)
	{
		$userAlias = User::where('id', $id)->value('alias_name');

		$username = trim($userAlias ?? '');
		if ($username == "")
			return User::where('id', $id)->value('name');
		return $username;
	}
	public function canComment($pid, $groupId, $type)
	{
		return (new RightsService())->checkCommentRightPermission($pid, $groupId, $type);
	}
	public function canDomainRights($groupId, $type)
	{
		if ($groupId === '0')
			if ($this->isAdmin())
				return true;
			else
				return false;

		if (ConstantService::$adminHasAllRights)

			if ($this->isAdmin())
				return true;

		return (new RightsService())->checkRightPermission($groupId, $type);
	}

	public function canUpdateDomainRights($groupId )
	{

		if (ConstantService::$adminHasAllRights)

			if ($this->isAdmin())
				return true;

		return $this->isManageDomainOwner($groupId);
	}
}
