<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileContributor: Lican Huang
* @created 2025-08-03
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

namespace App\Http\Controllers\Websocket;

use Illuminate\Http\Request;
use App\Services\HttpApiService;
use App\Http\Controllers\Controller;
class ExternalApiController extends Controller
{
    public function remoteCall(Request $request, HttpApiService $httpApiService)
    {
        // 获取 URL 参数
        $url = $request->query('url');

        // 校验 URL 是否为空
        if (empty($url)) {
            return response()->json([
                'error' => true,
                'message' => '缺少 url 参数',
            ], 400);
        }

        // 调用服务类发起请求
        $result = $httpApiService->callRemote($url);

        return response()->json($result);
    }
}
