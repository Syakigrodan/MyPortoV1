import Lenis from 'lenis';

/* ============================================================
   Adaptive grid (scale-up above 1920px)
   ============================================================ */
function applyAdaptiveGrid() {
    const FONT_BASE = 16;
    const baseWidth = 1920;
    const coef = 0.6666;
    const w = window.innerWidth;
    const widthReduction = ((baseWidth - w) / baseWidth) * 100;
    const size = FONT_BASE - (FONT_BASE * (widthReduction * coef)) / 100;
    if (size > FONT_BASE) {
        document.documentElement.style.fontSize = size + 'px';
    } else {
        document.documentElement.style.removeProperty('font-size');
    }
}

/* ============================================================
   Lenis smooth scroll + scroll lock model
   ============================================================ */
let lenis = null;
let scrollEnabled = true;

function startScroll() {
    scrollEnabled = true;
    if (lenis) lenis.start();
    document.documentElement.classList.remove('no-scroll');
}

function stopScroll() {
    scrollEnabled = false;
    if (lenis) lenis.stop();
    document.documentElement.classList.add('no-scroll');
}

function scrollTo(id) {
    const el = document.getElementById(id);
    if (!el) return;
    const wasDisabled = !scrollEnabled;
    if (wasDisabled) startScroll();
    setTimeout(() => {
        if (lenis && lenis.options.smoothWheel) {
            lenis.scrollTo(el, { offset: 0, duration: 1.2 });
        } else {
            window.scrollTo({ top: el.getBoundingClientRect().top + window.pageYOffset, behavior: 'smooth' });
        }
        if (wasDisabled) {
            setTimeout(() => stopScroll(), 80);
        }
    }, 50);
}

/* ============================================================
   Small spring stepper (plain JS, critically-damped)
   ============================================================ */
function createSpring({ tension = 210, friction = 26, onUpdate, onRest } = {}) {
    let x = 0;
    let v = 0;
    let target = 0;
    let rafId = null;

    const tick = () => {
        const dt = 1 / 60;
        const accel = tension * (target - x) - friction * v;
        v += accel * dt;
        x += v * dt;
        onUpdate(x);
        if (Math.abs(target - x) < 0.001 && Math.abs(v) < 0.001) {
            x = target;
            onUpdate(x);
            if (onRest) onRest();
            rafId = null;
            return;
        }
        rafId = requestAnimationFrame(tick);
    };

    return {
        set(newTarget) {
            target = newTarget;
            if (rafId === null) rafId = requestAnimationFrame(tick);
        },
        stop() {
            if (rafId !== null) cancelAnimationFrame(rafId);
            rafId = null;
        },
    };
}

/* ============================================================
   Live clock
   ============================================================ */
function initClock() {
    const timeEl = document.getElementById('clock-time');
    const dateEl = document.getElementById('clock-date');
    if (!timeEl || !dateEl) return;

    const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];

    const tick = () => {
        const d = new Date();
        let hours = d.getHours();
        const meridiem = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12 || 12;
        const minutes = String(d.getMinutes()).padStart(2, '0');
        timeEl.textContent = `${hours}:${minutes}${meridiem}`;
        dateEl.textContent = `${d.getDate()} ${MONTHS[d.getMonth()]}, ${d.getFullYear()}`;
        document.querySelectorAll('[data-clock-live]').forEach((el) => {
            el.textContent = `${hours}:${minutes}${meridiem}`;
        });
    };

    tick();
    setInterval(tick, 1000);
}

/* ============================================================
   Typing role animation
   ============================================================ */
