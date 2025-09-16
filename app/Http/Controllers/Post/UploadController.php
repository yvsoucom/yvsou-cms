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

namespace App\Http\Controllers\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use App\Models\DomainUploadAttach;
use Illuminate\Support\Facades\Auth;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate file existence
        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,webp,pdf,doc,docx,zip|max:5120' // up to 5MB
        ]);
        $file = $request->file('file'); // or 'image', etc.   
        if (!$file || !$file->isValid()) {
            return response()->json(['error' => 'Invalid file.'], 400);
        }

        // Get current year and month
        $year = now()->format('Y');
        $month = now()->format('m');
        $folder = "uploads/{$year}/{$month}";
        try {
            $userid = auth()->user()->id;
            $extension = $file->getClientOriginalExtension();
            $md5filename = md5_file($file->getRealPath());
            $downloadfilename = basename($file->getClientOriginalName());
            $filename = $md5filename . '.' . $extension;

            //$resized = $manager->make($request->file('file'))->resize(300, 300);
            $path = $file->storeAs($folder, $filename, 'private');

            $uploadattach = DomainUploadAttach::create([
                'userid' => $userid,
                'md5filename' => $md5filename,
                'filedownloadname' => $downloadfilename,
                'realfilename' => $path,
                'extention' => $extension,
            ]);
            $url = url("protected/" . $path);
            //return response()->json(['success' => true, 'url' => Storage::url($path)]);
             return response()->json(['success' => true, 'url' => $url]);
        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Server error during upload.',
                'message' => $e->getMessage()
            ], 500);
        }

        // $url = Storage::url($path);

    }



    public function processUpload(Request $request)
    {
        //  $data = $request->input('rawData');

        $latestAttach = DomainUploadAttach::where('userid', Auth::id())
            ->latest('id') // Or 'created_at' if preferred
            ->first();

        return response()->json([
            'realfilename' => $latestAttach->realfilename,
            'filedownloadname' => $latestAttach->filedownloadname,
            'extention' => $latestAttach->extention,
        ]);
    }

}
