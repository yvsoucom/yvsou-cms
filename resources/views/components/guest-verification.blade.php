<?php
// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

// SPDX-FileContributor: Lican Huang
// SPDX-License-Identifier: GPL-3.0-or-later OR LicenseRef-Proprietary

/**
 * This program is dual-licensed under GPLv3 or a commercial license.
 * See the GPLv3 license at: https://www.gnu.org/licenses/gpl-3.0.html
 * For commercial use, contact: yvsoucom@gmail.com
 */
?>
@guest
    @if(config('yvsou_config.BLOCKBOT'))
        <div class="max-w-md mx-auto bg-white shadow-md rounded p-6 mt-10">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verify') }}">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium mb-1">Math CAPTCHA:</label>
                    <div class="flex items-center space-x-3">
                        <span>{!! captcha_img('math') !!}</span>
                        <button type="button" onclick="refreshCaptcha()" class="text-sm text-blue-600">Reload</button>
                    </div>
                    <input type="text" name="captcha" class="w-full mt-2 border rounded p-2" required>
                    @error('captcha')
                        <p class="text-red-500 text-sm">Invalid CAPTCHA</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium mb-1">Drag the key into the lock:</label>
                    <div class="flex items-center space-x-6">
                        <img id="draggable" src="/build/images/app/key.png" draggable="true"
                            class="w-12 cursor-move transition-transform duration-700 ease-in-out transform-origin-left">

                        <div id="lock-wrapper"
                            class="relative w-24 h-24 border border-gray-400 p-2 rounded flex items-center justify-center">
                            <img id="target" src="/build/images/app/lock.png" class="w-full h-full object-contain pointer-events-none">
                        </div>
                    </div>
                    <input type="hidden" name="drag_verified" id="drag_verified" value="0">
                    @error('drag_verified')
                        <p class="text-red-500 text-sm">Drag verification failed</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
            </form>
        </div>
        <script>
            function refreshCaptcha() {
                fetch("captcha/math")
                    .then(res => res.text())
                    .then(html => document.querySelector('span').innerHTML = html);
            }

            document.addEventListener('DOMContentLoaded', () => {
                const draggable = document.getElementById('draggable');
                const lockWrapper = document.getElementById('lock-wrapper');
                const lockImage = document.getElementById('target');
                const dragVerified = document.getElementById('drag_verified');

                lockWrapper.addEventListener('dragover', e => e.preventDefault());

                lockWrapper.addEventListener('drop', (e) => {
                    e.preventDefault();

                    const lockRect = lockWrapper.getBoundingClientRect();
                    const bodyRect = document.body.getBoundingClientRect();

                    draggable.style.position = 'absolute';
                    draggable.style.left = (lockRect.left - bodyRect.left + lockRect.width * 0.1) + 'px';
                    draggable.style.top = (lockRect.top - bodyRect.top + lockRect.height * 0.5 - draggable.offsetHeight / 2) + 'px';

                    draggable.setAttribute('draggable', 'false');
                    draggable.style.cursor = 'default';
                    draggable.style.transformOrigin = 'left center';
                    draggable.style.transform = 'rotate(90deg)';

                    lockImage.src = '/build/images/app/unlock.png';
                    dragVerified.value = 1;

                    lockWrapper.classList.remove('border-gray-400');
                    lockWrapper.classList.add('border-green-500');
                });

                // Optional reset
                document.body.addEventListener('drop', e => {
                    if (!lockWrapper.contains(e.target)) {
                        draggable.style.position = 'relative';
                        draggable.style.left = '';
                        draggable.style.top = '';
                        draggable.style.transform = 'rotate(-90deg)';
                        draggable.setAttribute('draggable', 'true');
                        draggable.style.cursor = 'grab';
                        dragVerified.value = 0;

                        lockWrapper.classList.remove('border-green-500');
                        lockWrapper.classList.add('border-gray-400');
                    }
                });
            });
        </script>


    @endif
@endguest