function initTyping() {
    const el = document.getElementById('typed-role');
    if (!el) return;

    let words = [];
    try {
        words = JSON.parse(el.getAttribute('data-typing') || '[]');
    } catch (err) {
        words = [];
    }
    if (!words.length) return;

    let wordIndex = 0;
    let charIndex = 0;
    let deleting = false;
    const current = () => words[wordIndex];
    el.textContent = '';

    const typeInterval = () => {
        const word = current();
        if (deleting) {
            charIndex -= 1;
        } else {
            charIndex += 1;
        }

        el.textContent = word.slice(0, charIndex);

        let delay = deleting ? 45 : 100;
        if (!deleting && charIndex === word.length) {
            delay = 1600;
            deleting = true;
        } else if (deleting && charIndex === 0) {
            deleting = false;
            wordIndex = (wordIndex + 1) % words.length;
            delay = 350;
        }

        setTimeout(typeInterval, delay);
    };

    setTimeout(typeInterval, 1200);
}

/* ============================================================
   Reveal system (IntersectionObserver)
   ============================================================ */
let revealObserver = null;

function initReveals() {
    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.reveal, .reveal-line, .reveal-word').forEach((el) => el.classList.add('is-in'));
        return;
    }

    revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-in');
                    revealObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15 }
    );

    document.querySelectorAll('.reveal, .reveal-line, .reveal-word').forEach((el) => {
        if (el.closest('.hero, #home, .site-header')) return;
        revealObserver.observe(el);
    });
}

/* Split a heading into stagger-revealed words */
function splitWords(el) {
    const text = el.textContent.trim();
    el.textContent = '';
    const words = text.split(/\s+/);
    words.forEach((word, i) => {
        const s = document.createElement('span');
        s.className = 'reveal-word';
        s.style.transitionDelay = `${i * 35}ms`;
        s.textContent = word;
        el.appendChild(s);
        if (i < words.length - 1) el.appendChild(document.createTextNode(' '));
    });
}

/* ============================================================
   PageLoader
   ============================================================ */
function initLoader() {
    const loader = document.getElementById('page-loader');
    if (!loader) return;

    const counter = document.getElementById('loader-counter');
    const fill = document.getElementById('loader-fill');
    const center = document.getElementById('loader-center');
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    stopScroll();

    const complete = () => {
        const finish = () => {
            document.body.classList.add('is-ready');
            startScroll();
            setTimeout(() => loader.remove(), 400);
            playHeroReveals();
        };

        if (reduced) {
            loader.style.transition = 'none';
            loader.style.transform = 'translateY(-100%)';
            finish();
            return;
        }

        loader.style.transition = 'transform 0.7s cubic-bezier(0.22,1,0.36,1), border-radius 0.7s ease';
        center.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        center.style.opacity = '0';
        center.style.transform = 'translateY(-12px)';
        loader.style.transform = 'translateY(-100%)';
        loader.classList.add('is-hidden');

        setTimeout(finish, 700);
    };

    if (reduced) {
        if (counter) counter.textContent = '100';
        if (fill) fill.style.width = '100%';
        setTimeout(complete, 10);
        return;
    }

    const FILL_MS = 1300;
    const easeInOutCubic = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);
    const start = performance.now();

    const step = (now) => {
        const t = Math.min((now - start) / FILL_MS, 1);
        const progress = Math.round(easeInOutCubic(t) * 100);
        if (counter) counter.textContent = String(progress).padStart(3, '0');
        if (fill) fill.style.width = `${progress}%`;
        if (t < 1) {
            requestAnimationFrame(step);
        } else {
            complete();
        }
    };

    requestAnimationFrame(step);
}

/* ============================================================
   Hero reveals (gated on loader ready)
   ============================================================ */
function playHeroReveals() {
    document.querySelectorAll('.hero-reveal').forEach((el) => {
        el.classList.add('is-in');
    });
}

/* ============================================================
   LiquidReveal canvas (hero before/after brush)
   ============================================================ */
