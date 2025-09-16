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
<div x-data="reversionModal()" x-init="init()" x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 dark:bg-opacity-70" x-cloak>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl mx-4 p-6 transition-colors duration-200">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ __('post.reversionhist') }}</h3>
            <button @click="show = false" class="text-gray-600 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 text-xl transition-colors">&times;</button>
        </div>

        <div x-show="loading" class="text-center text-gray-500 dark:text-gray-400 py-10">{{ __('post.loading') }}</div>

        <div x-show="!loading && reversions.length === 0" class="text-center text-gray-400 dark:text-gray-500">
            {{ __('post.norevrsionfound') }}</div>

        <ul x-show="!loading" class="divide-y divide-gray-200 dark:divide-gray-700 max-h-80 overflow-y-auto">
            <template x-for="rev in reversions" :key="rev.id">
                <li class="py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex justify-between items-center mb-1">
                        <div>
                            <strong class="text-gray-900 dark:text-gray-200" x-text="rev.modified_by_name"></strong>
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="rev.modified_at"></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="rev.title"></span>
                        </div>
                        <div class="space-x-2">
                            <a :href="`/post/reversion-diff/${rev.id}/`" target="_blank"
                                class="text-blue-600 dark:text-blue-400 hover:underline text-sm">{{ __('post.diff') }}</a>
                            <button @click="restorereversion(rev.id)"
                                class="text-green-600 dark:text-green-400 hover:underline text-sm">{{ __('post.restore') }}</button>
                        </div>
                    </div>
                    <div class="text-sm text-gray-700 dark:text-gray-300 italic" x-text="rev.preview"></div>
                </li>
            </template>
        </ul>

        <div class="pt-4 text-center" x-show="nextPageUrl">
            <button @click="loadMore" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">{{ __('post.loadmore') }}</button>
        </div>
    </div>
</div> 

<script>
    window.reversionModalInstance = null;

    function reversionModal() {
        return {
            show: false,
            postId: null,
            loading: false,
            reversions: [],
            nextPageUrl: null,

            open(id) {
                this.show = true;
                this.postId = id;
                this.loading = true;
                fetch(`/post/${id}/reversions-json`)
                    .then(res => res.json())
                    .then(data => {
                        this.reversions = data.reversions;
                        this.nextPageUrl = data.next_page_url;
                        this.loading = false;
                    });
            },

            loadMore() {
                if (!this.nextPageUrl) return;
                this.loading = true;
                fetch(this.nextPageUrl)
                    .then(res => res.json())
                    .then(data => {
                        this.reversions.push(...data.reversions);
                        this.nextPageUrl = data.next_page_url;
                        this.loading = false;
                    });
            },

            restorereversion(id) {
                if (confirm(@json(__('post.confirm_restore')))) {

                    fetch(`/post/restore/${id}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }).then(() => window.location.reload());
                }
            },

            init() {
                window.reversionModalInstance = this;
            }
        }
    }

</script>