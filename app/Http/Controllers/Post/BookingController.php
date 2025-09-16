<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileContributor: Lican Huang
* @created 2025-09-04
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

use App\Models\Booking;
use App\Models\DomainPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function requestFile(DomainPost $post)
    {
        $booking = Booking::create([
            'post_id'      => $post->id,
            'requester_id' => Auth::id(),
            'status'       => 'pending',
        ]);

        // TODO: WebSocket notify provider
        return response()->json([
            'message' => 'Request sent to provider',
            'booking' => $booking,
        ]);
    }

    public function relayUpload(Request $request, Booking $booking)
    {
        $file = $request->file('file');
        $path = $file->store('tmp'); // storage/app/tmp

        $booking->update([
            'status'   => 'relay',
            'tmp_file' => $path,
        ]);

        // TODO: WebSocket notify consumer
        return response()->json([
            'message' => 'File uploaded to relay',
            'booking' => $booking,
        ]);
    }

    public function download(Booking $booking)
    {
        if ($booking->status !== 'relay') {
            return response()->json(['error' => 'File not available'], 400);
        }

        return response()->download(storage_path("app/{$booking->tmp_file}"));
    }
}
