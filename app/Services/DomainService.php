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


namespace App\Services;

use App\Models\DomainTree;
use App\Models\DomainTreeChildId;
use App\Models\DomainDict;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class DomainService
{

    public function get_children_by_groupid(string $groupId): array
    {
        // logger("get_children_by_groupid", [$groupId]);
        $groupId = trim($groupId);

        return array_map('trim', DomainTreeChildId::where('domainid', $groupId)
            ->pluck('child_id')
            ->toArray());
    }



    function get_title_by_id(int $id, int $lang): ?string
    {

        return DomainTree::where('lang', $lang)
            ->where('id', $id)
            ->value('domain_dict_name');
    }


    function get_first_title_by_id($id)
    {
        if (!is_numeric($id)) {
            return "";
        }

        // $record = DomainTree::find($id);
        logger($id);
        $domaindict = DomainDict::find($id);
        if (is_null($domaindict))
            return "";
        $record = DomainDict::find($id)->DomainTree;

        return $record ? $record->domain_dict_name : "";
    }

    function get_titledescription_by_id($id, $lang)
    {

        return DomainTree::where('lang', $lang)
            ->where('id', $id)
            ->value('description');

    }



    function get_first_titledescription_by_id($id)
    {
        if (!is_numeric($id))
            return "";

        $record = DomainTree::where('id', $id)
            ->first();


        return $record ? $record->description : "";
    }

    function get_jointitle_by_id($id)
    {
        if (!is_numeric($id))
            return "";
        $lang = (new LocaleService())->getcurlang();
        $title = trim($this->get_title_by_id($id, $lang));

        if ($title !== '')
            return $title;

        return $this->get_first_title_by_id($id);


    }



    function get_jointitledescription_by_id($id)
    {
        if (!is_numeric($id))
            return "";
        $lang = (new LocaleService())->getcurlang();
        $title = trim($this->get_titledescription_by_id($id, $lang));
        if ($title !== '')
            return $title;

        return $this->get_first_titledescription_by_id($id);
    }



    function get_jointitle_by_uniqid($groupid)
    {
        $id = $this->get_id_from_groupid($groupid);
        return $this->get_jointitle_by_id($id);

    }



    function get_joinGrouptitle_by_uniqid($groupid)
    {
        $ids = explode(".", $groupid);
        print_r($ids);
        $rtntitle = '';
        foreach ($ids as $id) {

            $rtntitle .= $this->get_jointitle_by_id($id) . '>';
        }
        return substr($rtntitle, 0, strlen($rtntitle) - 1);
    }


    function get_joinGroupLink_by_uniqid($groupid)
    {
        $ids = explode(".", $groupid);
        $rtntitle = '';
        $groupname = '';
        $lang = (new LocaleService())->getcurlang();
        $total = count($ids);
        $index = 0;
        foreach ($ids as $id) {
            $index++;
            $id = trim($id);
            if ($groupname === '')
                $groupname = $id;
            else
                $groupname .= '.' . $id;


            $rtitle = $this->get_jointitle_by_id($id);

            // Correctly call route() function
            $url = route('domainview.index', ['groupid' => $groupname]);
            $urlpostview = route('post.postview', ['groupid' => $groupname]);

            $rtntitle .= '<span style="display: inline-flex; align-items: center;">';
            $rtntitle .= '<a href="' . e($url) . '">' . e($rtitle) . '</a>';
            $rtntitle .= '<a href="' . e($urlpostview) . '">'
                . '<img src="/build/icons/app/list.svg" alt="" width="20" height="20" style="vertical-align: middle;" /></a>';
            $rtntitle .= '</span>';
            if ($index !== $total) {
                $rtntitle .= ' &gt; ';
            }

        }


        return substr($rtntitle, 0, strlen($rtntitle) - 1);
    }


    function get_joinLink_by_uniqid($groupid)
    {

        $id = trim($this->get_id_from_groupid($groupid));

        $rtitle = $this->get_jointitle_by_id($id);

        // Correctly call route() function
        $url = route('domainview.index', ['groupid' => $groupid]);
        $urlpostview = route('post.postview', ['groupid' => $groupid]);
        $rtntitle = '<div style="display: flex; align-items: center; gap: 4px;">';
        $rtntitle .= '<a href="' . e($url) . '">' . e($rtitle) . '</a>';
        $rtntitle .= '<a href="' . e($urlpostview) . '">'
            . '<img src="/build/icons/app/list.svg" alt="" class="w-5 h-3" /></a>';
        $rtntitle .= '</div>';
        return $rtntitle;
    }


    function get_joinGrouptitle_by_uniqidTwo($groupid)
    {
        $ids = explode(".", $groupid);
        $rtntitle = '';
        $icount = count($ids);
        $i = 0;
        foreach (array_reverse($ids) as $id) {
            $rtntitle = $this->get_jointitle_by_id($id) . '>' . $rtntitle;
            $i++;
            if ($i > 1) {
                if ($icount > 3)
                    $rtntitle = $this->get_jointitle_by_id($this->get_topid_from_groupid($groupid)) . '>...>' . $rtntitle;
                else if ($icount == 3)
                    $rtntitle = $this->get_jointitle_by_id($this->get_topid_from_groupid($groupid)) . '>' . $rtntitle;
                break;
            }
        }

        return substr($rtntitle, 0, strlen($rtntitle) - 1);
    }




    public function is_domain_leaf($groupId): bool
    {
        return DomainTreeChildId::where('domainid', $groupId)->count() === 0;
    }


    function get_id_from_groupid($groupid)
    {
        $ids = explode(".", $groupid);
        return end(array: $ids);
    }

    function get_topid_from_groupid($groupid)
    {
        $ids = explode(".", $groupid);
        return reset($ids);
    }



    function insertDomainTree($groupid, $titles, $descriptions)
    {

        $userid = auth()->user()->id;
        $newGroupId = $groupid;

        DB::transaction(function () use ($groupid, $titles, $descriptions, $userid) {

            $dict = new DomainDict();
            $dict->save();
            $dictId = $dict->id;

            $inserts = [];
            foreach ($titles as $lang => $title) {
                $langid = (new LocaleService)->getlangID($lang);
                $inserts[] = [
                    'id' => $dictId,
                    'domain_dict_name' => $title,
                    'lang' => $langid,
                    'description' => $descriptions[$lang] ?? null,
                ];
            }

            DB::table('domain_trees')->insert($inserts);


            DB::table('domain_id_manages')->insert([
                'userid' => $userid,
                'dictid' => $dictId,
                'm_type' => 'c',
            ]);

            if ($groupid === "0")
                $newGroupId = $dictId;
            else
                $newGroupId = $groupid . '.' . $dictId;

            DB::table('domain_managers')->insert([
                'userid' => $userid,
                'domainid' => $newGroupId,
                'm_type' => 'c',
                'cDate' => now()

            ]);

            DB::table('domain_tree_child_ids')->insert([
                'domainid' => $groupid,
                'child_id' => $dictId,
            ]);
        });
        return $newGroupId;
    }



    public function updateDomainTree($title, $description, $id, $lang)
    {
        $update = [
            'domain_dict_name' => $title,
            'description' => $description,
        ];

        DB::table('domain_trees')
            ->where('id', $id)
            ->where('lang', $lang)
            ->update($update);

        return true;
    }


    function checkDomainPublicStatus($domainID)
    {
        if (trim($domainID) === '') {
            return -1;
        }
        return DB::table('domain_managers')
            ->where('domainid', $domainID)
            ->where('m_type', 'c')
            ->value('bHide');
    }


}