function initLiquidReveal(container) {
    if (!container) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const baseImg = container.querySelector('img[data-before]');
    const afterSrc = container.getAttribute('data-after');
    const canvas = container.querySelector('canvas');
    const ctx = canvas.getContext('2d');

    const brushRadius = 143;
    const decay = 0.016;
    const dpr = Math.min(window.devicePixelRatio || 1, 2);

    let width = 0;
    let height = 0;
    let radius = 0;
    let diameter = 0;
    let cover = document.createElement('canvas');
    let coverCtx = cover.getContext('2d');
    let brush = document.createElement('canvas');
    let brushCtx = brush.getContext('2d');
    let points = [];
    let last = null;
    let idle = 0;
    let afterImage = new Image();

    const resize = () => {
        const rect = container.getBoundingClientRect();
        width = Math.round(rect.width);
        height = Math.round(rect.height);
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        cover.width = canvas.width;
        cover.height = canvas.height;
        coverCtx = cover.getContext('2d');
        drawCover();

        radius = brushRadius * dpr;
        diameter = Math.ceil(radius * 2);
        brush.width = diameter;
        brush.height = diameter;
        brushCtx = brush.getContext('2d');
        ctx.clearRect(0, 0, width, height);
        points = [];
        last = null;
    };

    const drawCover = () => {
        if (!afterImage.complete || !afterImage.naturalWidth) return;
        const iw = afterImage.naturalWidth;
        const ih = afterImage.naturalHeight;
        const scale = Math.max(canvas.width / iw, canvas.height / ih);
        const w = iw * scale;
        const h = ih * scale;
        coverCtx.drawImage(afterImage, (canvas.width - w) / 2, (canvas.height - h) / 2, w, h);
    };

    const stamp = (x, y) => {
        const c = x - radius;
        const cy = y - radius;
        brushCtx.clearRect(0, 0, diameter, diameter);
        const grad = brushCtx.createRadialGradient(radius, radius, 0, radius, radius, radius);
        grad.addColorStop(0, 'rgba(255,255,255,1)');
        grad.addColorStop(0.55, 'rgba(255,255,255,0.82)');
        grad.addColorStop(1, 'rgba(255,255,255,0)');
        brushCtx.globalCompositeOperation = 'source-over';
        brushCtx.fillStyle = grad;
        brushCtx.fillRect(0, 0, diameter, diameter);
        brushCtx.globalCompositeOperation = 'source-in';
        brushCtx.drawImage(cover, c, cy, diameter, diameter, 0, 0, diameter, diameter);
        ctx.globalCompositeOperation = 'source-over';
        ctx.drawImage(brush, c, cy);
    };

    const frame = () => {
        const drawing = points.length > 0;
        if (drawing) {
            idle = 0;
        } else {
            idle += 1;
        }

        const fade = drawing ? decay : Math.min(decay + idle * 0.004, 0.5);
        ctx.globalCompositeOperation = 'destination-out';
        ctx.fillStyle = `rgba(0,0,0,${fade})`;
        ctx.fillRect(0, 0, width, height);

        if (drawing) {
            points.forEach(([px, py]) => stamp(px, py));
            points = [];
        }

        if (idle > 120) {
            ctx.clearRect(0, 0, width, height);
            idle = 0;
        }

        requestAnimationFrame(frame);
    };

    const onPointer = (e) => {
        const rect = canvas.getBoundingClientRect();
        const x = (e.clientX - rect.left) * dpr;
        const y = (e.clientY - rect.top) * dpr;

        if (x < -radius || x > width * dpr + radius || y < -radius || y > height * dpr + radius) {
            last = null;
            return;
        }

        if (!last) {
            last = [x, y];
            points.push([x, y]);
            return;
        }

        const [lx, ly] = last;
        const dist = Math.hypot(x - lx, y - ly);
        const step = Math.max(radius * 0.3, 1);
        const n = Math.min(Math.ceil(dist / step), 60);
        for (let i = 1; i <= n; i += 1) {
            points.push([lx + ((x - lx) * i) / n, ly + ((y - ly) * i) / n]);
        }
        last = [x, y];
    };

    afterImage.onload = () => {
        if (cover.width) drawCover();
    };
    afterImage.src = afterSrc;

    window.addEventListener('pointermove', onPointer, { passive: true });

    if ('ResizeObserver' in window) {
        new ResizeObserver(resize).observe(container);
    }
    resize();

    requestAnimationFrame(frame);
}

