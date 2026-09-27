<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('portfolio.name') . ' — ' . config('portfolio.role'))</title>
        <meta name="description" content="@yield('meta_description', config('portfolio.tagline'))">
        <meta name="theme-color" content="#0a0a0a">
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='7' fill='%230a0a0a'/%3E%3Ccircle cx='16' cy='16' r='5' fill='%23b15f2c'/%3E%3Ccircle cx='24' cy='8' r='2.5' fill='%23b15f2c'/%3E%3C/svg%3E">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Onest:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        @include('components.svg-sprite')

        <a href="#main" class="skip-link">Skip to content</a>

        {{-- ============ PageLoader ============ --}}
        <div id="page-loader" class="page-loader" role="status" aria-label="Loading">
            <div id="loader-center" class="loader-center">
                <div class="loader-logo" aria-hidden="true">
                    <span class="loader-logo-halo"></span>
                    <svg style="width:1em;height:1em"><use href="#icon-spark"/></svg>
                </div>
                <p class="loader-title">Welcome To My Portfolio Website</p>
                <p class="loader-brand">MyPortofolio.com</p>
                <div class="loader-dots" aria-hidden="true"><span></span><span></span><span></span></div>
            </div>
            <div class="loader-progress">
                <div class="loader-track"><div id="loader-fill" class="loader-fill"></div></div>
                <div class="loader-meta">
                    <span>Loading</span>
                    <span id="loader-counter" class="loader-counter">000</span>
                </div>
            </div>
        </div>

        {{-- ============ Header ============ --}}
        <header class="site-header">
            <div class="shell header-inner">
                <a href="#home" class="brand">
                    <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-spark"/></svg>
                    <span>{{ config('portfolio.brand') }}</span>
                </a>

                <nav class="nav-primary" aria-label="Primary">
                    <ul>
                        <li><a class="nav-item" href="#home" aria-current="page">Home</a></li>
                        <li><a class="nav-item" href="#about">About</a></li>
                        <li><a class="nav-item" href="#portfolio">Portfolio</a></li>
                        <li><a class="nav-item" href="#contact">Contact</a></li>
                    </ul>
                </nav>

                <div class="header-right">
                    <div class="clock-chip">
                        <span class="clock-label">Local time</span>
                        <span id="clock-time" class="clock-time">9:41am</span>
                        <span class="clock-sep">•</span>
                        <span id="clock-date" class="clock-date">12 March, 2025</span>
                    </div>
                    <button class="menu-btn" type="button" data-menu-open aria-label="Open menu">
                        <span class="menu-btn-inner">
                            <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-grid"/></svg>
                            <span class="menu-btn-label">Menu</span>
                        </span>
                    </button>
                </div>
            </div>
        </header>

        <main id="main" tabindex="-1">
            @yield('content')
        </main>

        {{-- ============ Footer ============ --}}
        <footer class="site-footer">
            <div class="shell footer-inner">
                <p class="footer-copyright">© {{ date('Y') }} {{ config('portfolio.name') }}. All rights reserved.</p>
            </div>
        </footer>

        {{-- ============ NavMenu overlay ============ --}}
        <div id="nav-menu-overlay" class="navmenu" role="dialog" aria-modal="true" aria-label="Menu">
            <div class="shell navmenu-top">
                <p class="navmenu-brand">
                    <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-spark"/></svg>
                    {{ config('portfolio.brand') }}
                </p>
                <button class="navmenu-close" type="button" data-menu-close>
                    <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-x"/></svg>
                    Close
                </button>
            </div>

            <nav class="shell navmenu-nav" aria-label="Menu">
                <ul>
                    <li><button class="navmenu-item" type="button" data-scroll="home"><span class="navmenu-index">01</span><span>Home</span></button></li>
                    <li><button class="navmenu-item" type="button" data-scroll="about"><span class="navmenu-index">02</span><span>About</span></button></li>
                    <li><button class="navmenu-item" type="button" data-scroll="portfolio"><span class="navmenu-index">03</span><span>Portfolio</span></button></li>
                    <li><button class="navmenu-item" type="button" data-scroll="contact"><span class="navmenu-index">04</span><span>Contact</span></button></li>
                </ul>
            </nav>

            <div class="shell navmenu-bottom">
                <span>Local time — <span data-clock-live></span></span>
                <button class="navmenu-project" type="button" data-menu-close data-modal-open>Start a project →</button>
            </div>
        </div>

        {{-- ============ RequestModal ============ --}}
        <div id="request-modal" class="modal-backdrop" role="dialog" aria-modal="true" aria-label="Start a project">
            <div class="modal-panel" data-lenis-prevent>
                <button class="modal-close" type="button" data-modal-close aria-label="Close">
                    <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-x"/></svg>
                </button>

                <div id="modal-form-view">
                    <div class="modal-heading">
                        <span class="modal-stage">Start a project</span>
                        <h2 class="modal-h2">Tell us what you're building.</h2>
                    </div>

                    <form id="request-form" class="modal-form" action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="form-field">
                            <label class="form-caption" for="req-name">Name</label>
                            <input class="form-control" id="req-name" type="text" name="name" placeholder="Your name" required>
                        </div>
                        <div class="form-field">
                            <label class="form-caption" for="req-email">Email</label>
                            <input class="form-control" id="req-email" type="email" name="email" placeholder="you@company.com" required>
                        </div>
                        <div class="form-field">
                            <label class="form-caption" for="req-message">Project</label>
                            <textarea class="form-control" id="req-message" name="message" rows="4" placeholder="A few words about your project, timeline, and budget." required></textarea>
                        </div>

                        <div class="modal-foot">
                            <span class="modal-note">We reply within one business day.</span>
                            <button class="pill-btn pill-btn--dark" type="submit">
                                <span class="pill-spring">
                                    <span class="pill-inner pill-inner--arrow">
                                        <span data-submit-label>Send request</span>
                                        <span class="pill-badge pill-badge--up">
                                            <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-arrow-up-right"/></svg>
                                        </span>
                                    </span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <div id="modal-success-view" class="modal-success" style="display:none">
                    <div class="success-badge">
                        <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-spark"/></svg>
                    </div>
                    <h2 class="success-title">Request received</h2>
                    <p class="success-text">Thanks for reaching out — we'll get back to you within one business day.</p>
                    <button class="pill-btn pill-btn--dark" type="button" data-success-close>
                        <span class="pill-spring">
                            <span class="pill-inner pill-inner--plain">Close</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>