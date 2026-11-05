/**
 * OM Trip and Travels — Admin Panel JS
 */
(function () {
    'use strict';

    // ---- Sidebar Toggle (mobile) ----
    const sidebar   = document.getElementById('admin-sidebar');
    const toggleBtn = document.querySelector('.sidebar-toggle');
    const backdrop  = document.querySelector('.sidebar-backdrop');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            backdrop && backdrop.classList.toggle('show');
        });
        backdrop && backdrop.addEventListener('click', () => {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
        });
    }

    // ---- Confirm Delete ----
    document.querySelectorAll('.confirm-delete').forEach(el => {
        el.addEventListener('click', e => {
            const msg = el.dataset.msg || 'Are you sure you want to delete this item? This cannot be undone.';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // ---- Auto-dismiss flash messages ----
    document.querySelectorAll('.flash-msg').forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 5000);
        el.style.transition = 'opacity .4s';
    });

    // ---- Image Upload Preview ----
    const uploadZone  = document.querySelector('.image-upload-zone');
    const fileInput   = document.querySelector('.image-file-input');
    const previewGrid = document.querySelector('.image-preview-grid');

    if (uploadZone && fileInput) {
        uploadZone.addEventListener('click', () => fileInput.click());

        uploadZone.addEventListener('dragover', e => {
            e.preventDefault();
            uploadZone.classList.add('dragover');
        });
        uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
        uploadZone.addEventListener('drop', e => {
            e.preventDefault();
            uploadZone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        fileInput.addEventListener('change', () => handleFiles(fileInput.files));
    }

    function handleFiles(files) {
        if (!previewGrid) return;
        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => {
                const item = document.createElement('div');
                item.className = 'image-preview-item';
                item.innerHTML = `
                    <img src="${e.target.result}" alt="preview">
                    <button type="button" class="remove-btn" title="Remove">✕</button>
                `;
                item.querySelector('.remove-btn').addEventListener('click', () => item.remove());
                previewGrid.appendChild(item);
            };
            reader.readAsDataURL(file);
        });
    }

    // ---- Slug auto-generation ----
    const titleInput = document.querySelector('[data-slug-source]');
    const slugInput  = document.querySelector('[data-slug-target]');

    if (titleInput && slugInput && !slugInput.value) {
        titleInput.addEventListener('input', () => {
            slugInput.value = titleInput.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .substring(0, 180);
        });
    }

    // ---- CSRF helper ----
    function getCsrf() {
        const field = document.querySelector('input[name="_csrf"]');
        return field ? field.value : '';
    }

    // ---- AJAX Status Update ----
    document.querySelectorAll('.status-select').forEach(sel => {
        sel.addEventListener('change', async function () {
            const id     = this.dataset.id;
            const status = this.value;
            const url    = `/admin/enquiries/${id}/status`;

            const form = new FormData();
            form.append('status', status);
            form.append('_csrf', getCsrf());

            try {
                const res = await fetch(url, { method: 'POST', body: form });
                const json = await res.json();
                if (!json.success) alert('Failed to update status.');
            } catch (e) {
                alert('Network error.');
            }
        });
    });

    // ---- SMTP Test Button ----
    const smtpTestBtn = document.getElementById('smtp-test-btn');
    if (smtpTestBtn) {
        smtpTestBtn.addEventListener('click', async () => {
            smtpTestBtn.disabled = true;
            smtpTestBtn.textContent = 'Testing…';
            const form = new FormData();
            form.append('_csrf', getCsrf());
            try {
                const res  = await fetch('/admin/settings/smtp-test', { method: 'POST', body: form });
                const json = await res.json();
                alert(json.message || (json.success ? 'SMTP test OK!' : 'SMTP test failed.'));
            } catch {
                alert('Network error during SMTP test.');
            } finally {
                smtpTestBtn.disabled = false;
                smtpTestBtn.textContent = 'Test SMTP';
            }
        });
    }

    // ---- TinyMCE init (if loaded) ----
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '.tinymce-editor',
            height: 400,
            menubar: false,
            plugins: ['lists', 'link', 'image', 'media', 'table', 'code', 'fullscreen', 'wordcount'],
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image | table | code fullscreen',
            content_style: 'body { font-family: Segoe UI, sans-serif; font-size: 15px; line-height: 1.65; }',
            branding: false,
        });
    }

    // ---- Sort order drag (simple up/down) ----
    // Uses data-sort-url attribute on table rows
    document.querySelectorAll('.sort-up-btn, .sort-down-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            const row       = this.closest('tr');
            const id        = row.dataset.id;
            const direction = this.classList.contains('sort-up-btn') ? 'up' : 'down';
            const url       = this.dataset.sortUrl;
            if (!url) return;
            const form = new FormData();
            form.append('id', id);
            form.append('direction', direction);
            form.append('_csrf', getCsrf());
            try {
                await fetch(url, { method: 'POST', body: form });
                window.location.reload();
            } catch { alert('Sort failed.'); }
        });
    });

    // ---- Enquiry note form submission ----
    const noteForm = document.getElementById('add-note-form');
    if (noteForm) {
        noteForm.addEventListener('submit', async e => {
            e.preventDefault();
            const form = new FormData(noteForm);
            try {
                const res  = await fetch(noteForm.action, { method: 'POST', body: form });
                const json = await res.json();
                if (json.success) {
                    const list = document.getElementById('notes-list');
                    if (list) {
                        const div = document.createElement('div');
                        div.className = 'border rounded p-3 mb-2 bg-light';
                        div.innerHTML = `<p class="mb-1">${json.note}</p>
                            <small class="text-muted">by ${json.admin} &mdash; just now</small>`;
                        list.appendChild(div);
                    }
                    noteForm.reset();
                }
            } catch { alert('Failed to add note.'); }
        });
    }

    // ---- Chart.js dashboard init ----
    const chartEl = document.getElementById('enquiry-chart');
    if (chartEl && typeof Chart !== 'undefined') {
        const labels = JSON.parse(chartEl.dataset.labels || '[]');
        const values = JSON.parse(chartEl.dataset.values || '[]');
        new Chart(chartEl, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Enquiries',
                    data: values,
                    backgroundColor: 'rgba(230,92,0,.7)',
                    borderColor: '#e65c00',
                    borderWidth: 2,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    const statusChartEl = document.getElementById('status-chart');
    if (statusChartEl && typeof Chart !== 'undefined') {
        const labels = JSON.parse(statusChartEl.dataset.labels || '[]');
        const values = JSON.parse(statusChartEl.dataset.values || '[]');
        new Chart(statusChartEl, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: ['#1d4ed8','#d97706','#7c3aed','#065f46','#6b7280','#991b1b'],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { font: { size: 11 } } } },
                cutout: '65%',
            }
        });
    }

})();