/* ============================================================
   Hero music player
   ============================================================ */
function formatTime(seconds) {
    if (!isFinite(seconds) || isNaN(seconds) || seconds < 0) return '0:00';
    const m = Math.floor(seconds / 60);
    const s = Math.floor(seconds % 60);
    return `${m}:${String(s).padStart(2, '0')}`;
}

function initPlayer() {
    document.querySelectorAll('[data-player]').forEach((root) => {
        let tracks = [];
        try {
            tracks = JSON.parse(root.getAttribute('data-tracks') || '[]');
        } catch (err) {
            tracks = [];
        }
        if (!tracks.length || !root.querySelector('[data-player-audio]')) return;

        const audio = root.querySelector('[data-player-audio]');
        const titles = Array.from(root.querySelectorAll('.card-slot > .card-title'));
        const dots = Array.from(root.querySelectorAll('.card-dot'));
        const prevBtn = root.querySelector('[data-prev]');
        const nextBtn = root.querySelector('[data-next]');
        const playBtn = root.querySelector('[data-toggle-play]');
        const artistEl = root.querySelector('[data-player-artist]');
        const captionEl = root.querySelector('.card-caption');
        const fillEl = root.querySelector('[data-player-fill]');
        const currentEl = root.querySelector('[data-player-current]');
        const durationEl = root.querySelector('[data-player-duration]');
        const row = root.querySelector('.hero-card-row');
        const tileIcon = root.querySelector('.player-tile-icon');
        const tileImg = root.querySelector('[data-player-img]');

        let index = 0;
        let broken = false;

        const render = (from, to) => {
            titles.forEach((el, i) => {
                el.classList.remove('is-active', 'is-in', 'is-out');
                if (i === to) el.classList.add('is-in');
                if (i === from) el.classList.add('is-out');
            });
            dots.forEach((d, i) => d.classList.toggle('is-active', i === to));
            setTimeout(() => {
                titles.forEach((el, i) => {
                    if (i === to) {
                        el.classList.remove('is-in');
                        el.classList.add('is-active');
                    }
                });
            }, 50);
        };

        const setMeta = () => {
            artistEl.textContent = tracks[index].artist || '';
            durationEl.textContent = formatTime(audio.duration);
            currentEl.textContent = formatTime(audio.currentTime);
        };

        const showMeta = () => {
            captionEl.textContent = isFinite(audio.duration) ? 'Now playing' : 'Loading trackâ€¦';
            artistEl.textContent = tracks[index].artist || '';
            if (isFinite(audio.duration)) durationEl.textContent = formatTime(audio.duration);
        };

        const showBroken = () => {
            broken = true;
            playBtn.classList.remove('is-playing');
            captionEl.textContent = 'Audio file not found';
            artistEl.textContent = 'Add it to public/audio';
            durationEl.textContent = '0:00';
            currentEl.textContent = '0:00';
            fillEl.style.width = '0%';
        };

        const setTile = () => {
            if (!tileImg || !tileIcon) return;
            const image = tracks[index].image;
            const hasImage = Boolean(image);
            tileImg.hidden = !hasImage;
            tileIcon.style.display = hasImage ? 'none' : '';
            if (hasImage) tileImg.src = image;
        };

        const go = (to, autoplay) => {
            to = ((to % tracks.length) + tracks.length) % tracks.length;
            if (to === index && audio.src) {
                if (autoplay) playNow();
                return;
            }
            const from = index;
            index = to;
            render(from, index);
            setTile();
            broken = false;
            audio.src = tracks[index].file;
            showMeta();
            if (autoplay) playNow();
        };

        const playNow = () => {
            if (broken) return;
            const p = audio.play();
            if (p) p.catch(() => {});
        };

        if (playBtn) {
            playBtn.addEventListener('click', () => {
                if (!audio.src) audio.src = tracks[index].file;
                if (audio.paused) playNow();
                else audio.pause();
            });
        }
        if (prevBtn) prevBtn.addEventListener('click', () => go(index - 1, !audio.paused));
        if (nextBtn) nextBtn.addEventListener('click', () => go(index + 1, !audio.paused));
        dots.forEach((d, i) => d.addEventListener('click', () => go(i, !audio.paused)));

        if (row) {
            let startX = null;
            row.addEventListener('pointerdown', (e) => {
                if (e.target.closest('button')) return;
                startX = e.clientX;
            });
            row.addEventListener('pointerup', (e) => {
                if (startX === null) return;
                const dx = e.clientX - startX;
                startX = null;
                if (Math.abs(dx) < 40) return;
                go(index + (dx < 0 ? 1 : -1), !audio.paused);
            });
        }

        audio.addEventListener('playing', () => {
            broken = false;
            playBtn.classList.add('is-playing');
            captionEl.textContent = 'Now playing';
            setMeta();
        });
        audio.addEventListener('pause', () => playBtn.classList.remove('is-playing'));
        audio.addEventListener('loadedmetadata', () => {
            durationEl.textContent = formatTime(audio.duration);
        });
        audio.addEventListener('timeupdate', () => {
            if (!isFinite(audio.duration)) return;
            currentEl.textContent = formatTime(audio.currentTime);
            fillEl.style.width = `${(audio.currentTime / audio.duration) * 100}%`;
        });
        audio.addEventListener('ended', () => go(index + 1, true));
        audio.addEventListener('error', () => showBroken());

        setTile();
    });
}

