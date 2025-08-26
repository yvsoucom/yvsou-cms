<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.
// SPDX-FileCopyrightText: 2025 Institute of Future Science and Technology G.K., Tokyo
// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Installing Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow w-96 text-center">
        <div class="flex justify-center mb-4">
            <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
        </div>
        <div id="status" class="text-lg text-gray-700 mb-4">Preparing migrations...</div>

        <!-- Progress bar -->
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div id="progress-bar" class="bg-blue-600 h-4 rounded-full" style="width: 0%;"></div>
        </div>

        <!-- Log output -->
        <div id="log" class="text-left text-sm text-gray-600 mt-4 h-32 overflow-y-auto border p-2 rounded"></div>
    </div>

    <script>
        const statusEl = document.getElementById('status');
        const progressBar = document.getElementById('progress-bar');
        const logEl = document.getElementById('log');

        const evtSource = new EventSource('{{ route("install.migrateStream") }}');

        evtSource.addEventListener('progress', function(e) {
            const data = JSON.parse(e.data);
            statusEl.innerText = "Migrating: " + data.message;
            progressBar.style.width = data.percent + "%";
            logEl.innerHTML += "<div>✔ " + data.message + "</div>";
            logEl.scrollTop = logEl.scrollHeight;
        });

        evtSource.addEventListener('log', function(e) {
            const data = JSON.parse(e.data);
            logEl.innerHTML += "<div>" + data.message + "</div>";
            logEl.scrollTop = logEl.scrollHeight;
        });

        evtSource.addEventListener('complete', function(e) {
            const data = JSON.parse(e.data);
            statusEl.innerText = "Migration complete! Redirecting...";
            evtSource.close();
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 2000);
        });
    </script>
</body>
</html>
