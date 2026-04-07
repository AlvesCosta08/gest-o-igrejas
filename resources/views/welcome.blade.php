<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestão de Filiados - Assembleia de Deus Templo Central II, Maranguape/CE">
    <title>{{ config('app.name', 'ADTC2 System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /*
         * PSICOLOGIA DAS CORES APLICADA:
         * - Azul escuro (navy): autoridade, confiança, estabilidade — ideal para sistemas institucionais
         * - Dourado/âmbar suave: espiritualidade, prestígio, calor humano — alinha com contexto religioso
         * - Branco/cinza claro: clareza, limpeza, respiro visual — reduz carga cognitiva
         * - Evitado: roxo saturado (excitação/entretenimento), vermelho (alerta), verde (comércio)
         *
         * UX PRINCÍPIOS:
         * - Lei de Hick: poucas opções de ação (1 CTA principal)
         * - Hierarquia visual clara: título → subtítulo → CTA
         * - Contraste mínimo 4.5:1 (WCAG AA)
         * - Espaçamento generoso reduz carga cognitiva
         * - Motion reduzida e proposital (não decorativa)
         */

        :root {
            --navy-950: #0a0f1e;
            --navy-900: #0d1526;
            --navy-800: #111e35;
            --navy-700: #172442;
            --navy-600: #1e3160;
            --navy-400: #3d5a8a;
            --navy-300: #5b7aad;
            --gold-400:  #c9a84c;
            --gold-300:  #dfc078;
            --gold-200:  #f0d9a0;
            --gray-100:  #f4f5f7;
            --gray-200:  #e8eaed;
            --gray-400:  #9aa3b2;
            --gray-600:  #6b7585;
            --white:     #ffffff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--navy-950);
            color: var(--white);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ── SKELETON ── */
        #loader {
            position: fixed; inset: 0; z-index: 9999;
            background: var(--navy-950);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 16px;
            transition: opacity .4s ease;
        }
        #loader.out { opacity: 0; pointer-events: none; }
        .loader-ring {
            width: 48px; height: 48px;
            border: 3px solid rgba(201,168,76,.15);
            border-top-color: var(--gold-400);
            border-radius: 50%;
            animation: spin .9s linear infinite;
        }
        .loader-text { font-size: 13px; color: var(--gray-400); letter-spacing: .08em; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── NAVBAR ── */
        #navbar {
            position: fixed; top: 0; width: 100%; z-index: 100;
            padding: 20px 0;
            transition: background .3s ease, padding .3s ease, box-shadow .3s ease;
        }
        #navbar.scrolled {
            background: rgba(10,15,30,.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 12px 0;
            box-shadow: 0 1px 0 rgba(255,255,255,.06);
        }
        .nav-inner {
            max-width: 1200px; margin: 0 auto;
            padding: 0 24px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .logo-mark {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--gold-400), var(--gold-200));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .logo-mark svg { width: 20px; height: 20px; color: var(--navy-900); }
        .logo-text-main {
            font-size: 16px; font-weight: 700;
            color: var(--white); letter-spacing: -.01em;
        }
        .logo-text-sub {
            font-size: 10px; font-weight: 500;
            color: var(--gray-400); letter-spacing: .12em;
            text-transform: uppercase; margin-top: 1px;
        }
        .nav-links { display: flex; align-items: center; gap: 32px; }
        .nav-links a {
            font-size: 14px; font-weight: 500;
            color: var(--gray-400); text-decoration: none;
            transition: color .2s;
        }
        .nav-links a:hover { color: var(--white); }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px;
            background: var(--gold-400);
            color: var(--navy-900);
            font-size: 14px; font-weight: 700;
            border-radius: 8px; text-decoration: none;
            transition: background .2s, transform .15s, box-shadow .2s;
            letter-spacing: .01em;
        }
        .btn-primary:hover {
            background: var(--gold-300);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(201,168,76,.3);
        }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px;
            background: transparent;
            border: 1px solid rgba(255,255,255,.15);
            color: var(--gray-200);
            font-size: 14px; font-weight: 600;
            border-radius: 8px; text-decoration: none;
            transition: border-color .2s, color .2s, background .2s;
        }
        .btn-secondary:hover {
            border-color: rgba(255,255,255,.35);
            background: rgba(255,255,255,.05);
            color: var(--white);
        }
        @media (max-width: 768px) {
            .nav-links { display: none; }
        }

        /* ── HERO ── */
        #home {
            min-height: 100vh;
            display: flex; align-items: center;
            padding: 120px 24px 80px;
            position: relative; overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 60% 50% at 70% 50%, rgba(30,49,96,.5) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 20% 80%, rgba(201,168,76,.06) 0%, transparent 60%);
        }
        /* Grade sutil de fundo */
        .hero-grid {
            position: absolute; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size: 64px 64px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
        }
        .hero-inner {
            max-width: 1200px; margin: 0 auto; width: 100%;
            position: relative; z-index: 1;
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 80px; align-items: center;
        }
        @media (max-width: 900px) {
            .hero-inner { grid-template-columns: 1fr; gap: 48px; text-align: center; }
            .hero-cta { justify-content: center; }
        }
        .hero-tag {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 14px;
            border: 1px solid rgba(201,168,76,.3);
            background: rgba(201,168,76,.07);
            border-radius: 100px;
            font-size: 12px; font-weight: 600;
            color: var(--gold-300); letter-spacing: .06em;
            text-transform: uppercase; margin-bottom: 24px;
        }
        .hero-tag svg { width: 14px; height: 14px; }
        h1 {
            font-size: clamp(36px, 5vw, 60px);
            font-weight: 800; line-height: 1.1;
            letter-spacing: -.02em; margin-bottom: 20px;
        }
        h1 span { color: var(--gold-400); display: block; }
        .hero-desc {
            font-size: 17px; color: var(--gray-400);
            line-height: 1.7; margin-bottom: 36px;
            max-width: 480px;
        }
        .hero-cta { display: flex; gap: 12px; flex-wrap: wrap; }

        /* Painel de preview direito */
        .hero-panel {
            background: var(--navy-800);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 16px;
            overflow: hidden;
        }
        .panel-header {
            padding: 14px 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: 8px;
        }
        .panel-dot {
            width: 10px; height: 10px; border-radius: 50%;
        }
        .panel-title {
            font-size: 13px; color: var(--gray-600);
            margin-left: 4px; font-weight: 500;
        }
        .panel-body { padding: 20px; display: flex; flex-direction: column; gap: 10px; }
        .panel-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px;
            background: var(--navy-900);
            border: 1px solid rgba(255,255,255,.05);
            border-radius: 8px;
            transition: border-color .2s;
        }
        .panel-row:hover { border-color: rgba(201,168,76,.2); }
        .panel-row-left { display: flex; align-items: center; gap: 12px; }
        .panel-icon {
            width: 34px; height: 34px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .panel-icon svg { width: 16px; height: 16px; }
        .panel-label { font-size: 13px; font-weight: 600; color: var(--gray-200); }
        .panel-sub { font-size: 11px; color: var(--gray-600); margin-top: 1px; }
        .panel-badge {
            font-size: 11px; font-weight: 700;
            padding: 3px 10px; border-radius: 100px;
        }
        .panel-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-stat { text-align: center; }
        .panel-stat-num { font-size: 20px; font-weight: 800; color: var(--gold-400); }
        .panel-stat-lbl { font-size: 11px; color: var(--gray-600); margin-top: 2px; font-weight: 500; }
        .panel-divider { width: 1px; height: 32px; background: rgba(255,255,255,.06); }

        /* ── SEÇÃO SOBRE ── */
        #sobre {
            padding: 100px 24px;
            border-top: 1px solid rgba(255,255,255,.05);
        }
        .section-inner { max-width: 1200px; margin: 0 auto; }
        .section-tag {
            font-size: 11px; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: var(--gold-400);
            margin-bottom: 12px;
        }
        h2 {
            font-size: clamp(28px, 3.5vw, 42px);
            font-weight: 800; letter-spacing: -.02em;
            line-height: 1.15; margin-bottom: 16px;
        }
        .section-desc { font-size: 16px; color: var(--gray-400); max-width: 520px; line-height: 1.7; }
        .sobre-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 64px; align-items: center; margin-top: 56px;
        }
        @media (max-width: 800px) { .sobre-grid { grid-template-columns: 1fr; gap: 40px; } }
        .sobre-text p { font-size: 15px; color: var(--gray-400); line-height: 1.8; margin-bottom: 16px; }
        .check-list { list-style: none; display: flex; flex-direction: column; gap: 12px; margin-top: 24px; }
        .check-list li {
            display: flex; align-items: center; gap: 10px;
            font-size: 14px; color: var(--gray-200); font-weight: 500;
        }
        .check-icon {
            width: 20px; height: 20px; border-radius: 50%;
            background: rgba(201,168,76,.12);
            border: 1px solid rgba(201,168,76,.25);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .check-icon svg { width: 10px; height: 10px; color: var(--gold-400); }
        .quote-card {
            background: var(--navy-800);
            border: 1px solid rgba(255,255,255,.07);
            border-left: 3px solid var(--gold-400);
            border-radius: 12px; padding: 32px;
        }
        .quote-mark { font-size: 48px; line-height: 1; color: var(--gold-400); opacity: .4; font-family: Georgia, serif; }
        .quote-text { font-size: 16px; color: var(--gray-200); line-height: 1.7; margin: 12px 0 24px; font-style: italic; }
        .quote-author { display: flex; align-items: center; gap: 12px; }
        .quote-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--navy-600), var(--navy-400));
            display: flex; align-items: center; justify-content: center;
        }
        .quote-avatar svg { width: 18px; height: 18px; color: var(--gold-300); }
        .quote-name { font-size: 13px; font-weight: 700; color: var(--white); }
        .quote-role { font-size: 12px; color: var(--gray-600); margin-top: 1px; }

        /* ── RECURSOS ── */
        #recursos {
            padding: 100px 24px;
            background: var(--navy-900);
            border-top: 1px solid rgba(255,255,255,.05);
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .resources-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 20px; margin-top: 56px;
        }
        @media (max-width: 900px) { .resources-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .resources-grid { grid-template-columns: 1fr; } }
        .resource-card {
            background: var(--navy-800);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 12px; padding: 28px;
            transition: border-color .25s, transform .25s;
        }
        .resource-card:hover {
            border-color: rgba(201,168,76,.2);
            transform: translateY(-3px);
        }
        .resource-icon {
            width: 44px; height: 44px; border-radius: 10px;
            background: rgba(201,168,76,.1);
            border: 1px solid rgba(201,168,76,.2);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
        }
        .resource-icon svg { width: 20px; height: 20px; color: var(--gold-400); }
        .resource-title { font-size: 15px; font-weight: 700; color: var(--white); margin-bottom: 8px; }
        .resource-desc { font-size: 13px; color: var(--gray-600); line-height: 1.7; }

        /* ── CTA FINAL ── */
        #cta { padding: 100px 24px; }
        .cta-box {
            max-width: 680px; margin: 0 auto; text-align: center;
        }
        .cta-box h2 { margin-bottom: 16px; }
        .cta-box p { font-size: 16px; color: var(--gray-400); margin-bottom: 8px; }
        .cta-box p.small { font-size: 13px; color: var(--gray-600); margin-bottom: 36px; }
        .cta-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .cta-lock {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; color: var(--gray-600);
            margin-top: 20px;
        }
        .cta-lock svg { width: 13px; height: 13px; }

        /* ── FOOTER ── */
        footer {
            padding: 32px 24px;
            border-top: 1px solid rgba(255,255,255,.05);
            text-align: center;
        }
        footer p { font-size: 13px; color: var(--gray-600); }
        footer p + p { margin-top: 4px; font-size: 12px; }

        /* ── ANIMAÇÕES DE ENTRADA ── */
        .reveal {
            opacity: 0; transform: translateY(20px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: .1s; }
        .reveal-delay-2 { transition-delay: .2s; }
        .reveal-delay-3 { transition-delay: .3s; }
        .reveal-delay-4 { transition-delay: .4s; }
        .reveal-delay-5 { transition-delay: .5s; }
    </style>
</head>
<body>

    <!-- Loader -->
    <div id="loader">
        <div class="loader-ring"></div>
        <p class="loader-text">ADTC2 System</p>
    </div>

    <!-- Navbar -->
    <nav id="navbar">
        <div class="nav-inner">
            <a href="#home" class="nav-logo">
                <div class="logo-mark">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <div class="logo-text-main">ADTC2 System</div>
                    <div class="logo-text-sub">Templo Central II</div>
                </div>
            </a>

            <div class="nav-links">
                <a href="#home">Início</a>
                <a href="#sobre">Sobre</a>
                <a href="#recursos">Recursos</a>
            </div>

            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary">
                    Entrar
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endauth
        </div>
    </nav>

    <!-- Hero -->
    <section id="home">
        <div class="hero-bg"></div>
        <div class="hero-grid"></div>
        <div class="hero-inner">
            <!-- Esquerda -->
            <div>
                <div class="hero-tag reveal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Gestão Eclesiástica Segura
                </div>

                <h1 class="reveal reveal-delay-1">
                    Organize sua<br>congregação com
                    <span>confiança</span>
                </h1>

                <p class="hero-desc reveal reveal-delay-2">
                    Plataforma institucional para gestão de membros, obreiros e documentação
                    da Assembleia de Deus — Templo Central II, Maranguape/CE.
                </p>

                <div class="hero-cta reveal reveal-delay-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary">
                            Ir para o Dashboard
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary">
                            Acessar o Sistema
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="#sobre" class="btn-secondary">Saiba mais</a>
                    @endauth
                </div>
            </div>

            <!-- Direita: painel preview -->
            <div class="reveal reveal-delay-2">
                <div class="hero-panel">
                    <div class="panel-header">
                        <div class="panel-dot" style="background:#ff5f57"></div>
                        <div class="panel-dot" style="background:#febc2e"></div>
                        <div class="panel-dot" style="background:#28c840"></div>
                        <span class="panel-title">Painel de Membros</span>
                    </div>
                    <div class="panel-body">
                        <div class="panel-row">
                            <div class="panel-row-left">
                                <div class="panel-icon" style="background:rgba(59,130,246,.1)">
                                    <svg fill="none" stroke="#60a5fa" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="panel-label">João da Silva</div>
                                    <div class="panel-sub">Presbítero · Congregação Central</div>
                                </div>
                            </div>
                            <span class="panel-badge" style="background:rgba(34,197,94,.1);color:#4ade80">Ativo</span>
                        </div>
                        <div class="panel-row">
                            <div class="panel-row-left">
                                <div class="panel-icon" style="background:rgba(168,85,247,.1)">
                                    <svg fill="none" stroke="#c084fc" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="panel-label">Maria Oliveira</div>
                                    <div class="panel-sub">Diácona · Congregação Norte</div>
                                </div>
                            </div>
                            <span class="panel-badge" style="background:rgba(34,197,94,.1);color:#4ade80">Ativo</span>
                        </div>
                        <div class="panel-row">
                            <div class="panel-row-left">
                                <div class="panel-icon" style="background:rgba(201,168,76,.1)">
                                    <svg fill="none" stroke="#c9a84c" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="panel-label">Carlos Santos</div>
                                    <div class="panel-sub">Membro · Congregação Sul</div>
                                </div>
                            </div>
                            <span class="panel-badge" style="background:rgba(234,179,8,.1);color:#facc15">Transferido</span>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="panel-stat">
                            <div class="panel-stat-num">248</div>
                            <div class="panel-stat-lbl">Membros</div>
                        </div>
                        <div class="panel-divider"></div>
                        <div class="panel-stat">
                            <div class="panel-stat-num">5</div>
                            <div class="panel-stat-lbl">Congregações</div>
                        </div>
                        <div class="panel-divider"></div>
                        <div class="panel-stat">
                            <div class="panel-stat-num">97%</div>
                            <div class="panel-stat-lbl">Ativos</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre -->
    <section id="sobre">
        <div class="section-inner">
            <div class="reveal">
                <div class="section-tag">Sobre o sistema</div>
                <h2>Feito para a realidade<br>da sua igreja</h2>
                <p class="section-desc">
                    Um sistema simples, seguro e direto ao ponto — desenvolvido especificamente
                    para as necessidades administrativas da AD Templo Central II.
                </p>
            </div>

            <div class="sobre-grid">
                <div class="sobre-text reveal reveal-delay-1">
                    <p>
                        O ADTC2 System centraliza o cadastro de filiados, o controle de congregações
                        e a gestão de usuários em um único lugar, com acesso restrito e controlado
                        pelo secretário ou administrador responsável.
                    </p>
                    <p>
                        Cada congregação tem seu próprio espaço, e os dados são protegidos por
                        níveis de acesso — o usuário vê apenas o que precisa ver.
                    </p>
                    <ul class="check-list">
                        <li>
                            <div class="check-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            Acesso restrito — apenas usuários autorizados
                        </li>
                        <li>
                            <div class="check-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            Dados separados por congregação
                        </li>
                        <li>
                            <div class="check-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            Funciona em qualquer dispositivo
                        </li>
                        <li>
                            <div class="check-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            Documentos e cartas armazenados com segurança
                        </li>
                    </ul>
                </div>

                <div class="reveal reveal-delay-2">
                    <div class="quote-card">
                        <div class="quote-mark">"</div>
                        <p class="quote-text">
                            Com o ADTC2 System, nossa secretaria ficou muito mais organizada.
                            Encontramos qualquer ficha em segundos, sem precisar vasculhar papéis.
                        </p>
                        <div class="quote-author">
                            <div class="quote-avatar">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="quote-name">Pastor Presidente</div>
                                <div class="quote-role">Assembleia de Deus — Templo Central II</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recursos -->
    <section id="recursos">
        <div class="section-inner">
            <div class="reveal">
                <div class="section-tag">Funcionalidades</div>
                <h2>Tudo que a secretaria<br>precisa, em um lugar</h2>
            </div>

            <div class="resources-grid">
                @foreach([
                    ['path' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                     'title' => 'Cadastro de Filiados',
                     'desc'  => 'Ficha completa com dados pessoais, endereço, documentos, datas e função na igreja.'],
                    ['path' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                     'title' => 'Gestão de Congregações',
                     'desc'  => 'Cadastre e gerencie todas as congregações com seus respectivos membros e usuários.'],
                    ['path' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                     'title' => 'Controle de Acesso',
                     'desc'  => 'Dois níveis: administrador vê tudo, usuário comum vê apenas sua congregação.'],
                    ['path' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                     'title' => 'Dashboard com Estatísticas',
                     'desc'  => 'Visão geral dos membros ativos, inativos e transferidos com gráficos por congregação.'],
                    ['path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                     'title' => 'Documentos Digitais',
                     'desc'  => 'Armazene fotos, documentos de identificação e cartas de forma segura na nuvem.'],
                    ['path' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                     'title' => 'Acesso de Qualquer Lugar',
                     'desc'  => 'Funciona no computador, tablet e celular. Sem instalar nada, direto pelo navegador.'],
                ] as $i => $r)
                <div class="resource-card reveal" style="transition-delay: {{ $i * 0.08 }}s">
                    <div class="resource-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $r['path'] }}"/>
                        </svg>
                    </div>
                    <div class="resource-title">{{ $r['title'] }}</div>
                    <p class="resource-desc">{{ $r['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section id="cta">
        <div class="cta-box reveal">
            <div class="section-tag" style="text-align:center">Acesso ao sistema</div>
            <h2>Sistema de uso<br>exclusivo da AD TC II</h2>
            <p>Para acessar, utilize as credenciais fornecidas pelo administrador.</p>
            <p class="small">Não possui acesso? Entre em contato com o secretário da sua congregação.</p>

            <div class="cta-actions">
                <a href="{{ route('login') }}" class="btn-primary">
                    Acessar o Sistema
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                <a href="#recursos" class="btn-secondary">Ver recursos</a>
            </div>

            <div class="cta-lock">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Registro fechado — novos usuários são criados pelo administrador
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>© {{ date('Y') }} ADTC2 System. Todos os direitos reservados.</p>
        <p>Assembleia de Deus — Templo Central II · Maranguape/CE</p>
    </footer>

    <script>
        // Loader
        window.addEventListener('load', () => {
            setTimeout(() => {
                const l = document.getElementById('loader');
                l.classList.add('out');
                setTimeout(() => l.remove(), 400);
            }, 600);
        });

        // Navbar scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 40);
        });

        // Reveal on scroll
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => observer.observe(el));

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                const t = document.querySelector(a.getAttribute('href'));
                if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>
</html>