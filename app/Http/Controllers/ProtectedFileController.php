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


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\RightsService;
class ProtectedFileController extends Controller
{
    public function show($filename, Request $request)
    {
        $groupid = $request->query('groupid');
        $pid = $request->query('pid');
        if (!$groupid)
            $groupid = "0";
        if (!$pid)
            $pid = 0;
      //  logger("groipid, pid",[$groupid, $pid]);
        if (!(new RightsService())->attachfileAccess($filename, $groupid, $pid)) {
            abort(404);
        }
        $path = storage_path("app/private/{$filename}");
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }


}