/* ============================================================
   Works tabs
   ============================================================ */
function initTabs() {
    document.querySelectorAll('[data-tabs]').forEach((root) => {
        const btns = root.querySelectorAll('.tab-btn');
        const panels = root.querySelectorAll('.works-panel');
        btns.forEach((btn) => {
            btn.addEventListener('click', () => {
                btns.forEach((b) => b.classList.remove('is-active'));
                btn.classList.add('is-active');
                const target = btn.getAttribute('data-tab');
                panels.forEach((p) => {
                    p.classList.toggle('is-active', p.getAttribute('data-panel') === target);
                });
            });
        });
    });
}

/* ============================================================
   Project bento: case-study modal
   ============================================================ */
function initProjectCards() {
    const root = document.querySelector('[data-projects]');
    if (!root) return;

    /* modal */
    const modal = document.getElementById('proj-modal');
    if (!modal) return;
    const panel = modal.querySelector('.proj-modal-panel');
    const cache = { cat: null, number: null, title: null, desc: null, tech: [], features: [], challenge: null, url: null, github: null };

    const escapeHtml = (str) =>
        String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');

    const fill = (card) => {
        cache.cat = escapeHtml(card.dataset.categoryLabel);
        cache.number = card.dataset.number || '';
        cache.title = escapeHtml(card.dataset.title);
        cache.desc = escapeHtml(card.dataset.desc);
        cache.challenge = escapeHtml(card.dataset.challenge);
        cache.url = card.dataset.url || '#';
        cache.github = card.dataset.github || '';
        try { cache.tech = JSON.parse(card.dataset.tech || '[]'); } catch (e) { cache.tech = []; }
        try { cache.features = JSON.parse(card.dataset.highlights || '[]'); } catch (e) { cache.features = []; }

        modal.querySelector('.proj-modal-cat').textContent = cache.cat;
        modal.querySelector('.proj-modal-idx').textContent = cache.number ? `/${cache.number}` : '';
        modal.querySelector('.proj-modal-title').textContent = cache.title;
        modal.querySelector('.proj-modal-desc').textContent = cache.desc;
        modal.querySelector('.proj-modal-challenge-text').textContent = cache.challenge;

        const techBox = modal.querySelector('.proj-modal-tech');
        techBox.innerHTML = cache.tech
            .map((t) => `<span class="proj-tag">${escapeHtml(t)}</span>`)
            .join('');

        const featureBox = modal.querySelector('.proj-modal-features');
        featureBox.innerHTML = cache.features
            .map((f) => `<li><svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-check"/></svg><span>${escapeHtml(f)}</span></li>`)
            .join('');

        const ghLink = modal.querySelector('[data-project-github]');
        ghLink.setAttribute('href', cache.github || '#');
        ghLink.style.pointerEvents = cache.github ? '' : 'none';
        ghLink.style.opacity = cache.github ? '' : '0.45';
        modal.querySelector('[data-project-url]').setAttribute('href', cache.url);
    };

    const open = (card) => {
        fill(card);
        const body = modal.querySelector('.proj-modal-body');
        if (body) body.scrollTop = 0;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        stopScroll();
    };
    const close = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        startScroll();
    };

    root.querySelectorAll('[data-proj-open]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const card = btn.closest('.proj-card');
            if (card) open(card);
        });
    });
    modal.querySelectorAll('[data-proj-close]').forEach((btn) => {
        btn.addEventListener('click', close);
    });
    modal.addEventListener('click', (e) => {
        if (e.target === modal || e.target.classList.contains('proj-modal-backdrop')) close();
    });
    panel.addEventListener('click', (e) => e.stopPropagation());
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) close();
    });
}

