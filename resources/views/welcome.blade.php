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
        :root {
            --bg-main: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-secondary: #475569;
            --text-light: #94a3b8;
            --border-light: #e2e8f0;
            --border-hover: #cbd5e1;
            --orange-600: #ea580c;
            --orange-500: #f97316;
            --orange-400: #fb923c;
            --orange-300: #fdba74;
            --orange-200: #fed7aa;
            --orange-100: #ffedd5;
            --navy-900: #0f172a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-main);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ── LOADER ── */
        #loader {
            position: fixed; inset: 0; z-index: 9999;
            background: var(--bg-main);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 20px;
            transition: opacity .5s ease;
        }
        #loader.out { opacity: 0; pointer-events: none; }
        .loader-logo {
            width: 80px;
            height: 80px;
            animation: pulse 2s ease-in-out infinite;
        }
        .loader-text { 
            font-size: 16px; 
            font-weight: 600;
            color: var(--orange-500); 
            letter-spacing: .1em;
            animation: fadeInOut 2s ease-in-out infinite;
        }
        @keyframes pulse { 
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        @keyframes fadeInOut { 
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }

        /* ── NAVBAR ── */
        #navbar {
            position: fixed; top: 0; width: 100%; z-index: 100;
            padding: 20px 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all .4s ease;
            border-bottom: 1px solid transparent;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        #navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 30px rgba(0,0,0,0.08);
            border-bottom: 1px solid var(--border-light);
            padding: 14px 0;
        }
        .nav-inner {
            max-width: 1280px; margin: 0 auto;
            padding: 0 32px;
            display: flex; align-items: center; justify-content: space-between;
        }
        
        .nav-logo { 
            display: flex; 
            align-items: center; 
            gap: 16px; 
            text-decoration: none; 
            padding: 8px 0;
            transition: transform .3s ease;
        }
        .nav-logo:hover {
            transform: translateX(4px);
        }

        .logo-image {
            height: 52px;
            width: auto;
            object-fit: contain;
            flex-shrink: 0;
            transition: all .3s ease;
            filter: drop-shadow(0 2px 8px rgba(249,115,22,0.15));
        }
        
        .nav-logo:hover .logo-image {
            transform: scale(1.08) rotate(-2deg);
            filter: drop-shadow(0 4px 12px rgba(249,115,22,0.25));
        }
        
        .logo-text-group {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        
        .logo-text-main {
            font-size: 22px; 
            font-weight: 800;
            background: linear-gradient(135deg, var(--orange-600), var(--orange-500));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }
        
        .logo-text-sub {
            font-size: 11px; 
            font-weight: 600;
            color: var(--text-secondary); 
            letter-spacing: .15em;
            text-transform: uppercase;
        }

        .nav-links { display: flex; align-items: center; gap: 40px; }
        .nav-links a {
            font-size: 15px; font-weight: 600;
            color: var(--text-secondary); text-decoration: none;
            transition: all .3s ease;
            position: relative;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--orange-400), var(--orange-600));
            border-radius: 3px;
            transition: width .3s ease;
        }
        .nav-links a:hover { 
            color: var(--orange-600);
        }
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .btn-primary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 12px 32px;
            background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
            color: #ffffff;
            font-size: 15px; font-weight: 700;
            border-radius: 12px; text-decoration: none;
            transition: all .3s ease;
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
            background: linear-gradient(135deg, var(--orange-600), var(--orange-500));
        }
        
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 12px 32px;
            background: transparent;
            border: 2px solid var(--border-hover);
            color: var(--text-main);
            font-size: 15px; font-weight: 600;
            border-radius: 12px; text-decoration: none;
            transition: all .3s ease;
        }
        .btn-secondary:hover {
            border-color: var(--orange-500);
            color: var(--orange-600);
            background: var(--orange-50);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .logo-text-main { font-size: 18px; }
            .logo-text-sub { font-size: 9px; }
            .logo-image { height: 44px; }
            .nav-inner { padding: 0 20px; }
        }
        
        @media (max-width: 480px) {
            .logo-text-group { display: none; }
            .logo-image { height: 40px; }
        }

        /* ── HERO ─ */
        #home {
            min-height: 100vh;
            display: flex; align-items: center;
            padding: 140px 32px 100px;
            position: relative; overflow: hidden;
            background: linear-gradient(180deg, var(--bg-main) 0%, var(--bg-secondary) 100%);
        }
        .hero-glow {
            position: absolute; top: -20%; right: -10%;
            width: 800px; height: 800px;
            background: radial-gradient(circle, rgba(249,115,22,0.08) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }
        .hero-glow-2 {
            position: absolute; bottom: -20%; left: -10%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(249,115,22,0.06) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }
        
        .hero-inner {
            max-width: 1280px; margin: 0 auto; width: 100%;
            position: relative; z-index: 1;
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 100px; align-items: center;
        }
        @media (max-width: 900px) {
            .hero-inner { grid-template-columns: 1fr; gap: 60px; text-align: center; }
            .hero-cta { justify-content: center; }
            .hero-desc { margin-left: auto; margin-right: auto; }
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 10px 24px;
            background: linear-gradient(135deg, var(--orange-100), rgba(249,115,22,0.1));
            border: 2px solid var(--orange-300);
            border-radius: 50px;
            font-size: 13px; font-weight: 700;
            color: var(--orange-600); letter-spacing: .05em;
            text-transform: uppercase; margin-bottom: 32px;
            box-shadow: 0 4px 15px rgba(249,115,22,0.15);
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .hero-badge svg { 
            width: 18px; height: 18px;
            animation: pulse 2s ease-in-out infinite;
        }
        
        h1 {
            font-size: clamp(42px, 6vw, 68px);
            font-weight: 900; line-height: 1.05;
            color: var(--text-main);
            margin-bottom: 28px;
            letter-spacing: -1px;
        }
        h1 span { 
            background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
        }
        
        .hero-desc {
            font-size: 19px; color: var(--text-secondary);
            line-height: 1.8; margin-bottom: 42px;
            max-width: 580px;
            font-weight: 400;
        }
        .hero-cta { display: flex; gap: 18px; flex-wrap: wrap; }
        
        .hero-stats {
            display: flex; gap: 40px; margin-top: 60px;
            padding-top: 40px;
            border-top: 2px solid var(--border-light);
        }
        .hero-stat-item {
            display: flex; flex-direction: column;
        }
        .hero-stat-number {
            font-size: 36px; font-weight: 900;
            color: var(--orange-600);
            line-height: 1;
        }
        .hero-stat-label {
            font-size: 14px; color: var(--text-light);
            margin-top: 6px; font-weight: 500;
        }

        .hero-panel {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.02);
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: transform .5s ease;
        }
        .hero-panel:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
        }
        .panel-header {
            padding: 20px 28px;
            border-bottom: 1px solid var(--border-light);
            display: flex; align-items: center; gap: 12px;
            background: linear-gradient(180deg, var(--bg-secondary), var(--bg-card));
        }
        .panel-dots { display: flex; gap: 8px; }
        .panel-dot { width: 12px; height: 12px; border-radius: 50%; }
        .panel-title { font-size: 14px; color: var(--text-secondary); font-weight: 700; margin-left: 6px; }
        
        .panel-body { padding: 28px; display: flex; flex-direction: column; gap: 16px; }
        .panel-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 20px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            transition: all .3s ease;
        }
        .panel-row:hover { 
            border-color: var(--orange-300);
            background: #fff;
            transform: translateX(8px);
            box-shadow: 0 8px 25px rgba(249,115,22,0.1);
        }
        .panel-row-left { display: flex; align-items: center; gap: 18px; }
        .panel-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            background: #fff; border: 2px solid var(--border-light);
            transition: all .3s ease;
        }
        .panel-row:hover .panel-icon {
            border-color: var(--orange-400);
            background: var(--orange-50);
            transform: scale(1.1);
        }
        .panel-icon svg { width: 24px; height: 24px; }
        .panel-label { font-size: 15px; font-weight: 700; color: var(--text-main); }
        .panel-sub { font-size: 13px; color: var(--text-light); margin-top: 2px; }
        
        .panel-badge {
            font-size: 11px; font-weight: 700; padding: 6px 14px; border-radius: 50px;
        }
        
        .panel-footer {
            padding: 24px 28px;
            border-top: 1px solid var(--border-light);
            display: flex; justify-content: space-around;
            background: linear-gradient(180deg, var(--bg-card), var(--bg-secondary));
        }
        .panel-stat { text-align: center; }
        .panel-stat-num { font-size: 28px; font-weight: 900; color: var(--orange-600); }
        .panel-stat-lbl { font-size: 12px; color: var(--text-light); text-transform: uppercase; letter-spacing: .05em; margin-top: 4px; font-weight: 600; }
        .panel-divider { width: 1px; height: 40px; background: var(--border-light); }

        /* ── SEÇÃO SOBRE ── */
        #sobre {
            padding: 120px 32px;
            background: var(--bg-main);
            border-top: 1px solid var(--border-light);
            position: relative;
        }
        .section-logo-decoration {
            position: absolute;
            top: 50%;
            right: 8%;
            transform: translateY(-50%);
            opacity: 0.04;
            pointer-events: none;
            z-index: 0;
        }
        .section-logo-decoration img {
            height: 350px;
            width: auto;
        }
        
        .section-inner { max-width: 1280px; margin: 0 auto; position: relative; z-index: 1; }
        .section-tag {
            font-size: 14px; font-weight: 800; letter-spacing: .15em;
            text-transform: uppercase; color: var(--orange-600);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .section-tag .mini-logo {
            height: 32px;
            width: auto;
            opacity: 0.7;
        }
        h2 {
            font-size: clamp(36px, 5vw, 52px);
            font-weight: 900; color: var(--text-main);
            line-height: 1.1; margin-bottom: 24px;
            letter-spacing: -0.5px;
        }
        .section-desc { font-size: 18px; color: var(--text-secondary); max-width: 620px; line-height: 1.8; }
        
        .sobre-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 100px; align-items: center; margin-top: 80px;
        }
        @media (max-width: 800px) { .sobre-grid { grid-template-columns: 1fr; gap: 60px; } }
        
        .sobre-text p { font-size: 16px; color: var(--text-secondary); line-height: 1.9; margin-bottom: 22px; }
        
        .check-list { list-style: none; display: flex; flex-direction: column; gap: 18px; margin-top: 36px; }
        .check-list li {
            display: flex; align-items: center; gap: 16px;
            font-size: 16px; color: var(--text-main); font-weight: 600;
        }
        .check-icon {
            width: 32px; height: 32px; border-radius: 10px;
            background: linear-gradient(135deg, var(--orange-400), var(--orange-600));
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(249,115,22,0.3);
            flex-shrink: 0;
        }
        .check-icon svg { width: 18px; height: 18px; color: #fff; }
        
        .quote-card {
            background: linear-gradient(135deg, var(--bg-secondary), #fff);
            border: 2px solid var(--border-light);
            border-left: 6px solid var(--orange-500);
            border-radius: 20px; padding: 40px;
            position: relative;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        }
        .quote-mark { font-size: 80px; line-height: 1; color: var(--orange-300); opacity: 0.5; font-family: Georgia, serif; position: absolute; top: 20px; left: 30px; }
        .quote-text { font-size: 18px; color: var(--text-main); line-height: 1.8; margin: 20px 0 30px; font-weight: 500; font-style: italic; position: relative; z-index: 1; }
        .quote-author { display: flex; align-items: center; gap: 18px; }
        .quote-avatar {
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, var(--orange-500), var(--orange-600));
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 15px rgba(249,115,22,0.3);
        }
        .quote-avatar svg { width: 28px; height: 28px; color: #fff; }
        .quote-name { font-size: 16px; font-weight: 700; color: var(--text-main); }
        .quote-role { font-size: 14px; color: var(--text-light); margin-top: 2px; }

        /* ── RECURSOS ── */
        #recursos {
            padding: 120px 32px;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-light);
            border-bottom: 1px solid var(--border-light);
            position: relative;
        }
        .resources-bg-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.025;
            pointer-events: none;
            z-index: 0;
        }
        .resources-bg-logo img {
            height: 450px;
            width: auto;
        }
        
        .resources-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 32px; margin-top: 64px;
            position: relative;
            z-index: 1;
        }
        @media (max-width: 900px) { .resources-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .resources-grid { grid-template-columns: 1fr; } }
        
        .resource-card {
            background: var(--bg-card);
            border: 2px solid var(--border-light);
            border-radius: 20px; padding: 40px 32px;
            transition: all .4s ease;
            position: relative;
            overflow: hidden;
        }
        .resource-card::before {
            content: ''; position: absolute; top: 0; left: 0; width: 5px; height: 0;
            background: linear-gradient(180deg, var(--orange-400), var(--orange-600)); transition: height .4s ease;
        }
        .resource-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border-color: var(--orange-300);
        }
        .resource-card:hover::before { height: 100%; }
        
        .resource-icon {
            width: 64px; height: 64px; border-radius: 16px;
            background: linear-gradient(135deg, var(--orange-100), rgba(249,115,22,0.15));
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 26px;
            border: 2px solid var(--orange-200);
            box-shadow: 0 4px 15px rgba(249,115,22,0.15);
        }
        .resource-icon svg { width: 32px; height: 32px; color: var(--orange-600); }
        .resource-title { font-size: 20px; font-weight: 800; color: var(--text-main); margin-bottom: 14px; }
        .resource-desc { font-size: 15px; color: var(--text-secondary); line-height: 1.7; }

        /* ── CTA FINAL ── */
        #cta { padding: 120px 32px; background: var(--bg-main); position: relative; }
        .cta-logo-top {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0.08;
            pointer-events: none;
        }
        .cta-logo-top img {
            height: 80px;
            width: auto;
        }
        
        .cta-box {
            max-width: 750px; margin: 0 auto; text-align: center;
            padding: 80px 50px;
            background: linear-gradient(135deg, var(--text-main) 0%, #1e293b 100%);
            border-radius: 32px;
            color: #fff;
            position: relative; overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.2);
        }
        .cta-box::after {
            content: ''; position: absolute; top: -50px; right: -50px;
            width: 300px; height: 300px; background: var(--orange-500);
            filter: blur(100px); opacity: 0.3; border-radius: 50%;
        }
        .cta-box::before {
            content: ''; position: absolute; bottom: -50px; left: -50px;
            width: 250px; height: 250px; background: var(--orange-600);
            filter: blur(80px); opacity: 0.25; border-radius: 50%;
        }
        .cta-box h2 { color: #fff; margin-bottom: 20px; font-size: 42px; }
        .cta-box p { color: rgba(255,255,255,0.8); margin-bottom: 10px; font-size: 17px; }
        .cta-box p.small { font-size: 14px; color: rgba(255,255,255,0.6); margin-bottom: 42px; }
        .cta-actions { display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; position: relative; z-index: 2; }
        
        .cta-box .btn-primary { background: var(--orange-500); color: #fff; font-size: 16px; padding: 16px 40px; }
        .cta-box .btn-primary:hover { background: #fff; color: var(--orange-600); }
        
        .cta-box .btn-secondary { border-color: rgba(255,255,255,0.3); color: #fff; font-size: 16px; padding: 16px 40px; }
        .cta-box .btn-secondary:hover { border-color: #fff; background: rgba(255,255,255,0.15); }

        /* ── FOOTER ── */
        footer {
            padding: 60px 32px 40px;
            background: var(--bg-main);
            border-top: 2px solid var(--border-light);
            text-align: center;
            position: relative;
        }
        .footer-logo {
            margin-bottom: 30px;
        }
        .footer-logo img {
            height: 60px;
            width: auto;
            opacity: 0.9;
            transition: all .3s ease;
            filter: drop-shadow(0 2px 8px rgba(249,115,22,0.2));
        }
        .footer-logo img:hover {
            opacity: 1;
            transform: scale(1.05);
            filter: drop-shadow(0 4px 12px rgba(249,115,22,0.3));
        }
        footer p { font-size: 14px; color: var(--text-light); }
        footer p strong { color: var(--text-secondary); }
        .footer-divider {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--orange-300), var(--orange-600));
            margin: 28px auto;
            border-radius: 4px;
        }
        .footer-links {
            display: flex; justify-content: center; gap: 32px; margin-top: 24px;
        }
        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color .3s ease;
        }
        .footer-links a:hover {
            color: var(--orange-600);
        }

        /* ── ANIMAÇÕES ── */
        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: opacity .8s ease, transform .8s ease;
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
        <img src="{{ asset('images/logo.png') }}" alt="ADTC2" class="loader-logo">
        <p class="loader-text">Carregando sistema...</p>
    </div>

    <!-- Navbar -->
    <nav id="navbar">
        <div class="nav-inner">
            <a href="#home" class="nav-logo">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="ADTC2 System - Templo Central II" 
                     class="logo-image">
                
                <div class="logo-text-group">
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
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary">
                    Entrar
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endauth
        </div>
    </nav>

    <!-- Hero -->
    <section id="home">
        <div class="hero-glow"></div>
        <div class="hero-glow-2"></div>
        
        <div class="hero-inner">
            <div>
                <div class="hero-badge reveal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
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
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary">
                            Acessar o Sistema
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="#sobre" class="btn-secondary">Saiba mais</a>
                    @endauth
                </div>
                
                <div class="hero-stats reveal reveal-delay-4">
                    <div class="hero-stat-item">
                        <div class="hero-stat-number">248</div>
                        <div class="hero-stat-label">Membros Ativos</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-number">5</div>
                        <div class="hero-stat-label">Congregações</div>
                    </div>
                    <div class="hero-stat-item">
                        <div class="hero-stat-number">97%</div>
                        <div class="hero-stat-label">Satisfação</div>
                    </div>
                </div>
            </div>

            <div class="reveal reveal-delay-2">
                <div class="hero-panel">
                    <div class="panel-header">
                        <div class="panel-dots">
                            <div class="panel-dot" style="background:#ef4444"></div>
                            <div class="panel-dot" style="background:#f59e0b"></div>
                            <div class="panel-dot" style="background:#10b981"></div>
                        </div>
                        <span class="panel-title">Painel Administrativo</span>
                    </div>
                    <div class="panel-body">
                        <div class="panel-row">
                            <div class="panel-row-left">
                                <div class="panel-icon">
                                    <svg fill="none" stroke="#f97316" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="panel-label">João da Silva</div>
                                    <div class="panel-sub">Presbítero · Congregação Central</div>
                                </div>
                            </div>
                            <span class="panel-badge" style="background:#ecfdf5;color:#059669">Ativo</span>
                        </div>
                        <div class="panel-row">
                            <div class="panel-row-left">
                                <div class="panel-icon">
                                    <svg fill="none" stroke="#f97316" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="panel-label">Maria Oliveira</div>
                                    <div class="panel-sub">Diácona · Congregação Norte</div>
                                </div>
                            </div>
                            <span class="panel-badge" style="background:#ecfdf5;color:#059669">Ativo</span>
                        </div>
                        <div class="panel-row">
                            <div class="panel-row-left">
                                <div class="panel-icon">
                                    <svg fill="none" stroke="#94a3b8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="panel-label">Carlos Santos</div>
                                    <div class="panel-sub">Membro · Congregação Sul</div>
                                </div>
                            </div>
                            <span class="panel-badge" style="background:#fff7ed;color:#ea580c">Transferido</span>
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
        <div class="section-logo-decoration">
            <img src="{{ asset('images/logo.png') }}" alt="ADTC2">
        </div>
        
        <div class="section-inner">
            <div class="reveal">
                <div class="section-tag">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="mini-logo">
                    Sobre o sistema
                </div>
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
                            Acesso restrito e seguro
                        </li>
                        <li>
                            <div class="check-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            Dados organizados por congregação
                        </li>
                        <li>
                            <div class="check-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            Interface limpa e intuitiva
                        </li>
                        <li>
                            <div class="check-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            Suporte técnico dedicado
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
                                <div class="quote-name">Secretário Geral</div>
                                <div class="quote-role">AD Templo Central II</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recursos -->
    <section id="recursos">
        <div class="resources-bg-logo">
            <img src="{{ asset('images/logo.png') }}" alt="ADTC2">
        </div>
        
        <div class="section-inner">
            <div class="reveal">
                <div class="section-tag">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="mini-logo">
                    Funcionalidades
                </div>
                <h2>Tudo que a secretaria<br>precisa, em um lugar</h2>
            </div>

            <div class="resources-grid">
                @foreach([
                    ['path' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                     'title' => 'Cadastro de Filiados',
                     'desc'  => 'Ficha completa com dados pessoais, endereço e documentos.'],
                    ['path' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                     'title' => 'Gestão de Congregações',
                     'desc'  => 'Cadastre e gerencie todas as congregações separadamente.'],
                    ['path' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
                     'title' => 'Controle de Acesso',
                     'desc'  => 'Administração centralizada com permissões por usuário.'],
                    ['path' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                     'title' => 'Relatórios Visuais',
                     'desc'  => 'Gráficos automáticos de crescimento e estatísticas.'],
                    ['path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                     'title' => 'Documentos Digitais',
                     'desc'  => 'Armazene cartas e fichas de forma segura e acessível.'],
                    ['path' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
                     'title' => 'Acesso Mobile',
                     'desc'  => 'Funciona perfeitamente no celular, tablet e computador.'],
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
        <div class="cta-logo-top">
            <img src="{{ asset('images/logo.png') }}" alt="ADTC2">
        </div>
        
        <div class="cta-box reveal">
            <div class="section-tag" style="color: var(--orange-300); text-align:center; justify-content:center; margin-bottom:24px;">
                <img src="{{ asset('images/logo.png') }}" alt="" class="mini-logo" style="filter: brightness(0) invert(1); opacity: 0.7; height:28px;">
                Acesso Restrito
            </div>
            <h2>Sistema exclusivo da<br>AD Templo Central II</h2>
            <p>Utilize suas credenciais fornecidas pela secretaria para acessar.</p>
            <p class="small">Dúvidas? Entre em contato com o líder da sua congregação.</p>

            <div class="cta-actions">
                <a href="{{ route('login') }}" class="btn-primary">
                    Acessar o Sistema
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="#recursos" class="btn-secondary">Ver recursos</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-logo">
            <img src="{{ asset('images/logo.png') }}" alt="ADTC2 System - Templo Central II">
        </div>
        <div class="footer-divider"></div>
        <p>© {{ date('Y') }} <strong>ADTC2 System</strong>. Todos os direitos reservados.</p>
        <p style="margin-top:12px; font-size:13px;">Desenvolvido para Assembleia de Deus — Templo Central II · Maranguape/CE</p>
        <div class="footer-links">
            <a href="#home">Início</a>
            <a href="#sobre">Sobre</a>
            <a href="#recursos">Recursos</a>
            <a href="{{ route('login') }}">Login</a>
        </div>
    </footer>

    <script>
        window.addEventListener('load', () => {
            setTimeout(() => {
                const l = document.getElementById('loader');
                l.classList.add('out');
                setTimeout(() => l.remove(), 500);
            }, 1200);
        });

        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 30);
        });

        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        reveals.forEach(el => observer.observe(el));
        
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