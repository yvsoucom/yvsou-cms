<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025 Institute of Future Science and Technology G.K., Tokyo
// SPDX-FileContributor: Lican Huang
//
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AutoUpdaterService;
use Illuminate\Http\Request;

class UpdaterController extends Controller
{
    protected $updater;

    public function __construct(AutoUpdaterService $updater)
    {
        $this->updater = $updater;
    }



    public function run(Request $request)
    {

        $success = $this->updater->applyUpdate();

        return back()->with(
            'status',
            $success
            ? "Update applied! "
            : "Update failed."
        );
    }
}
