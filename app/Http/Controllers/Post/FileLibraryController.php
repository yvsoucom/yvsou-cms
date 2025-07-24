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

namespace App\Http\Controllers\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use App\Models\DomainUploadAttach;
use Illuminate\Support\Facades\Auth;

class FileLibraryController extends Controller
{
    public function index()
    {


        $fileAttach = DomainUploadAttach::where('userid', Auth::id())
            ->latest('id') // Or 'created_at' if preferred
            ->get();

        $files = collect($fileAttach)->map(function ($file) {
            // $url = url($file->realfilename);
            $url = url("protected/" . $file->realfilename);
            $name = $file->filedownloadname;
            $ext = $file->extention;
            $type = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']) ? 'image' : 'doc';
            $ext = strtolower($ext);

            // Determine type by extension
            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'webp':
                case 'bmp':
                case 'svg':
                    $type = 'img';
                    break;
                case 'pdf':
                    $type = 'pdf';
                    break;
                case 'doc':
                case 'docx':
                    $type = 'doc';
                    break;
                case 'zip':
                case 'rar':
                case '7z':
                    $type = 'zip';
                    break;
                default:
                    $type = 'other';
            }

            return compact('url', 'name', 'type');
        });

        return response()->json($files);
    }

}