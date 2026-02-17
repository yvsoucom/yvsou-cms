SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
SPDX-FileContributor: Lican Huang
SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary
 
## About yvsou-cms
  
 
**yvsou-cms** is a powerful content management system developed with Laravel by Hangzhou Domain Zones Technology Co., Ltd.  It features fine-grained permission control, dynamic directory structures, and is designed for extensibility and commercial readiness. yvsou-cms also features dynamic themes and plugins frameworks and shortcodes and filters similar with wordpress. yvsou-cms is a newly redeveloped content management system built with Laravel, encompassing the core functionalities of the DSCloud Platform, which was originally launched in 2011 by Hangzhou Domain Zones Technology Co., Ltd.



---
→ ## ⭐ Star This Repository  
→ **If you use yvsou-cms in your projects, star it to show support and help others discover it!**  
→ *Why star?* Stars improve GitHub visibility and prioritize bug fixes for active users.  

[![GitHub Stars](https://img.shields.io/github/stars/yvsoucom/yvsou-cms?style=social)](https://github.com/yvsoucom/yvsou-cms/stargazers)  
→ *Embedded star counter for social proof*  

## 🚀 Features

- Dynamic and hierarchical directory system  
- Group and File-based access control (GFAC)  
- Clean Laravel architecture  
- Optional commercial-grade add-ons  
- Dynamic themes upload and switch
- Dynamic plugins upload and activation
- Shortcodes and filters similar with wordpress
- Multi database engines supported, such as Mysql , PostgreSQL and SQLite
- Easy install guide similar with wordpress
- Easy upgrade similar with wordpress
- websocket support("workerman" or "swoole")


---

## 📄 Key Files

| File                  | Description                                 |
|-----------------------|---------------------------------------------|
| `composer.json`       | Laravel dependencies and package config     |
| `env.example`         | Sample environment configuration file       |
| `yvsou_example_config`| Sample custom  configuration file           |
| `routes/web.php`      | Web routes for frontend/backend             |




## 🛡️ License Options

yvsou-cms uses **dual licensing** to support both open-source and commercial use:

 ## Legal and Contribution Info

- 📜 [License (GPLv3)](./LICENSE.txt) — Open source terms under GPLv3  
- 💼 [Commercial License](./COMMERCIAL-LICENSE.md) — Terms for commercial usage and premium features  
- 🤝 [Contributing Guide](./CONTRIBUTING.md) — How to contribute and contributor rights  


 Dual License Rights Overview: GPLv3 vs Commercial
+---------------------+-------------------------+-----------------------------+
|                     | 👩‍💻 Contributor (GPLv3)  | 💼 Commercial User           |
+---------------------+-------------------------+-----------------------------+
| Use Freely          | ✅ Yes                  | ✅ Yes                      |
| Modify              | ✅ Yes                  | ✅ Yes                      |
| Share/Redistribute  | ✅ Yes (GPL required)   | ✅ Yes (no GPL required)    |
| Attribution         | ✅ Required             | ❌ Not required             |
| Private Use Only    | ✅ Yes                  | ✅ Yes                      |
| Legal Support       | ❌ No                   | ✅ Yes                      |
| Pay License Fee     | ❌ No                   | ✅ Yes (if not GPL)         |
+---------------------+-------------------------+-----------------------------+

## Dev guide

# Clone your repository from GitHub
git clone https://github.com/yvsoucom/yvsou-cms.git

# Go into the project directory
cd yvsou-cms
copy env.example .env
copy yvsou_example_config.php  config/yvsou_config.php
run mysql install.sql or install57.sql
edit .env for db setting

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# run yvsou-cms app
npm run dev


## Installation
  [INSTALL.MD](./INSTALL.MD) — Guide for installation 

[Working? Let us know ](https://github.com/yvsoucom/yvsou-cms/issues)

## 🛠️ Contribute
1. [Check out Good First Issues](https://github.com/yvsoucom/yvsou-cms/issues?q=is:open+is:issue+label:%22good+first+issue%22)

2. [Contribution Guidelines](./CONTRIBUTING.md)

3. Submit a PR!

→ New contributors welcome! 🙌

## Plugins
  MoneyPlugin , plugin supports several pay methods and accounting transactions  
  [https://github.com/yvsoucom/MoneyPlugin] https://github.com/yvsoucom/MoneyPlugin

## Themes
  PurityTheme , simple theme demostrates dunamic theme support.
  [https://github.com/yvsoucom/PurityTheme] https://github.com/yvsoucom/PurityTheme
 
## Live Metrics  

You can access the live Metrics here:

[https://yvsoucom.github.io/metrics-dashboard/](https://yvsoucom.github.io/metrics-dashboard/)

## Demo migration site  from dscloud platform

[https://cms.yvsou.com/]  migration from dscloud platform  (https://cms.yvsou.com/)     

## Demo test web site

[https://cms-test.yvsou.com/]   test yvsou-cms site  (https://cms-test.yvsou.com/)



# WebSocket Server (Workerman / Swoole)

yvsou-cms supports two WebSocket engines:

Workerman (default, pure PHP, easy to deploy)

Swoole (high-performance, requires PHP extension)

Only one engine runs at a time, selected by configuration.

1. Choose WebSocket Engine

In istallation, you choose the websocket driver.

Check your .env file:

WEBSOCKET_DRIVER=workerman


or

WEBSOCKET_DRIVER=swoole


No code changes are required.

2. Configuration

WebSocket settings are defined in:

config/websocket.php


Example:

return [
    'driver' => env('WEBSOCKET_DRIVER', 'workerman'),

    'workerman' => [
        'host' => '0.0.0.0',
        'port' => 8080,
        'worker_count' => 4,
        'heartbeat' => 10,
    ],

    'swoole' => [
        'host' => '0.0.0.0',
        'port' => 9502,
        'worker_num' => 4,
        'task_worker_num' => 2,
        'heartbeat_idle_time' => 60,
        'heartbeat_check_interval' => 30,
    ],
];

3. Start WebSocket Server

The system provides one unified command:

php artisan websocket:start


The command automatically starts:

Workerman if WEBSOCKET_DRIVER=workerman

Swoole if WEBSOCKET_DRIVER=swoole

4. Run as Background Service (Recommended)
Using Supervisor
[program:yvsou-websocket]
command=php artisan websocket:start
directory=/path/to/yvsou-cms
autostart=true
autorestart=true
user=www
stdout_logfile=/var/log/yvsou-websocket.log
stderr_logfile=/var/log/yvsou-websocket-error.log


This ensures the WebSocket server:

Restarts automatically on crash

Runs independently of Laravel HTTP requests

5. Architecture Notes

WebSocket servers do NOT start automatically with Laravel HTTP

They run as separate CLI processes

Laravel is bootstrapped only to:

Load config

Access services

Dispatch events

This design ensures:

✔ No performance impact on HTTP
✔ Clean separation of concerns
✔ Easy debugging and scaling

6. When to Use Which Engine
Engine	Recommended For
Workerman	Shared hosting, simple deployment
Swoole	High concurrency, long-running connections
7. File Structure
ws/
├── workerman-server.php
└── swoole-server.php

app/Console/Commands/
└── WebSocketStartCommand.php

8. Notes

Only one WebSocket engine runs at a time

Switching engines only requires changing .env

No database migration is required

Heartbeat is handled internally by each engine






## Publish Paper References
[Lican Huang, Authorization Policies and Co-Operating Strategies of DSCloud Platform
](https://arxiv.org/pdf/1801.02147)
[Lican Huang, Directory Service Provided by DSCloud Platform
](https://arxiv.org/pdf/1710.08101)