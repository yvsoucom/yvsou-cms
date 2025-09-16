// SPDX-FileCopyrightText: 2025 Hangzhou Domain Zones Technology Co., Ltd.

// SPDX-License-Identifier: MIT  

import './setup-jquery.js';

import 'trumbowyg/dist/trumbowyg.min.js';
import 'trumbowyg/dist/ui/trumbowyg.min.css';
import 'trumbowyg/plugins/upload/trumbowyg.upload';


$.trumbowyg.svgPath = window.trumbowygSvgPath; // '/icons/trumbowyg/icons.svg';

let isEditorInitialized = false;

function initEditor() {
    if (isEditorInitialized) return;
    isEditorInitialized = true;

    function generateFilePreviewHTML(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        const iconSize = '40px';

        let preview = '';

        if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'].includes(ext)) {
            preview = `<img src="${file.url}" alt="${file.name}" style="height:60px; max-width:100px; object-fit:contain;" title="${file.name}">`;
        } else if (ext === 'pdf') {
            preview = `<img src="/build/icons/app/pdf.svg" alt="PDF icon" style="width:${iconSize}; height:${iconSize};" title="${file.name}">`;
        } else if (['doc', 'docx'].includes(ext)) {
            preview = `<img src="/build/icons/app/doc.svg" alt="DOC icon" style="width:${iconSize}; height:${iconSize};" title="${file.name}">`;
        } else if (['xls', 'xlsx'].includes(ext)) {
            preview = `<img src="/build/icons/app/xls.svg" alt="XLS icon" style="width:${iconSize}; height:${iconSize};" title="${file.name}">`;
        } else if (['zip', 'rar'].includes(ext)) {
            preview = `<img src="/build/icons/app/zip.svg" alt="ZIP icon" style="width:${iconSize}; height:${iconSize};" title="${file.name}">`;
        } else if (['ppt', 'pptx'].includes(ext)) {
            preview = `<img src="/build/icons/app/ppt.svg" alt="ZIP icon" style="width:${iconSize}; height:${iconSize};" title="${file.name}">`;
        } else {
            preview = `<img src="/build/icons/app/other.svg" alt="File icon" style="width:${iconSize}; height:${iconSize};" title="${file.name}">`;
        }

        return `
        <div style="display: flex; align-items: center; gap: 10px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
            ${preview}
            <div>
                <div style="font-weight: bold;">${file.name}</div>
                <div style="font-size: 12px; color: #666;">Click to insert</div>
            </div>
        </div>
    `;
    }

    function openLibraryModal(onSelect) {
        fetch('/post/file-library')
            .then(res => res.json())
            .then(files => {
                const list = document.getElementById('fileLibraryList');
                list.innerHTML = '';

                files.forEach(file => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const div = document.createElement('div');
                    div.style.margin = '10px 0';
                    div.style.cursor = 'pointer';
                    div.style.border = '1px solid #ccc';
                    div.style.padding = '10px';
                    div.style.display = 'flex';
                    div.style.alignItems = 'center';
                    div.style.gap = '10px';
                    div.innerHTML = generateFilePreviewHTML(file);
                    /*
                                        let preview = '';
                                        const iconSize = '32px';
                    
                                        if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'].includes(ext)) {
                                            preview = `<img src="${file.url}" alt="${file.name}" style="height:60px;max-width:100px;">`;
                                        } else if (ext === 'pdf') {
                                            preview = `<img src="/icons/pdf-icon.png" style="width:${iconSize}" alt="PDF">`;
                                        } else if (['doc', 'docx'].includes(ext)) {
                                            preview = `<img src="/icons/doc-icon.png" style="width:${iconSize}" alt="DOC">`;
                                        } else if (['xls', 'xlsx'].includes(ext)) {
                                            preview = `<img src="/icons/xls-icon.png" style="width:${iconSize}" alt="XLS">`;
                                        } else if (['zip', 'rar'].includes(ext)) {
                                            preview = `<img src="/icons/zip-icon.png" style="width:${iconSize}" alt="ZIP">`;
                                        } else {
                                            preview = `<img src="/icons/file-icon.png" style="width:${iconSize}" alt="FILE">`;
                                        }
                    
                    
                                        div.innerHTML = `
                                        ${preview}
                                        <div>
                                            <div>${file.name}</div>
                                            <div style="font-size: 12px; color: #666;">Click to insert</div>
                                        </div>
                                    `;
                    */
                    div.onclick = () => {
                        onSelect(file);
                        closeLibraryModal();
                    };

                    list.appendChild(div);
                });

                document.getElementById('fileLibraryModal').style.display = 'block';
            });
    }


    function closeLibraryModal() {
        document.getElementById('fileLibraryModal').style.display = 'none';
    }


    $.extend(true, $.trumbowyg, {
        langs: {
            en: {
                insertLibraryFile: 'Insert from Library'
            }
        },
        plugins: {
            insertLibraryFile: {
                init: function (trumbowyg) {
                    trumbowyg.addBtnDef('insertLibraryFile', {
                        fn: function () {
                            
                            savedRange = trumbowyg.range;
                            
                            openLibraryModal((file) => {
                               
                                trumbowyg.range = savedRange;
                                trumbowyg.selection = savedRange;
                                trumbowyg.restoreRange();
                               
                                const extension = file.name.split('.').pop().toLowerCase();
                                const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                                const isImage = imageExtensions.includes(extension);

                                if (isImage) {
                                    trumbowyg.execCmd('insertImage', file.url, false, true);
                                } else {
                                    trumbowyg.execCmd('insertHTML', `<a href="${file.url}" target="_blank">${file.name}</a>`);
                                }
                            });
                        },
                        ico: 'insertImage'
                    });
                }
            }
        }
    });

    let savedRange = null;

    $('#ys_editor').trumbowyg({
        btns: [
            ['viewHTML'],
            ['undo', 'redo'],
            ['formatting'],
            ['strong', 'em', 'del'],
            ['superscript', 'subscript'],
            ['link'],
            ['insertImage'],
            ['upload', 'insertLibraryFile'], // 👈 include it here
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ],
        autogrow: true,
        removeformatPasted: true,
        plugins: {
            upload: {

                serverPath: '/post/upload',
                fileFieldName: 'file',
                urlPropertyName: 'url',
                method: 'POST',
                accept: '*/*',  // Allow any file type (not just images)
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }
        }
    });

    $('#ys_editor').on('tbwopenupload', function () {
        $('.trumbowyg-upload input[type=file]').attr('accept', '*/*');
    });

    $('#ys_editor').on('tbwuploadsuccess', function (e, data) {
        // Send file info to Laravel via AJAX
        //  console.log('Editor tbwuploadsuccess :', data);
        fetch('/post/processUpload', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ rawData: 1 })
        })
            .then(response => response.json())
            .then(result => {
                if (!result || !result.realfilename) {
                    alert('Upload succeeded, but the server didn\'t return a URL.');
                    return;
                }
                const fileUrl = "/protected/" + result.realfilename;
                const extension = result.extention;
                const fileName = result.filedownloadname;
                const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                //    alert('Upload succeeded,  ' + fileUrl + ', ' + fileName + ',' + extension);
                try {
                    if (imageExtensions.includes(extension)) {
                        $('#ys_editor').trumbowyg('execCmd', {
                            cmd: 'insertImage',
                            param: fileUrl,
                            forceCss: false
                        });
                    } else {
                        $('#ys_editor').trumbowyg('execCmd', {
                            cmd: 'insertHTML',
                            param: `<a href="${fileUrl}" target="_blank" rel="noopener">${fileName}</a>`
                        });
                    }
                } catch (error) {
                    console.error('Insertion error:', error);
                    alert('File inserted but editor might not reflect changes');
                }

                $('.trumbowyg-upload').trigger('tbwclose');
            })
            .catch(error => {
                console.error('Post-upload processing failed:', error);
                alert('Upload succeeded but post-processing failed.');
                $('.trumbowyg-upload').trigger('tbwclose');
            });
    });

    $('#ys_editor').on('tbwuploaderror', function (e, data) {
        //   console.error('Upload error:', data);
        alert(`Upload failed: ${data.message || 'Unknown error'}`);
        $('.trumbowyg-upload').trigger('tbwclose');
    });
}

// Automatically initialize editor on DOM ready
$(document).ready(() => {
    if (typeof window.shouldLoadEditor === 'undefined' || window.shouldLoadEditor) {
        initEditor();

        console.log('Editor initialized');
    }
});
