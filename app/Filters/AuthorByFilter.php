<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
* SPDX-FileContributor: Lican Huang
* @created 2025-08-13
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

// app/Filters/ExampleFilter.php

use function app\Helpers\add_filter;

// Add a filter to modify some output globally
// Define $lastauthorby and $updated_at before using them in the closure

/* usage example
 $author_by = apply_filters(
                    'modify_author_by',
                    $author_by,          // base value
                    $lastauthorby,       // dynamic arg 1
                    $post->updated_at    // dynamic arg 2
                );
*/

// Register filter with 3 args
add_filter('modify_author_by', function ($author_by, $lastauthorby, $updated_at) {
    return $author_by . " last modified by {$lastauthorby} {$updated_at}";
}, 10, 3);
