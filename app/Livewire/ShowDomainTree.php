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
namespace App\Livewire;

use Livewire\Component;
use App\Services\DomainService;
use App\Models\DomainManager;

class ShowDomainTree extends Component
{
    public $expanded = [];
    public $children = [];

    public $groupid;
    public $domain;

    public function mount()
    {
        // Initialize root domain groupid
        $tmpgroupid = DomainManager::getFirstGroupid();
        $tmpgroupid = trim($tmpgroupid);
        if ($tmpgroupid)
            $this->groupid = trim((new DomainService())->get_topid_from_groupid($tmpgroupid));

        if ($this->groupid) {
            $thislink = (new DomainService())->get_joinLink_by_uniqid($this->groupid);
            $this->domain = [
                'groupid' => $this->groupid,
                'name' => $thislink,
                // 'name' => trim((new DomainService())->get_jointitle_by_uniqid($this->groupid)),
            ];

            // Load first-level children of root domain
            $this->loadChildren($this->groupid);

            foreach ($this->children[$this->groupid] as $child) {
                $this->loadnextChildren($child);
            }
            // Root expanded by default
            $this->expanded[] = $this->groupid;
        }
    }

    // Load children for a given groupid, if not loaded
    public function loadChildren($groupid)
    {
        $groupid = trim($groupid);
        if (!is_array($this->children) || !isset($this->children[$groupid])) {
            $childIds = (new DomainService())->get_children_by_groupid($groupid);
            //   logger("childids", [$childIds]);
            $this->children[$groupid] = []; // initialize
            foreach ($childIds as $childId) {
                $childId = trim($childId);
                $childgroupid = "{$groupid}.{$childId}";
                $this->children[$groupid][] = $childgroupid;

            }
        }
        //   logger("this_childids", [$this->children[$groupid]]);
    }

    public function loadnextChildren($groupid)
    {
        $groupid = trim($groupid);
        if (!isset($this->children[$groupid])) {
            $childIds = (new DomainService())->get_children_by_groupid($groupid);
            //   logger("nextchildids", [$childIds]);
            $this->children[$groupid] = []; // initialize
            foreach ($childIds as $childId) {
                $childId = trim($childId);
                $childgroupid = "{$groupid}.{$childId}";
                $this->children[$groupid][] = $childgroupid;
            }
        }
        // logger("loadnextChildren", [$this->children[$groupid]]);
    }


    public function toggle($groupid)
    {
        if (in_array($groupid, $this->expanded)) {
            // Collapse
            $this->expanded = array_diff($this->expanded, [$groupid]);
        } else {
            // Expand
            $this->expanded[] = $groupid;
            // Lazy load children if not loaded
            $this->loadChildren($groupid);
            foreach ($this->children[$groupid] as $child) {
                $this->loadnextChildren($child);
            }

        }
    }

    public function render()
    {
        return view('livewire.show-domain-tree', [
            'domain' => $this->domain,
            'children' => $this->children,
            'expanded' => $this->expanded,
        ]);
    }
}
