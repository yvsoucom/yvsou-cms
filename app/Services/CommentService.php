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

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DomainComment;

class CommentService
{


  public function getCommentNumbers($pid)
  {


    return DB::table('domain_comments')
      ->where('postid', $pid)
      ->count();
  }



  function isCommentAuthor($commentId)
  {
    $currentUserId = Auth::id();

    if (!$currentUserId) {
      return false;
    }

    $commentUserId = DB::table('domain_comments')
      ->where('id', $commentId)
      ->value('userid');
    return $currentUserId === $commentUserId;
  }



  function isParentComment($postId, $commentId)
  {
    return DB::table('domain_comments')
      ->where('postid', $postId)
      ->where('comment_parent', $commentId)
      ->count();
  }


  function getChildrenComments($postId, $comment_parentId)
  {
    $query = DomainComment::where('postid', $postId)
      ->where('comment_parent', $comment_parentId);
    return $query->get([
      'id',
      'comment_date',
      'comment_content',
      'comment_parent',
      'userid',
      'comment_approved',
    ]);
  }


}



