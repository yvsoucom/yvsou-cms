<?php
/**
 * © 2025 Hangzhou Domain Zones Technology Co., Ltd.    All rights reserved.
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


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DomainComment;
use App\Models\User;

class PostCommented extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $comment;
    public $location;

    public function __construct(User $user, DomainComment $comment, string $location)
    {
        $this->user = $user;
        $this->comment = $comment;
        $this->location = $location;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    { 
        return (new MailMessage)
            ->subject("Your paper commented by: {$this->comment->userid}")
            ->greeting("Hello, {$notifiable->name}")
           // ->line("Your paper “{$this->postid->title}” was commented on by {$this->comment->userid}.")
            ->line("Your paper “{$this->comment->postid}” was commented on by {$this->comment->userid}.")
         
            ->line("Comment: “{$this->comment->comment_content}”")
            ->action('View Comment', $this->location);
    }
}