/* ============================================================
   NavMenu overlay
   ============================================================ */
function initNavMenu() {
    const menu = document.getElementById('nav-menu-overlay');
    if (!menu) return;

    const openMenu = () => {
        menu.classList.add('is-open');
        stopScroll();
        const items = menu.querySelectorAll('.navmenu-item');
        items.forEach((el, i) => {
            el.style.transitionDelay = `${i * 45 + 80}ms`;
        });
    };

    const closeMenu = () => {
        menu.classList.remove('is-open');
        startScroll();
    };

    document.querySelectorAll('[data-menu-open]').forEach((btn) => {
        btn.addEventListener('click', openMenu);
    });
    document.querySelectorAll('[data-menu-close]').forEach((btn) => {
        btn.addEventListener('click', closeMenu);
    });

    menu.querySelectorAll('.navmenu-item').forEach((item) => {
        item.addEventListener('click', () => {
            closeMenu();
            const target = item.getAttribute('data-scroll');
            if (target) {
                setTimeout(() => scrollTo(target), 80);
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && menu.classList.contains('is-open')) closeMenu();
    });
}

/* ============================================================
   Request Modal
   ============================================================ */
let requestModal = null;

function openRequestModal() {
    if (!requestModal) return;
    requestModal.classList.add('is-open');
    stopScroll();
}

function closeRequestModal() {
    if (!requestModal) return;
    requestModal.classList.remove('is-open');
    startScroll();
}

function initRequestModal() {
    const modal = document.getElementById('request-modal');
    if (!modal) return;
    requestModal = modal;

    const panel = modal.querySelector('.modal-panel');
    const closeBtn = modal.querySelector('[data-modal-close]');
    const form = document.getElementById('request-form');
    const formView = document.getElementById('modal-form-view');
    const successView = document.getElementById('modal-success-view');
    const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelectorAll('[data-modal-open]').forEach((btn) => {
        btn.addEventListener('click', openRequestModal);
    });

    closeBtn.addEventListener('click', closeRequestModal);
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeRequestModal();
    });
    panel.addEventListener('click', (e) => e.stopPropagation());
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeRequestModal();
            document.getElementById('nav-menu-overlay')?.classList.contains('is-open') && closeMenu();
        }
    });

    const resetForm = () => {
        if (!form) return;
        form.reset();
        form.querySelectorAll('.form-error').forEach((el) => el.remove());
        formView.style.display = '';
        successView.style.display = 'none';
    };

    if (form) {
        const submitLabel = submitBtn ? submitBtn.querySelector('[data-submit-label]') : null;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.setAttribute('aria-busy', 'true');
                if (submitLabel) submitLabel.textContent = 'Sendingâ€¦';
            }

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        name: form.name.value,
                        email: form.email.value,
                        message: form.message.value,
                    }),
                });

                if (res.ok) {
                    formView.style.display = 'none';
                    successView.style.display = 'flex';
                    return;
                }

                const data = await res.json().catch(() => ({}));
                const errors = data.errors || {};
                const labels = data.message ? [data.message] : Object.values(errors).flat();
                form.querySelectorAll('.form-error').forEach((el) => el.remove());
                labels.slice(0, 3).forEach((msg) => {
                    const p = document.createElement('p');
                    p.className = 'form-error';
                    p.textContent = msg;
                    form.prepend(p);
                });
            } catch (err) {
                formView.style.display = 'none';
                successView.style.display = 'flex';
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.removeAttribute('aria-busy');
                    if (submitLabel) submitLabel.textContent = 'Send request';
                }
            }
        });
    }

    const successClose = document.querySelector('[data-success-close]');
    if (successClose) {
        successClose.addEventListener('click', () => {
            closeRequestModal();
            setTimeout(resetForm, 300);
        });
    }
}

