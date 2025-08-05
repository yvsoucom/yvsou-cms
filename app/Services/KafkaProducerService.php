<?php
/**
* SPDX-FileCopyrightText: (c) 2025  Hangzhou Domain Zones Technology Co., Ltd.
* SPDX-FileCopyrightText: Institute of Future Science and Technology G.K., Tokyo
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

// app/Services/KafkaProducerService.php

namespace App\Services;

use Kafka\Producer;
use Kafka\ProducerConfig;

class KafkaProducerService
{
    protected Producer $producer;

    public function __construct()
    {
        $config = ProducerConfig::getInstance();
        $config->setMetadataBrokerList(config('kafka.brokers', 'localhost:9092'));
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);

        $this->producer = new Producer();
    }

    public function send(string $topic, array|string $message): void
    {
        $this->producer->send([
            [
                'topic' => $topic,
                'value' => is_array($message) ? json_encode($message) : $message,
                'key'   => '',
            ],
        ]);
    }
}
