/**
 * OM Trip and Travels — Main JS
 */
(function () {
    'use strict';

    // ---- Back to Top ----
    const backTop = document.getElementById('back-to-top');
    if (backTop) {
        window.addEventListener('scroll', () => {
            backTop.style.display = window.scrollY > 400 ? 'flex' : 'none';
        });
        backTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    // ---- Hero Slider (Bootstrap Carousel already handles via data attrs) ----

    // ---- Vehicle Gallery Thumbnails ----
    const mainImg = document.getElementById('vehicle-main-img');
    const thumbs  = document.querySelectorAll('.vehicle-gallery-thumbs img');
    if (mainImg && thumbs.length) {
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                mainImg.src = thumb.dataset.full || thumb.src;
                thumbs.forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            });
        });
    }

    // ---- CSRF Token helper ----
    function getCsrf() {
        const field = document.querySelector('input[name="_csrf"]');
        return field ? field.value : '';
    }

    // ---- Click Logger (Call / WhatsApp) ----
    function logClick(type, vehicleId, packageId, page) {
        const data = { type, page: page || window.location.href };
        if (vehicleId) data.vehicle_id = vehicleId;
        if (packageId) data.package_id = packageId;
        fetch('/api/click-log', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
            },
            body: JSON.stringify(data),
            keepalive: true,
        }).catch(() => {});
    }

    document.querySelectorAll('[data-log-call]').forEach(el => {
        el.addEventListener('click', () => {
            logClick('call', el.dataset.vehicleId, el.dataset.packageId);
        });
    });

    document.querySelectorAll('[data-log-whatsapp]').forEach(el => {
        el.addEventListener('click', () => {
            logClick('whatsapp', el.dataset.vehicleId, el.dataset.packageId);
        });
    });

    // ---- WhatsApp Message Builder ----
    window.openWhatsapp = function (number, template, vars) {
        let msg = template;
        for (const [k, v] of Object.entries(vars || {})) {
            msg = msg.replace(new RegExp('{' + k + '}', 'g'), v);
        }
        window.open('https://wa.me/' + number + '?text=' + encodeURIComponent(msg), '_blank');
    };

    // ---- Form AJAX submission (generic enquiry form) ----
    const enquiryForms = document.querySelectorAll('.ajax-form');
    enquiryForms.forEach(form => {
        form.addEventListener('submit', async e => {
            e.preventDefault();
            const btn = form.querySelector('[type="submit"]');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending…';

            const formData = new FormData(form);
            try {
                const res  = await fetch(form.action, { method: 'POST', body: formData });
                const json = await res.json();
                const alert = form.querySelector('.form-result');
                if (alert) {
                    alert.className = 'form-result alert ' + (json.success ? 'alert-success' : 'alert-danger');
                    alert.textContent = json.message;
                    alert.style.display = 'block';
                }
                if (json.success) form.reset();
            } catch (err) {
                console.error(err);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    });

    // ---- Quick Quote Card & Modal Handler ----
    const quickQuoteForm = document.querySelector('.ajax-quote-form');
    if (quickQuoteForm) {
        quickQuoteForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const pickup = quickQuoteForm.querySelector('[name="pickup_location"]')?.value || 'Indore';
            const drop = quickQuoteForm.querySelector('[name="drop_location"]')?.value || 'Ujjain / Omkareshwar';
            const date = quickQuoteForm.querySelector('[name="travel_date"]')?.value || '';
            const passengers = quickQuoteForm.querySelector('[name="passengers"]')?.value || '7-12';

            const modalEl = document.getElementById('quickQuoteModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const routeEl = document.getElementById('modal-route-preview');
                const dateEl = document.getElementById('modal-date-preview');
                const paxEl = document.getElementById('modal-passengers-preview');
                if (routeEl) routeEl.textContent = pickup + ' → ' + drop;
                if (dateEl)  dateEl.textContent = date;
                if (paxEl)   paxEl.textContent = passengers + ' Passengers';

                const mPickup = document.getElementById('modal-pickup');
                const mDrop   = document.getElementById('modal-drop');
                const mDate   = document.getElementById('modal-date');
                const mPax    = document.getElementById('modal-passengers');
                if (mPickup) mPickup.value = pickup;
                if (mDrop)   mDrop.value   = drop;
                if (mDate)   mDate.value   = date;
                if (mPax)    mPax.value    = passengers;

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            } else {
                window.location.href = '/booking?pickup=' + encodeURIComponent(pickup) + '&drop=' + encodeURIComponent(drop) + '&date=' + encodeURIComponent(date) + '&pax=' + encodeURIComponent(passengers);
            }
        });
    }

    const modalQuoteForm = document.getElementById('quickQuoteContactForm');
    if (modalQuoteForm) {
        modalQuoteForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('btn-modal-quote-submit');
            const alertBox = document.getElementById('modal-quote-alert');
            const originalBtnHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting...';

            const formData = new FormData(modalQuoteForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const res = await fetch('/api/enquiry', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const json = await res.json();
                if (alertBox) {
                    alertBox.classList.remove('d-none', 'alert-danger', 'alert-success');
                    if (json.success) {
                        alertBox.classList.add('alert-success');
                        alertBox.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Thank you! Your request has been received. Our team will contact you in 15 minutes.';
                        setTimeout(() => {
                            const waText = encodeURIComponent(`Hello OM Trip and Travels, I just requested a quote for ${data.pickup_location || 'Indore'} to ${data.drop_location} on ${data.travel_date} for ${data.passengers} passengers. Name: ${data.name}`);
                            window.open(`https://wa.me/919826012345?text=${waText}`, '_blank');
                        }, 1200);
                    } else {
                        alertBox.classList.add('alert-danger');
                        alertBox.textContent = json.message || 'Please check your information and try again.';
                        btn.disabled = false;
                        btn.innerHTML = originalBtnHtml;
                    }
                }
            } catch (err) {
                if (alertBox) {
                    alertBox.classList.remove('d-none', 'alert-success');
                    alertBox.classList.add('alert-danger');
                    alertBox.textContent = 'Enquiry submitted. Call us directly at +91 98260 12345.';
                }
                btn.disabled = false;
                btn.innerHTML = originalBtnHtml;
            }
        });
    }

    // ---- Auto-hide flash alerts ----
    document.querySelectorAll('.auto-dismiss').forEach(el => {
        setTimeout(() => {
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 5000);
        el.style.transition = 'opacity .4s';
    });

    // ---- Gallery lightbox (simple) ----
    const galleryItems = document.querySelectorAll('.gallery-item');
    if (galleryItems.length) {
        const overlay = document.createElement('div');
        overlay.id = 'lightbox';
        overlay.style.cssText = `
            display:none;position:fixed;inset:0;z-index:9999;
            background:rgba(0,0,0,.92);cursor:zoom-out;
            align-items:center;justify-content:center;
        `;
        const img = document.createElement('img');
        img.style.cssText = 'max-width:92vw;max-height:92vh;border-radius:8px;';
        overlay.appendChild(img);
        document.body.appendChild(overlay);

        galleryItems.forEach(item => {
            item.addEventListener('click', () => {
                const src = item.querySelector('img')?.src;
                if (!src) return;
                img.src = src;
                overlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });
        overlay.addEventListener('click', () => {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        });
    }

    // ---- Lazy load images ----
    if ('IntersectionObserver' in window) {
        const lazyImgs = document.querySelectorAll('img[data-src]');
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                obs.unobserve(img);
            });
        }, { rootMargin: '200px' });
        lazyImgs.forEach(img => observer.observe(img));
    }

    // ---- Smooth scroll for anchor links ----
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        const target = document.querySelector(a.getAttribute('href'));
        if (!target) return;
        a.addEventListener('click', e => {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

})();