/* ============================================================
   Wait â€” share a closeMenu reference for modal escape
   ============================================================ */
function closeMenu() {
    const menu = document.getElementById('nav-menu-overlay');
    if (menu) {
        menu.classList.remove('is-open');
        startScroll();
    }
}

/* ============================================================
   Guestbook avatar picker (photo preview + filename)
   ============================================================ */
function initAvatarPickers() {
    document.querySelectorAll('[data-avatar-input]').forEach((input) => {
        const picker = input.closest('.avatar-picker');
        const preview = picker ? picker.querySelector('[data-avatar-preview]') : null;
        const label = picker ? picker.querySelector('[data-avatar-name]') : null;

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file) return;

            if (preview) {
                preview.replaceChildren();
                const img = document.createElement('img');
                img.alt = '';
                img.src = URL.createObjectURL(file);
                img.addEventListener('load', () => URL.revokeObjectURL(img.src), { once: true });
                preview.appendChild(img);
            }

            if (label) label.textContent = file.name;
        });
    });
}

/* ============================================================
   Anchor smooth scrolling + "View Work" etc.
   ============================================================ */
function initAnchors() {
    document.querySelectorAll('a[href^="#"]').forEach((a) => {
        if (a.classList.contains('skip-link')) return;
        a.addEventListener('click', (e) => {
            const id = a.getAttribute('href').slice(1);
            if (!id) return;
            if (a.hasAttribute('data-open-modal') || a.hasAttribute('data-modal-open')) {
                e.preventDefault();
                openRequestModal();
                return;
            }
            e.preventDefault();
            scrollTo(id);
        });
    });

    document.querySelectorAll('[data-scroll]').forEach((el) => {
        el.addEventListener('click', (e) => {
            if (el.tagName === 'A' && el.hasAttribute('href')) return;
            e.preventDefault();
            scrollTo(el.getAttribute('data-scroll'));
        });
    });
}


/* ============================================================
   Init
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    applyAdaptiveGrid();
    window.addEventListener('resize', applyAdaptiveGrid);

    window.scrollTo(0, 0);
    lenis = new Lenis({ smoothWheel: true });
    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    document.querySelectorAll('[data-split-words]').forEach(splitWords);

    initClock();
    initTyping();
    initReveals();
    initLoader();

    initLiquidReveal(document.querySelector('.liquid-reveal'));
    initPlayer();
    initTabs();
    initProjectCards();
    initNavMenu();
    initRequestModal();
    initAnchors();
    initAvatarPickers();
});
