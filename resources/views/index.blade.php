@extends('layouts.app')

@section('content')
    {{-- ============ HERO ============ --}}
    <section id="home" class="hero">
        <div class="liquid-reveal" data-after="{{ config('portfolio.hero.afterSrc') }}">
            <img data-before src="{{ config('portfolio.hero.beforeSrc') }}" alt="{{ config('portfolio.brand') }} showcase">
            <canvas aria-hidden="true"></canvas>
        </div>
        <div class="hero-vignette"></div>

        <div class="hero-watermark" aria-hidden="true"></div>

        <div class="hero-grid shell">
            <div class="hero-col-left">
                <p class="eyebrow hero-reveal reveal" style="transition-delay:200ms">{{ config('portfolio.hero.eyebrow') }}</p>

                <h1 class="hero-h1">
                    <span class="reveal-line"><span style="transition-delay:250ms">Hi, I'm</span></span>
                    <span class="reveal-line"><span style="transition-delay:370ms">{{ config('portfolio.name') }}</span></span>
                </h1>

                <p class="hero-role hero-reveal reveal" style="transition-delay:500ms">
                    <span class="typed-role" id="typed-role" data-typing="{{ json_encode(config('portfolio.roles')) }}">{{ config('portfolio.role') }}</span>
                </p>

                <p class="hero-bio hero-reveal reveal" style="transition-delay:580ms">{{ config('portfolio.tagline') }}</p>

                <div class="cta-row hero-reveal reveal" style="transition-delay:660ms">
                    <a class="cv-shimmer cv-shimmer--accent cv-shimmer--noswap hero-reveal reveal" style="transition-delay:660ms" href="#portfolio" aria-label="Explore Work">
                        <span class="cv-shimmer-txt" aria-hidden="true">
                            <span class="cv-shimmer-1">@foreach (str_split('Explore Work') as $ci => $ch)<span class="cv-letter" style="--d:{{ $ci * 0.04 }}s">{{ $ch === ' ' ? "\u{00A0}" : $ch }}</span>@endforeach</span>
                        </span>
                        <span class="cv-shimmer-trail" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false"><use href="#icon-arrow-right"/></svg>
                        </span>
                    </a>
                    <a class="cv-shimmer cv-shimmer--ink hero-reveal reveal" style="transition-delay:740ms" href="{{ config('portfolio.resume_url') }}" download aria-label="Download CV">
                        <span class="cv-shimmer-txt" aria-hidden="true">
                            <span class="cv-shimmer-1">@foreach (str_split('Download CV') as $ci => $ch)<span class="cv-letter" style="--d:{{ $ci * 0.04 }}s">{{ $ch === ' ' ? "\u{00A0}" : $ch }}</span>@endforeach</span>
                            <span class="cv-shimmer-2">@foreach (str_split('Downloading...') as $ci => $ch)<span class="cv-letter" style="--d:{{ $ci * 0.04 }}s">{{ $ch === ' ' ? "\u{00A0}" : $ch }}</span>@endforeach</span>
                        </span>
                        <span class="cv-shimmer-emoji" aria-hidden="true">📥</span>
                    </a>
                </div>

                <div class="hero-socials hero-reveal reveal" style="transition-delay:740ms">
                    <span class="hero-socials-label">Follow me</span>
                    <ul class="hero-socials-list">
                        @foreach (['instagram', 'github', 'linkedin'] as $key)
                            @if (config("portfolio.socials.$key"))
                                <li>
                                    <a class="social-btn" href="{{ config("portfolio.socials.$key") }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($key) }}" title="{{ ucfirst($key) }}">
                                        <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-{{ $key }}"/></svg>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="hero-col-right">
                <div class="hero-card hero-reveal reveal reveal-card" style="transition-delay:400ms" data-player data-tracks="{{ json_encode(config('portfolio.music')) }}">
                    <div class="hero-card-row">
                        <div class="card-tile" data-player-tile aria-hidden="true">
                            <svg class="player-tile-icon" style="width:1em;height:1em"><use href="#icon-music"/></svg>
                            <img class="player-tile-img" data-player-img alt="" hidden>
                        </div>
                        <div class="card-panel">
                            <div>
                                <p class="card-caption">Now playing</p>
                                <div class="card-slot">
                                    @foreach (config('portfolio.music') as $i => $track)
                                        <span class="card-title {{ $i === 0 ? 'is-active' : '' }}">{{ $track['title'] }}</span>
                                    @endforeach
                                </div>
                                <p class="player-artist" data-player-artist></p>
                                <div class="player-progress">
                                    <div class="player-time">
                                        <span data-player-current>0:00</span>
                                        <span data-player-duration>0:00</span>
                                    </div>
                                    <div class="player-track"><div class="player-fill" data-player-fill></div></div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="card-dots" role="tablist" aria-label="Tracks">
                                    @foreach (config('portfolio.music') as $i => $track)
                                        <button class="card-dot {{ $i === 0 ? 'is-active' : '' }}" type="button" aria-label="Track {{ $i + 1 }}"></button>
                                    @endforeach
                                </div>
                                <div class="card-nav">
                                    <button type="button" data-prev aria-label="Previous">
                                        <svg style="width:1em;height:1em;transform:rotate(180deg)" aria-hidden="true"><use href="#icon-arrow-right"/></svg>
                                    </button>
                                    <button type="button" class="card-play" data-toggle-play aria-label="Play">
                                        <svg class="icon-play" style="width:1em;height:1em" aria-hidden="true"><use href="#icon-play"/></svg>
                                        <svg class="icon-pause" style="width:1em;height:1em" aria-hidden="true"><use href="#icon-pause"/></svg>
                                    </button>
                                    <button type="button" data-next aria-label="Next">
                                        <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-arrow-right"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <audio data-player-audio preload="none"></audio>
                </div>

                <div class="hero-visual hero-reveal reveal reveal-card" style="transition-delay:550ms">
                    <div class="hero-visual-rings" aria-hidden="true">
                        <span class="hero-visual-ring hero-visual-ring--outer"></span>
                        <span class="hero-visual-ring hero-visual-ring--inner"></span>
                        <span class="hero-visual-badge-pill">
                            <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-spark"/></svg>
                            {{ config('portfolio.role') }}
                        </span>
                    </div>

                    @foreach (config('portfolio.hero.badges') as $index => $badge)
                        <div class="badge-float badge-float--{{ $index }}" style="animation-delay:{{ $index * -1.4 }}s">
                            <span class="badge-float-icon">
                                <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-{{ $badge['icon'] }}"/></svg>
                            </span>
                            <span class="badge-float-text">
                                <strong>{{ $badge['value'] }}</strong>
                                <span>{{ $badge['label'] }}</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="hero-status shell hero-reveal reveal" style="transition-delay:900ms">
            <span>Building since 2020</span>
            <span class="hero-status-center">Full-Stack &amp; Backend Focused</span>
            <span class="hero-status-right"><span>Scroll to explore</span> ↓</span>
        </div>
    </section>

    {{-- ============ ABOUT ============ --}}
    <section id="about" class="about-section">
        <div class="shell">
            {{-- Bento grid --}}
            <div class="bento">
                {{-- Row 1: bio + photo --}}
                <div class="bento-card bento-card--bio">
                    <div class="bento-bio-inner">
                        <p class="eyebrow reveal">Hello, I'm {{ config('portfolio.brand') }}</p>
                        <h2 class="bento-h2 reveal reveal-up">
                            <span data-split-words>HAI, SAYA {{ strtoupper(config('portfolio.name')) }}.</span>
                        </h2>
                        <p class="bento-text reveal reveal-up" style="transition-delay:120ms">{{ config('portfolio.about_intro') }}</p>
                        <a class="cv-shimmer reveal reveal-up" style="transition-delay:220ms" href="{{ config('portfolio.resume_url') }}" download aria-label="Unduh CV Lengkap">
                            <svg class="cv-shimmer-svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><use href="#icon-download"/></svg>
                            <span class="cv-shimmer-txt" aria-hidden="true">
                                <span class="cv-shimmer-1">@foreach (str_split('Unduh CV Lengkap') as $ci => $ch)<span class="cv-letter" style="--d:{{ $ci * 0.04 }}s">{{ $ch === ' ' ? "\u{00A0}" : $ch }}</span>@endforeach</span>
                                <span class="cv-shimmer-2">@foreach (str_split('Mengunduh...') as $ci => $ch)<span class="cv-letter" style="--d:{{ $ci * 0.04 }}s">{{ $ch === ' ' ? "\u{00A0}" : $ch }}</span>@endforeach</span>
                            </span>
                        </a>
                    </div>
                </div>

                <div class="bento-card bento-card--photo reveal reveal-up">
                    <div class="profile-card">
                        <div class="profile-photo">
                            @if (config('portfolio.photo'))
                                <img src="{{ config('portfolio.photo') }}" alt="{{ config('portfolio.name') }}">
                            @else
                                <div class="profile-photo-inner">
                                    <span class="profile-ring profile-ring--outer"></span>
                                    <span class="profile-ring profile-ring--inner"></span>
                                    <span class="profile-initials">SP</span>
                                </div>
                            @endif
                            <div class="profile-floatbar">
                                <span class="profile-user">
                                    <span class="profile-handle">{{ config('portfolio.handle') }}</span>
                                    <span class="profile-online"><i></i> Online</span>
                                </span>
                                <button class="btn-float" type="button" data-modal-open>Rekrut Saya</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 2: education + tech stack --}}
                <div class="bento-card bento-card--edu reveal reveal-up">
                    <div class="edu-top">
                        <span class="edu-icon">
                            <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-graduation"/></svg>
                        </span>
                        <span class="chip chip--dark">{{ strtoupper(config('portfolio.education.badge')) }}</span>
                    </div>
                    <div class="edu-body">
                        <p class="edu-school"><span aria-hidden="true">🏛</span> {{ strtoupper(config('portfolio.education.school')) }}</p>
                        <h3 class="edu-major">{{ strtoupper(config('portfolio.education.major')) }}</h3>
                    </div>
                    <div class="edu-courses">
                        <p class="edu-courses-label">Mata Kuliah Relevan:</p>
                        <ul class="chip-list">
                            @foreach (config('portfolio.education.courses') as $course)
                                <li class="chip chip--soft">{{ $course }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="gpa-row">
                        <span class="gpa-value">{{ config('portfolio.education.gpa') }}</span>
                        <span class="gpa-meta">
                            <span class="gpa-label">{{ strtoupper(config('portfolio.education.gpa_label')) }}</span>
                            <span class="gpa-scale">/ 4.00 IPK</span>
                        </span>
                    </div>
                </div>

                <div class="bento-card bento-card--stack reveal reveal-up">
                    <div class="stack-head">
                        <span class="stack-icon">
                            <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-terminal"/></svg>
                        </span>
                        <h3 class="bento-list-title">Tech Stack</h3>
                    </div>
                    @foreach (config('portfolio.tech_stack') as $group => $items)
                        <div class="stack-group">
                            <p class="stack-group-label">{{ $group }}</p>
                            <ul class="chip-list">
                                @foreach ($items as $item)
                                    <li class="chip chip--soft">{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                <div class="bento-card bento-card--focus reveal reveal-up">
                    <div class="focus-body">
                        <h3 class="focus-title">{{ strtoupper(config('portfolio.focus.title')) }}</h3>
                        <p class="focus-text">{{ config('portfolio.focus.description') }}</p>
                    </div>
                    <ul class="focus-tags">
                        @foreach (config('portfolio.focus.tags') as $tag)
                            <li>
                                <span class="focus-tag">
                                    <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-{{ $tag['icon'] }}"/></svg>
                                    {{ $tag['label'] }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ PORTFOLIO / WORKS ============ --}}
    <section id="portfolio" class="works-section">
    {{-- Ambient warm glow (decorative, sits behind content) --}}
    <div class="works-glow-layer" aria-hidden="true">
        <span class="works-glow works-glow--amber"></span>
        <span class="works-glow works-glow--ember"></span>
    </div>
        <div class="shell" data-tabs>
            <div class="works-bento reveal reveal-up">
            <div class="works-head">
                <span class="works-eyebrow pill reveal"><span class="dot"></span> Portfolio</span>
                <h2 class="works-h2">
                    <span class="reveal-line"><span style="transition-delay:120ms">Selected Work</span></span>
                </h2>
            </div>

            <div class="tabs reveal" role="tablist" aria-label="Portfolio sections" style="transition-delay:100ms">
                <button class="tab-btn is-active" type="button" data-tab="projects" role="tab" aria-selected="true">Projects</button>
                <button class="tab-btn" type="button" data-tab="stack" role="tab" aria-selected="false">Tech Stack</button>
                <button class="tab-btn" type="button" data-tab="certificates" role="tab" aria-selected="false">Certificates</button>
            </div>

            {{-- Projects --}}
            <div class="works-panel is-active" data-panel="projects" role="tabpanel" data-projects>
                @if ($projects->isNotEmpty())
                    <div class="proj-grid">
                        @foreach ($projects as $index => $project)
                            @php
                                $projectUrl = $project->link ?? $project->github ?? '#';
                                $projectYear = $project->year ?? $project->created_at->year;
                                $number = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                                $palettes = [
                                    ['--art-a:#3f3f46', '--art-b:#18181b'],
                                    ['--art-a:#52525b', '--art-b:#1c1c20'],
                                    ['--art-a:#44403c', '--art-b:#17171a'],
                                ];
                                $art = $palettes[$index % 3];
                            @endphp
                            <article class="proj-card reveal reveal-up"
                                     style="transition-delay:{{ $index * 80 }}ms; {{ implode(';', $art) }}"
                                     data-title="{{ $project->title }}"
                                     data-category-label="{{ $project->category }}"
                                     data-number="{{ $number }}"
                                     data-desc="{{ $project->description }}"
                                     data-tech='{{ json_encode($project->tech_stack ?? []) }}'
                                     data-highlights='{{ json_encode($project->highlights ?? []) }}'
                                     data-challenge="{{ $project->challenge }}"
                                     data-url="{{ $projectUrl }}"
                                     data-github="{{ $project->github ?? '' }}">
                                <button type="button" class="proj-media" data-proj-open aria-haspopup="dialog" aria-label="Lihat studi kasus {{ $project->title }}">
                                    <span class="proj-art" aria-hidden="true">
                                        <span class="proj-skeleton" aria-hidden="true"></span>
                                    </span>
                                    <span class="proj-chrome" aria-hidden="true"><i></i><i></i><i></i></span>
                                    <span class="proj-cat">{{ $project->category }}</span>
                                    <span class="proj-idx" aria-hidden="true">/{{ $number }}</span>
                                    <span class="proj-view" aria-hidden="true">
                                        <span class="proj-view-label">
                                            <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-folder"/></svg>
                                            <span>Lihat Studi Kasus</span>
                                        </span>
                                    </span>
                                </button>

                                <div class="proj-body">
                                    <h3 class="proj-title">{{ $project->title }}</h3>
                                    <p class="proj-desc">{{ $project->description }}</p>
                                    @if (!empty($project->tech_stack))
                                        <ul class="proj-tags">
                                            @foreach ($project->tech_stack as $tech)
                                                <li class="proj-tag">{{ $tech }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>

                                <div class="proj-foot">
                                    <button type="button" class="proj-more" data-proj-open>
                                        Detail & Fitur Utama
                                        <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-arrow-up-right"/></svg>
                                    </button>
                                    @if (!empty($project->github))
                                        <a class="proj-ghost-link" href="{{ $project->github }}" target="_blank" rel="noopener" aria-label="GitHub repository">
                                            <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-github"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Project case study modal --}}
                    <div class="proj-modal" id="proj-modal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="proj-modal-title">
                        <div class="proj-modal-backdrop" data-proj-close></div>
                        <div class="proj-modal-panel" data-lenis-prevent>
                            <button class="proj-modal-close" type="button" data-proj-close aria-label="Tutup">
                                <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-x"/></svg>
                            </button>

                            <div class="proj-modal-media">
                                <span class="proj-art" aria-hidden="true">
                                    <span class="proj-skeleton" aria-hidden="true"></span>
                                </span>
                                <span class="proj-chrome" aria-hidden="true"><i></i><i></i><i></i></span>
                                <span class="proj-modal-idx" aria-hidden="true"></span>
                            </div>

                            <div class="proj-modal-body" data-lenis-prevent>
                                <span class="proj-modal-cat"></span>
                                <h3 class="proj-modal-title" id="proj-modal-title"></h3>
                                <p class="proj-modal-desc"></p>

                                <div class="proj-modal-tech"></div>

                                <div class="proj-modal-bloc">
                                    <h4 class="proj-modal-h4">SOROTAN &amp; FITUR UTAMA</h4>
                                    <ul class="proj-modal-features"></ul>
                                </div>

                                <div class="proj-modal-bloc proj-modal-challenge">
                                    <h4 class="proj-modal-h4">TANTANGAN KODE</h4>
                                    <p class="proj-modal-challenge-text"></p>
                                </div>

                                <div class="proj-modal-actions">
                                    <a class="proj-action proj-action--ghost" href="#" target="_blank" rel="noopener" data-project-github>💻 Kode GitHub</a>
                                    <a class="proj-action proj-action--glow" href="#" target="_blank" rel="noopener" data-project-url>Demo Langsung <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-arrow-up-right"/></svg></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="modal-note">Projects coming soon.</p>
                @endif
            </div>

            {{-- Tech Stack --}}
            <div class="works-panel" data-panel="stack" role="tabpanel">
                @php $groupedSkills = $skills->groupBy('category'); @endphp
                @if ($skills->isNotEmpty())
                    <div class="stack-groups">
                        @foreach (App\Models\Skill::CATEGORIES as $category)
                            @if ($groupedSkills->has($category))
                                <div class="stack-group reveal" style="transition-delay:80ms">
                                    <h3 class="stack-group-title">{{ $category }}</h3>
                                    <div class="stack-grid">
                                        @foreach ($groupedSkills->get($category) as $skill)
                                            <span class="stack-badge">
                                                <span class="bar" aria-hidden="true"></span>
                                                {{ $skill->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="modal-note">Tech stack coming soon.</p>
                @endif
            </div>

            {{-- Certificates --}}
            <div class="works-panel" data-panel="certificates" role="tabpanel">
                @if ($certificates->isNotEmpty())
                    <div class="cert-grid">
                        @foreach ($certificates as $index => $certificate)
                            <a class="cert-card reveal reveal-up" href="{{ $certificate->credential_url ?: '#' }}" target="_blank" rel="noopener" style="transition-delay:{{ $index * 80 }}ms">
                                <span class="cert-cat">{{ $certificate->category }}</span>
                                <h3 class="cert-title">{{ $certificate->title }}</h3>
                                <p class="cert-issuer">{{ $certificate->issuer }}</p>
                                @if ($certificate->issued_date)
                                    <p class="cert-date">Issued {{ $certificate->issued_date->format('M Y') }}</p>
                                @endif
                                @if ($certificate->description)
                                    <p class="cert-desc">{{ $certificate->description }}</p>
                                @endif
                                <div class="cert-foot">
                                    <span class="cert-verify">
                                        <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-check"/></svg>
                                        <span>View credential</span>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="modal-note">Certificates coming soon.</p>
                @endif
            </div>
            </div>
        </div>
    </section>

    {{-- ============ CONTACT + GUESTBOOK ============ --}}
    <section id="contact" class="connect-section">
        <div class="connect-glow-layer" aria-hidden="true">
            <span class="connect-glow connect-glow--warm"></span>
        </div>

        <div class="shell">
            <div class="connect-grid">
                {{-- ---------- Left: contact form + socials ---------- --}}
                <div class="connect-col connect-col--left">
                    <header class="connect-head">
                        <span class="works-eyebrow pill reveal"><span class="dot"></span> {{ config('portfolio.contact.title') }}</span>
                        <h2 class="connect-h2 reveal reveal-up">{{ config('portfolio.contact.headline') }}</h2>
                        <p class="connect-sub reveal reveal-up" style="transition-delay:100ms">{{ config('portfolio.contact.subtitle') }}</p>
                    </header>

                    <form class="connect-card connect-form reveal reveal-up" style="transition-delay:160ms" action="{{ route('contact.send') }}" method="POST" novalidate>
                        @csrf

                        @if (session('success'))
                            <p class="connect-alert connect-alert--ok">
                                <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-check"/></svg>
                                {{ session('success') }}
                            </p>
                        @endif

                        <div class="field">
                            <label class="field-label" for="connect-name">{{ __('Name') }}</label>
                            <span class="field-wrap">
                                <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-user"/></svg>
                                <input class="field-control @error('name') is-invalid @enderror" id="connect-name" type="text" name="name" value="{{ old('name') }}" placeholder="Your name" autocomplete="name" required>
                            </span>
                            @error('name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="field">
                            <label class="field-label" for="connect-email">{{ __('Email') }}</label>
                            <span class="field-wrap">
                                <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-mail"/></svg>
                                <input class="field-control @error('email') is-invalid @enderror" id="connect-email" type="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" autocomplete="email" required>
                            </span>
                            @error('email')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="field">
                            <label class="field-label" for="connect-message">{{ __('Message') }}</label>
                            <textarea class="field-control field-control--plain @error('message') is-invalid @enderror" id="connect-message" name="message" rows="4" placeholder="Tell me about your project, timeline, and budget." required>{{ old('message') }}</textarea>
                            @error('message')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <button class="btn-send" type="submit">
                            <span>{{ __('Kirim Pesan') }}</span>
                            <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-send"/></svg>
                        </button>
                    </form>

                    <div class="connect-card connect-socials reveal reveal-up" style="transition-delay:240ms">
                        <div class="connect-card-head">
                            <h3 class="connect-card-title">
                                <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-globe"/></svg>
                                {{ config('portfolio.contact.socials_title') }}
                            </h3>
                        </div>
                        <ul class="social-grid">
                            @foreach (['linkedin' => 'LinkedIn', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'github' => 'GitHub', 'tiktok' => 'TikTok'] as $key => $label)
                                @if (config("portfolio.socials.$key"))
                                    <li>
                                        <a class="social-item" href="{{ config("portfolio.socials.$key") }}" target="_blank" rel="noopener" aria-label="{{ $label }}" title="{{ $label }}">
                                            <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-{{ $key }}"/></svg>
                                            <span>{{ $label }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- ---------- Right: live comments / guestbook ---------- --}}
                <div class="connect-col connect-col--right">
                    <div class="connect-card connect-comments reveal reveal-up">
                        <div class="connect-card-head">
                            <h3 class="connect-card-title">
                                <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-message"/></svg>
                                {{ config('portfolio.contact.comments_title') }}
                                <span class="comment-count">{{ $comments->count() }}</span>
                            </h3>
                            <p class="connect-card-note">{{ config('portfolio.contact.comments_hint') }}</p>
                        </div>

                        <form class="comment-form" action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="field">
                                <label class="field-label" for="comment-name">{{ __('Name') }}</label>
                                <span class="field-wrap">
                                    <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-user"/></svg>
                                    <input class="field-control @error('name') is-invalid @enderror" id="comment-name" type="text" name="name" value="{{ old('name') }}" placeholder="Your name" autocomplete="name" required>
                                </span>
                                @error('name')<p class="field-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="field">
                                <label class="field-label" for="comment-message">{{ __('Message') }}</label>
                                <textarea class="field-control field-control--plain @error('message') is-invalid @enderror" id="comment-message" name="message" rows="3" placeholder="Write your comment here..." required>{{ old('message') }}</textarea>
                                @error('message')<p class="field-error">{{ $message }}</p>@enderror
                            </div>

                            <label class="avatar-picker">
                                <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" data-avatar-input>
                                <span class="avatar-preview" data-avatar-preview>
                                    <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-camera"/></svg>
                                </span>
                                <span class="avatar-picker-text">
                                    <strong>{{ __('Choose Profile Photo') }}</strong>
                                    <span data-avatar-name>{{ __('JPG, PNG, or WEBP · max 2 MB') }}</span>
                                </span>
                            </label>
                            @error('avatar')<p class="field-error">{{ $message }}</p>@enderror

                            <button class="btn-send" type="submit">
                                <span>{{ __('Post Comment') }}</span>
                                <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-message"/></svg>
                            </button>
                        </form>

                        <ul class="comment-list">
                            @forelse ($comments as $comment)
                                <li class="comment-item @if ($comment->is_pinned) comment-item--pinned @endif">
                                    <span class="comment-avatar">
                                        @if ($comment->avatar_url)
                                            <img src="{{ $comment->avatar_url }}" alt="{{ $comment->name }}" loading="lazy" width="44" height="44">
                                        @else
                                            <span aria-hidden="true">{{ $comment->initials }}</span>
                                        @endif
                                    </span>
                                    <div class="comment-body">
                                        <div class="comment-meta">
                                            <span class="comment-name">{{ $comment->name }}</span>
                                            @if ($comment->is_admin)
                                                <span class="badge badge--admin">{{ __('Admin') }}</span>
                                            @endif
                                            @if ($comment->is_pinned)
                                                <span class="badge badge--pinned">
                                                    <svg style="width:1em;height:1em" aria-hidden="true"><use href="#icon-star"/></svg>
                                                    {{ __('Pinned Comment') }}
                                                </span>
                                            @endif
                                            <time class="comment-date" datetime="{{ $comment->created_at->toDateString() }}">{{ $comment->created_at->locale('id')->diffForHumans() }}</time>
                                        </div>
                                        <p class="comment-text">{{ $comment->message }}</p>
                                    </div>
                                </li>
                            @empty
                                <li class="comment-empty">{{ __('Be the first to leave a comment.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection