<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2c363d">
    <title>Sign in | UCO-TMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            color-scheme: light;
            --slate-950: #202a30;
            --slate-900: #2c363d;
            --slate-800: #354149;
            --slate-700: #4b5a64;
            --slate-500: #7d8a92;
            --teal: #12b8c4;
            --teal-dark: #079aa7;
            --teal-soft: #e7f8f9;
            --ink: #27343c;
            --muted: #78868f;
            --line: #e1e7ea;
            --canvas: #f7f8fa;
            --white: #ffffff;
            --danger: #c4475a;
            --danger-bg: #fff4f5;
            --success: #277c65;
            --success-bg: #edf8f3;
        }

        * { box-sizing: border-box; }

        html { min-width: 320px; }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--canvas);
            color: var(--ink);
            font-family: 'Lato', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        button, input { font: inherit; }
        button, label, input { -webkit-tap-highlight-color: transparent; }
        button { touch-action: manipulation; }

        .login-shell {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(480px, .92fr);
            min-height: 100vh;
            min-height: 100svh;
        }

        .brand-panel {
            position: relative;
            isolation: isolate;
            display: flex;
            min-height: 720px;
            overflow: hidden;
            padding: 46px clamp(40px, 5vw, 84px) 38px;
            background: var(--slate-900);
            color: var(--white);
        }

        .brand-panel::before {
            position: absolute;
            z-index: -2;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.022) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.022) 1px, transparent 1px);
            background-size: 64px 64px;
            content: '';
            mask-image: linear-gradient(to bottom, #000, transparent 86%);
        }

        .brand-panel::after {
            position: absolute;
            z-index: -1;
            top: -230px;
            right: -230px;
            width: 620px;
            height: 620px;
            border: 1px solid rgba(18,184,196,.11);
            border-radius: 50%;
            box-shadow: 0 0 0 90px rgba(18,184,196,.025), 0 0 0 180px rgba(18,184,196,.018);
            content: '';
        }

        .brand-inner {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 720px;
            margin: 0 auto;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            align-self: flex-start;
            gap: 13px;
            color: inherit;
            text-decoration: none;
        }

        .brand-logo {
            width: 43px;
            height: 43px;
            object-fit: contain;
        }

        .brand-name {
            display: block;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: .055em;
            line-height: 1.2;
        }

        .brand-caption {
            display: block;
            margin-top: 4px;
            color: #9baab3;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .19em;
            text-transform: uppercase;
        }

        .hero {
            position: relative;
            z-index: 2;
            margin-top: clamp(68px, 10vh, 108px);
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 11px;
            margin: 0 0 20px;
            color: #54d2da;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .17em;
            text-transform: uppercase;
        }

        .eyebrow::before {
            width: 26px;
            height: 2px;
            background: var(--teal);
            content: '';
        }

        .hero h2 {
            max-width: 580px;
            margin: 0;
            font-size: clamp(38px, 3.7vw, 58px);
            font-weight: 500;
            letter-spacing: -.042em;
            line-height: 1.12;
        }

        .hero h2 span { color: #5bd2da; }

        .hero-copy {
            max-width: 480px;
            margin: 21px 0 0;
            color: #aebcc5;
            font-size: 16px;
            line-height: 1.75;
        }

        .preview-wrap {
            position: relative;
            z-index: 1;
            width: min(100%, 570px);
            margin: 42px 0 32px;
            perspective: 1200px;
        }

        .preview-glow {
            position: absolute;
            right: 8%;
            bottom: -30px;
            left: 8%;
            height: 90px;
            border-radius: 50%;
            background: rgba(18,184,196,.13);
            filter: blur(40px);
            pointer-events: none;
        }

        .dashboard-preview {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 12px;
            background: #f8f9fb;
            box-shadow: 0 32px 70px rgba(8,16,20,.34);
            color: var(--ink);
            transform: rotateX(var(--preview-x, 0deg)) rotateY(var(--preview-y, 0deg));
            transform-style: preserve-3d;
            transition: transform .35s ease-out;
            will-change: transform;
        }

        .preview-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 42px;
            padding: 0 18px;
            border-bottom: 1px solid #e7ebee;
            background: #fff;
        }

        .preview-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #52616b;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .preview-title svg { color: var(--teal-dark); }

        .preview-window-controls { display: flex; gap: 5px; }
        .preview-window-controls i { width: 5px; height: 5px; border-radius: 50%; background: #d6dfe4; }

        .preview-body { padding: 21px 23px 22px; }

        .preview-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .preview-heading strong { font-size: 16px; font-weight: 700; }
        .preview-heading small { display: block; margin-top: 4px; color: #95a1a9; font-size: 7px; letter-spacing: .13em; text-transform: uppercase; }

        .preview-new-task {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 10px;
            border-radius: 4px;
            background: var(--teal);
            color: white;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .preview-metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }

        .preview-metric {
            padding: 13px 12px 11px;
            border: 1px solid #e7ecef;
            border-radius: 6px;
            background: white;
            box-shadow: 0 3px 9px rgba(36,52,62,.04);
        }

        .metric-top { display: flex; align-items: baseline; justify-content: space-between; }
        .metric-top span { color: #7c8991; font-size: 8px; font-weight: 600; }
        .metric-top strong { font-size: 13px; font-weight: 700; }

        .metric-track { height: 3px; margin-top: 10px; overflow: hidden; border-radius: 4px; background: #edf1f3; }
        .metric-fill { width: var(--value); height: 100%; border-radius: inherit; background: var(--teal); transform: scaleX(0); transform-origin: left; transition: transform 1.1s cubic-bezier(.2,.75,.25,1) var(--delay); }
        .is-ready .metric-fill { transform: scaleX(1); }

        .preview-task {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-top: 12px;
            padding: 13px;
            border: 1px solid #e7ecef;
            border-radius: 6px;
            background: white;
        }

        .task-check {
            display: grid;
            flex: 0 0 auto;
            width: 24px;
            height: 24px;
            place-items: center;
            border-radius: 6px;
            background: var(--teal-soft);
            color: var(--teal-dark);
        }

        .task-details { min-width: 0; flex: 1; }
        .task-details strong { display: block; overflow: hidden; font-size: 9px; font-weight: 600; text-overflow: ellipsis; white-space: nowrap; }
        .task-details small { display: block; margin-top: 5px; color: #9aa5ac; font-size: 7px; }
        .task-status { padding: 4px 7px; border-radius: 3px; background: #edf8f9; color: #14949f; font-size: 7px; font-weight: 700; }

        .brand-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: auto;
            color: #91a1ab;
            font-size: 11px;
            letter-spacing: .025em;
        }

        .brand-foot span { display: inline-flex; align-items: center; gap: 8px; }
        .brand-foot svg { color: #54cbd3; }

        .form-panel {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 72px clamp(36px, 6vw, 92px) 84px;
            background: var(--canvas);
        }

        .form-column { width: 100%; max-width: 400px; }

        .mobile-brand { display: none; margin-bottom: 48px; }
        .mobile-brand .brand-name { color: var(--ink); }
        .mobile-brand .brand-caption { color: var(--muted); }

        .form-kicker {
            margin: 0 0 12px;
            color: var(--teal-dark);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .form-header h1 {
            margin: 0;
            color: var(--ink);
            font-size: clamp(30px, 3vw, 38px);
            font-weight: 700;
            letter-spacing: -.035em;
            line-height: 1.2;
        }

        .form-header p {
            margin: 13px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.65;
        }

        .login-form { margin-top: 34px; }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 0 0 22px;
            padding: 13px 14px;
            border: 1px solid #f2ced5;
            border-radius: 7px;
            background: var(--danger-bg);
            color: var(--danger);
            font-size: 13px;
            line-height: 1.5;
            animation: alert-in .35s ease both;
        }

        .alert.success { border-color: #cbe8dd; background: var(--success-bg); color: var(--success); }
        .alert svg { flex: 0 0 auto; margin-top: 1px; }

        .field { margin-bottom: 20px; }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: #46545d;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .015em;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            color: #8d9aa3;
            pointer-events: none;
            transform: translateY(-50%);
            transition: color .2s ease;
        }

        .form-input {
            display: block;
            width: 100%;
            height: 52px;
            padding: 0 46px 0 45px;
            border: 1px solid #d9e1e5;
            border-radius: 7px;
            outline: none;
            background: var(--white);
            color: var(--ink);
            font-size: 14px;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .form-input::placeholder { color: #a1adb5; }
        .form-input:hover { border-color: #bdcbd2; }
        .form-input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(18,184,196,.12); }
        .input-wrap:focus-within .input-icon { color: var(--teal-dark); }
        .field.has-error .form-input { border-color: #df8392; }
        .field.has-error .form-input:focus { box-shadow: 0 0 0 3px rgba(196,71,90,.09); }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 7px;
            display: grid;
            width: 38px;
            height: 38px;
            padding: 0;
            place-items: center;
            border: 0;
            border-radius: 5px;
            background: transparent;
            color: #84929b;
            cursor: pointer;
            transform: translateY(-50%);
            transition: color .2s ease, background .2s ease;
        }

        .password-toggle:hover { background: #f0f5f6; color: var(--teal-dark); }
        .password-toggle:focus-visible { outline: 2px solid var(--teal); outline-offset: 1px; }

        .field-error { margin: 7px 0 0; color: var(--danger); font-size: 12px; line-height: 1.4; }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 3px 0 26px;
        }

        .remember {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: #687780;
            cursor: pointer;
            font-size: 13px;
            user-select: none;
        }

        .remember input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .custom-check {
            display: grid;
            width: 17px;
            height: 17px;
            place-items: center;
            border: 1px solid #cbd6dc;
            border-radius: 4px;
            background: #fff;
            color: #fff;
            transition: background .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .custom-check svg { opacity: 0; transform: scale(.6); transition: opacity .15s ease, transform .15s ease; }
        .remember input:checked + .custom-check { border-color: var(--teal); background: var(--teal); }
        .remember input:checked + .custom-check svg { opacity: 1; transform: scale(1); }
        .remember input:focus-visible + .custom-check { box-shadow: 0 0 0 3px rgba(18,184,196,.18); }

        .submit-button {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            width: 100%;
            height: 52px;
            overflow: hidden;
            padding: 0 22px;
            border: 0;
            border-radius: 7px;
            background: var(--teal);
            box-shadow: 0 7px 18px rgba(18,184,196,.18);
            color: #fff;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .055em;
            transition: background .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .submit-button::before {
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.19), transparent);
            content: '';
            transform: skewX(-20deg);
            transition: left .55s ease;
        }

        .submit-button:hover { background: var(--teal-dark); box-shadow: 0 9px 24px rgba(18,184,196,.25); transform: translateY(-1px); }
        .submit-button:hover::before { left: 140%; }
        .submit-button:active { transform: translateY(0); }
        .submit-button:focus-visible { outline: 3px solid rgba(18,184,196,.26); outline-offset: 3px; }
        .submit-button:disabled { cursor: wait; opacity: .82; transform: none; }
        .submit-button .arrow { transition: transform .2s ease; }
        .submit-button:hover .arrow { transform: translateX(3px); }

        .spinner {
            width: 17px;
            height: 17px;
            border: 2px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        .privacy-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin: 20px 0 0;
            color: #8a979f;
            font-size: 11px;
            line-height: 1.5;
            text-align: center;
        }

        .form-footer {
            position: absolute;
            right: 24px;
            bottom: 25px;
            left: 24px;
            color: #98a3aa;
            font-size: 10px;
            letter-spacing: .035em;
            text-align: center;
        }

        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .65s cubic-bezier(.2,.7,.2,1) var(--delay, 0ms), transform .65s cubic-bezier(.2,.7,.2,1) var(--delay, 0ms);
        }

        .reveal-left { transform: translateX(-18px); }
        .is-ready .reveal { opacity: 1; transform: translate(0); }
        .no-react .reveal { opacity: 1; transform: none; }

        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes alert-in { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: none; } }

        @media (max-width: 1100px) {
            .login-shell { grid-template-columns: minmax(0, 1fr) minmax(440px, .9fr); }
            .brand-panel { padding-right: 42px; padding-left: 42px; }
            .hero h2 { font-size: 42px; }
            .preview-wrap { margin-top: 36px; }
        }

        @media (max-width: 860px) {
            .login-shell { display: block; }
            .brand-panel { display: none; }
            .form-panel { min-height: 100vh; min-height: 100svh; padding: 50px 28px 88px; }
            .form-column { max-width: 420px; }
            .mobile-brand { display: inline-flex; }
        }

        @media (max-width: 480px) {
            .form-panel { align-items: flex-start; padding: 30px 22px 80px; }
            .mobile-brand { margin-bottom: 44px; }
            .brand-logo { width: 38px; height: 38px; }
            .brand-name { font-size: 20px; }
            .form-header h1 { font-size: 30px; }
            .login-form { margin-top: 30px; }
            .form-footer { bottom: 20px; line-height: 1.5; }
        }

        @media (max-height: 790px) and (min-width: 861px) {
            .brand-panel { min-height: 680px; padding-top: 32px; padding-bottom: 28px; }
            .hero { margin-top: 46px; }
            .hero h2 { font-size: 42px; }
            .hero-copy { margin-top: 14px; font-size: 14px; }
            .preview-wrap { width: min(100%, 500px); margin-top: 28px; margin-bottom: 24px; }
            .preview-body { padding: 16px 19px; }
            .form-panel { padding-top: 48px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
            .reveal { opacity: 1; transform: none; }
            .metric-fill { transform: scaleX(1); }
            .dashboard-preview { transform: none !important; }
        }
    </style>
</head>
<body class="no-react">
    <main class="login-shell" id="login-page">
        <section class="brand-panel" aria-labelledby="brand-heading">
            <div class="brand-inner">
                <a class="brand reveal reveal-left" style="--delay: 40ms" href="{{ url('/') }}" aria-label="UCO-TMS home">
                    <img class="brand-logo" src="{{ asset('assets/images/logo/accent-teal-100@2x.png') }}" alt="" width="43" height="43">
                    <span>
                        <span class="brand-name">UCO-TMS</span>
                        <span class="brand-caption">Task Management System</span>
                    </span>
                </a>

                <div class="hero">
                    <p class="eyebrow reveal" style="--delay: 100ms">One workspace. Every team.</p>
                    <h2 class="reveal" id="brand-heading" style="--delay: 160ms">Your team's work,<br><span>in one clear view.</span></h2>
                    <p class="hero-copy reveal" style="--delay: 220ms">Bring requests, creative projects, and team tasks together—so everyone knows what matters and what comes next.</p>
                </div>

                <div class="preview-wrap reveal" style="--delay: 300ms" id="dashboard-preview-wrap" aria-hidden="true">
                    <div class="preview-glow"></div>
                    <div class="dashboard-preview" id="dashboard-preview">
                        <div class="preview-topbar">
                            <div class="preview-title">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                                Workspace overview
                            </div>
                            <div class="preview-window-controls"><i></i><i></i><i></i></div>
                        </div>
                        <div class="preview-body">
                            <div class="preview-heading">
                                <div><strong>Good morning, team</strong><small>Home &nbsp; / &nbsp; Dashboard</small></div>
                                <span class="preview-new-task">+ &nbsp; New task</span>
                            </div>
                            <div class="preview-metrics">
                                <div class="preview-metric">
                                    <div class="metric-top"><span>Open</span><strong>04</strong></div>
                                    <div class="metric-track"><div class="metric-fill" style="--value: 14%; --delay: 650ms"></div></div>
                                </div>
                                <div class="preview-metric">
                                    <div class="metric-top"><span>In progress</span><strong>10</strong></div>
                                    <div class="metric-track"><div class="metric-fill" style="--value: 35%; --delay: 740ms"></div></div>
                                </div>
                                <div class="preview-metric">
                                    <div class="metric-top"><span>Closed</span><strong>14</strong></div>
                                    <div class="metric-track"><div class="metric-fill" style="--value: 50%; --delay: 830ms"></div></div>
                                </div>
                            </div>
                            <div class="preview-task">
                                <span class="task-check">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </span>
                                <div class="task-details"><strong>Creative request · New campaign assets</strong><small>Content Team &nbsp;·&nbsp; Updated today</small></div>
                                <span class="task-status">In progress</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="brand-foot reveal" style="--delay: 390ms">
                    <span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><path d="M3 20v-2a6 6 0 0 1 12 0v2"/><circle cx="17" cy="9" r="2"/><path d="M17 14a5 5 0 0 1 4 4.9V20"/></svg>
                        Built for connected teams
                    </span>
                    <span>Plan. Collaborate. Deliver.</span>
                </div>
            </div>
        </section>

        <section class="form-panel" aria-labelledby="login-heading">
            <div class="form-column">
                <a class="brand mobile-brand reveal" href="{{ url('/') }}" aria-label="UCO-TMS home">
                    <img class="brand-logo" src="{{ asset('assets/images/logo/accent-teal-100@2x.png') }}" alt="" width="43" height="43">
                    <span>
                        <span class="brand-name">UCO-TMS</span>
                        <span class="brand-caption">Task Management System</span>
                    </span>
                </a>

                <header class="form-header">
                    <p class="form-kicker reveal" style="--delay: 80ms">Welcome back</p>
                    <h1 class="reveal" id="login-heading" style="--delay: 140ms">Sign in to your workspace</h1>
                    <p class="reveal" style="--delay: 200ms">Enter your credentials to continue to UCO-TMS.</p>
                </header>

                <form class="login-form reveal" style="--delay: 260ms" id="login-form" method="POST" action="{{ route('login.store') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="alert" role="alert">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert success" role="status">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <div class="field @error('email') has-error @enderror">
                        <label class="field-label" for="email">Email address</label>
                        <div class="input-wrap">
                            <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                            <input class="form-input" id="email" name="email" type="email" inputmode="email" autocomplete="username" placeholder="name@uz.edu.ph" value="{{ old('email') }}" aria-describedby="@error('email') email-error @enderror" @error('email') aria-invalid="true" @enderror required autofocus>
                        </div>
                        @error('email')<p class="field-error" id="email-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field @error('password') has-error @enderror">
                        <label class="field-label" for="password">Password</label>
                        <div class="input-wrap">
                            <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                            <input class="form-input" id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" aria-describedby="@error('password') password-error @enderror" @error('password') aria-invalid="true" @enderror required>
                            <span id="password-control"></span>
                        </div>
                        @error('password')<p class="field-error" id="password-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-options">
                        <label class="remember">
                            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                            <span class="custom-check" aria-hidden="true">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>
                            </span>
                            Keep me signed in
                        </label>
                    </div>

                    <button class="submit-button" id="submit-button" type="submit">
                        <span id="submit-label">Sign in</span>
                        <svg class="arrow" id="submit-arrow" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </button>

                    <p class="privacy-note">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        Secure access &middot; Authorized users only
                    </p>
                </form>
            </div>

            <footer class="form-footer">&copy; {{ date('Y') }} UCO-TMS &nbsp;&middot;&nbsp; University Communications Office</footer>
        </section>
    </main>

    <noscript>
        <style>.reveal { opacity: 1 !important; transform: none !important; } .metric-fill { transform: scaleX(1) !important; }</style>
    </noscript>

    <!-- React enhances the existing Laravel form; authentication remains a normal POST. -->
    <script src="https://cdn.jsdelivr.net/npm/react@18.3.1/umd/react.production.min.js" crossorigin></script>
    <script src="https://cdn.jsdelivr.net/npm/react-dom@18.3.1/umd/react-dom.production.min.js" crossorigin></script>
    <script>
        (function () {
            function startLoginExperience() {
                if (!window.React || !window.ReactDOM) {
                    document.body.classList.add('no-react');
                    return;
                }

                var h = window.React.createElement;
                var useEffect = window.React.useEffect;
                var useState = window.React.useState;
                var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

                function LoginEnhancements() {
                    var passwordState = useState(false);
                    var showPassword = passwordState[0];
                    var setShowPassword = passwordState[1];
                    var submittingState = useState(false);
                    var setSubmitting = submittingState[1];

                    useEffect(function () {
                        var frame = window.requestAnimationFrame(function () {
                            document.body.classList.remove('no-react');
                            document.getElementById('login-page').classList.add('is-ready');
                        });

                        var form = document.getElementById('login-form');
                        var button = document.getElementById('submit-button');
                        var label = document.getElementById('submit-label');
                        var arrow = document.getElementById('submit-arrow');

                        function onSubmit(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault();
                                form.reportValidity();
                                return;
                            }

                            setSubmitting(true);
                            button.disabled = true;
                            button.setAttribute('aria-busy', 'true');
                            label.textContent = 'Signing in…';
                            arrow.style.display = 'none';

                            var spinner = document.createElement('span');
                            spinner.className = 'spinner';
                            spinner.setAttribute('aria-hidden', 'true');
                            button.insertBefore(spinner, label);
                        }

                        form.addEventListener('submit', onSubmit);

                        return function () {
                            window.cancelAnimationFrame(frame);
                            form.removeEventListener('submit', onSubmit);
                        };
                    }, []);

                    useEffect(function () {
                        var password = document.getElementById('password');
                        password.type = showPassword ? 'text' : 'password';
                    }, [showPassword]);

                    useEffect(function () {
                        var preview = document.getElementById('dashboard-preview');
                        var wrap = document.getElementById('dashboard-preview-wrap');
                        if (!preview || !wrap || reducedMotion.matches) return;

                        function onMove(event) {
                            var rect = wrap.getBoundingClientRect();
                            var x = (event.clientX - rect.left) / rect.width - .5;
                            var y = (event.clientY - rect.top) / rect.height - .5;
                            preview.style.setProperty('--preview-y', (x * 3.5).toFixed(2) + 'deg');
                            preview.style.setProperty('--preview-x', (-y * 3).toFixed(2) + 'deg');
                        }

                        function onLeave() {
                            preview.style.setProperty('--preview-y', '0deg');
                            preview.style.setProperty('--preview-x', '0deg');
                        }

                        wrap.addEventListener('pointermove', onMove);
                        wrap.addEventListener('pointerleave', onLeave);
                        return function () {
                            wrap.removeEventListener('pointermove', onMove);
                            wrap.removeEventListener('pointerleave', onLeave);
                        };
                    }, []);

                    var eyeIcon = showPassword
                        ? h('svg', { width: 18, height: 18, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: 1.8, strokeLinecap: 'round', strokeLinejoin: 'round', 'aria-hidden': 'true' },
                            h('path', { d: 'M3 3l18 18M10.6 10.7a2 2 0 0 0 2.7 2.7M9.9 4.3A10.8 10.8 0 0 1 12 4c5.5 0 9 6 9 6a17.3 17.3 0 0 1-2.1 2.8M6.5 6.4C3.7 8.3 3 10 3 10s3.5 6 9 6a9.8 9.8 0 0 0 4.1-.8' }))
                        : h('svg', { width: 18, height: 18, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: 1.8, strokeLinecap: 'round', strokeLinejoin: 'round', 'aria-hidden': 'true' },
                            h('path', { d: 'M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z' }),
                            h('circle', { cx: 12, cy: 12, r: 3 }));

                    return h('button', {
                        type: 'button',
                        className: 'password-toggle',
                        onClick: function () { setShowPassword(function (visible) { return !visible; }); },
                        'aria-label': showPassword ? 'Hide password' : 'Show password',
                        'aria-controls': 'password',
                        'aria-pressed': showPassword,
                        title: showPassword ? 'Hide password' : 'Show password',
                        disabled: submittingState[0]
                    }, eyeIcon);
                }

                window.ReactDOM.createRoot(document.getElementById('password-control')).render(h(LoginEnhancements));
            }

            startLoginExperience();
        })();
    </script>
</body>
</html>
