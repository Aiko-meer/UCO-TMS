<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0B1F16">
    <title>Sign in | UCO-TMS</title>

    <link rel="icon" type="image/png" href="/assets/images/uz-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700;9..144,800&display=swap" rel="stylesheet">

    <style>
        :root {
            color-scheme: light;
            --forest-950: #0B1F16;
            --forest-900: #152C1F;
            --forest-800: #1F3D2B;
            --forest-700: #2B5238;
            --forest-600: #3B6B4A;
            --forest-500: #4F7D5C;
            --forest-400: #6B8F71;
            --forest-300: #8FAE92;
            --forest-200: #B9CDB6;
            --forest-100: #DCE7D8;
            --forest-50: #F1F5EC;
            --cream-50: #FBF9F2;
            --cream-100: #F7F4EA;
            --cream-200: #EFEADB;
            --cream-300: #E4DCC8;
            --gold-300: #E3C98A;
            --gold-400: #D4B165;
            --gold-500: #C9A24B;
            --gold-600: #A9853A;
            --ink: #152C1F;
            --muted: #5C7565;
            --line: #DCE7D8;
            --white: #ffffff;
            --danger: #B4435A;
            --danger-bg: #FFF5F6;
            --danger-line: #F1CBD3;
            --success: #2B6145;
            --success-bg: #EDF6EF;
        }

        * {
            box-sizing: border-box
        }

        html {
            min-width: 320px
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--cream-100);
            color: var(--ink);
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            -webkit-font-smoothing: antialiased
        }

        button,
        input {
            font: inherit
        }

        button,
        label,
        input {
            -webkit-tap-highlight-color: transparent
        }

        button {
            touch-action: manipulation
        }

        ::selection {
            background: var(--gold-500);
            color: var(--forest-950)
        }

        .login-shell {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(480px, .92fr);
            min-height: 100vh;
            min-height: 100svh
        }

        /* ---------- Brand panel ---------- */
        .brand-panel {
            position: relative;
            isolation: isolate;
            display: flex;
            min-height: 720px;
            overflow: hidden;
            padding: 44px clamp(40px, 5vw, 82px) 34px;
            background:
                radial-gradient(900px 560px at 12% 0%, rgba(107, 143, 113, .30), transparent 62%),
                radial-gradient(760px 520px at 92% 32%, rgba(201, 162, 75, .12), transparent 60%),
                var(--forest-950);
            color: var(--cream-100)
        }

        .brand-panel::before {
            position: absolute;
            z-index: -1;
            top: -240px;
            right: -240px;
            width: 620px;
            height: 620px;
            border: 1px solid rgba(201, 162, 75, .18);
            border-radius: 50%;
            box-shadow: 0 0 0 90px rgba(201, 162, 75, .035), 0 0 0 180px rgba(201, 162, 75, .022);
            content: ''
        }

        .brand-panel::after {
            position: absolute;
            z-index: -1;
            bottom: -220px;
            left: -180px;
            width: 520px;
            height: 520px;
            border-radius: 50%;
            background: rgba(59, 107, 74, .28);
            filter: blur(90px);
            content: ''
        }

        .leaf {
            position: absolute;
            top: -8%;
            z-index: 0;
            color: var(--forest-300);
            opacity: .2;
            pointer-events: none;
            animation: drift var(--d, 20s) linear infinite;
            animation-delay: var(--delay, 0s)
        }

        .brand-inner {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 720px;
            margin: 0 auto
        }

        .brand {
            display: inline-flex;
            align-items: center;
            align-self: flex-start;
            gap: 13px;
            color: inherit;
            text-decoration: none
        }

        .brand-mark {
            display: grid;
            width: 48px;
            height: 48px;
            flex: 0 0 auto;
            padding: 3px;
            place-items: center;
            overflow: hidden;
            border-radius: 9999px;
            background: var(--white);
            box-shadow: 0 1px 0 rgba(21, 44, 31, .05), 0 10px 24px -14px rgba(0, 0, 0, .8);
            transition: transform .3s ease
        }

        .brand:hover .brand-mark {
            transform: scale(1.06) rotate(-3deg)
        }

        .brand-logo {
            width: 100%;
            height: 100%;
            object-fit: contain
        }

        .brand-name {
            display: block;
            font-family: 'Fraunces', Georgia, serif;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -.01em;
            line-height: 1.15
        }

        .brand-name span {
            color: var(--gold-400)
        }

        .brand-caption {
            display: block;
            margin-top: 4px;
            color: var(--forest-300);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .19em;
            text-transform: uppercase
        }

        .hero {
            position: relative;
            z-index: 2;
            margin-top: clamp(58px, 9vh, 96px)
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin: 0 0 22px;
            padding: 7px 14px;
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 9999px;
            background: rgba(255, 255, 255, .05);
            color: var(--forest-100);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .04em;
            backdrop-filter: blur(6px)
        }

        .eyebrow i {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--gold-400);
            box-shadow: 0 0 0 0 rgba(212, 177, 101, .6);
            animation: pulse 2.4s ease-out infinite
        }

        .hero h2 {
            max-width: 590px;
            margin: 0;
            font-family: 'Fraunces', Georgia, serif;
            font-size: clamp(38px, 3.6vw, 56px);
            font-weight: 700;
            letter-spacing: -.03em;
            line-height: 1.08
        }

        .hero h2 span {
            background: linear-gradient(90deg, #B9CDB6, #E3C98A, #8FAE92, #D4B165, #B9CDB6);
            background-size: 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: grad 8s linear infinite
        }

        .hero-copy {
            max-width: 470px;
            margin: 20px 0 0;
            color: var(--forest-200);
            font-size: 15px;
            line-height: 1.75
        }

        /* ---------- Dashboard preview ---------- */
        .preview-wrap {
            position: relative;
            z-index: 1;
            width: min(100%, 560px);
            margin: 40px 0 30px;
            perspective: 1200px
        }

        .preview-glow {
            position: absolute;
            right: 10%;
            bottom: -26px;
            left: 10%;
            height: 84px;
            border-radius: 50%;
            background: rgba(201, 162, 75, .16);
            filter: blur(42px);
            pointer-events: none
        }

        .dashboard-preview {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 16px;
            background: var(--cream-50);
            box-shadow: 0 34px 74px rgba(0, 0, 0, .42);
            color: var(--ink);
            transform: rotateX(var(--preview-x, 0deg)) rotateY(var(--preview-y, 0deg));
            transform-style: preserve-3d;
            transition: transform .35s ease-out;
            will-change: transform
        }

        .preview-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 44px;
            padding: 0 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
            background: var(--forest-900);
            color: var(--cream-100)
        }

        .preview-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase
        }

        .preview-title svg {
            color: var(--gold-400)
        }

        .preview-window-controls {
            display: flex;
            gap: 5px
        }

        .preview-window-controls i {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .22)
        }

        .preview-body {
            padding: 20px 22px 21px
        }

        .preview-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px
        }

        .preview-heading strong {
            font-family: 'Fraunces', Georgia, serif;
            font-size: 17px;
            font-weight: 700
        }

        .preview-heading small {
            display: block;
            margin-top: 4px;
            color: var(--forest-400);
            font-size: 7px;
            letter-spacing: .13em;
            text-transform: uppercase
        }

        .preview-new-task {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 10px;
            border-radius: 5px;
            background: var(--gold-500);
            color: var(--forest-950);
            font-size: 8px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase
        }

        .preview-metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px
        }

        .preview-metric {
            padding: 13px 12px 11px;
            border: 1px solid var(--cream-200);
            border-radius: 9px;
            background: var(--white);
            box-shadow: 0 3px 9px rgba(11, 31, 22, .04)
        }

        .metric-top {
            display: flex;
            align-items: baseline;
            justify-content: space-between
        }

        .metric-top span {
            color: var(--forest-500);
            font-size: 8px;
            font-weight: 600
        }

        .metric-top strong {
            font-size: 13px;
            font-weight: 700
        }

        .metric-track {
            height: 3px;
            margin-top: 10px;
            overflow: hidden;
            border-radius: 4px;
            background: var(--cream-200)
        }

        .metric-fill {
            width: var(--value);
            height: 100%;
            border-radius: inherit;
            background: var(--fill, var(--gold-500));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 1.1s cubic-bezier(.2, .75, .25, 1) var(--delay)
        }

        .is-ready .metric-fill {
            transform: scaleX(1)
        }

        .preview-task {
            display: flex;
            align-items: center;
            gap: 11px;
            margin-top: 12px;
            padding: 13px;
            border: 1px solid var(--cream-200);
            border-radius: 9px;
            background: var(--white)
        }

        .task-check {
            display: grid;
            width: 25px;
            height: 25px;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 8px;
            background: var(--forest-100);
            color: var(--forest-700)
        }

        .task-details {
            min-width: 0;
            flex: 1
        }

        .task-details strong {
            display: block;
            overflow: hidden;
            font-size: 9px;
            font-weight: 600;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .task-details small {
            display: block;
            margin-top: 5px;
            color: var(--forest-400);
            font-size: 7px
        }

        .task-status {
            padding: 4px 7px;
            border-radius: 4px;
            background: rgba(201, 162, 75, .16);
            color: var(--gold-600);
            font-size: 7px;
            font-weight: 700
        }

        .brand-foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: auto;
            color: var(--forest-300);
            font-size: 11px;
            letter-spacing: .025em
        }

        .brand-foot span {
            display: inline-flex;
            align-items: center;
            gap: 8px
        }

        .brand-foot .foot-seal {
            width: 26px;
            height: 26px;
            padding: 1px;
            border-radius: 50%;
            background: #fff
        }

        .brand-foot .foot-seal img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%
        }

        /* ---------- Form panel ---------- */
        .form-panel {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 76px clamp(36px, 6vw, 88px) 92px;
            background:
                radial-gradient(620px 360px at 100% 0%, rgba(201, 162, 75, .10), transparent 62%),
                var(--cream-100)
        }

        .back-link {
            position: absolute;
            top: 30px;
            right: 34px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--forest-500);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: color .2s ease, gap .2s ease
        }

        .back-link:hover {
            gap: 11px;
            color: var(--forest-900)
        }

        .form-column {
            width: 100%;
            max-width: 400px
        }

        .mobile-brand {
            display: none;
            margin-bottom: 44px
        }

        .mobile-brand .brand-name {
            color: var(--forest-900)
        }

        .mobile-brand .brand-caption {
            color: var(--forest-500)
        }

        .form-kicker {
            margin: 0 0 12px;
            color: var(--gold-600);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase
        }

        .form-header h1 {
            margin: 0;
            color: var(--forest-900);
            font-family: 'Fraunces', Georgia, serif;
            font-size: clamp(30px, 3vw, 38px);
            font-weight: 700;
            letter-spacing: -.03em;
            line-height: 1.16
        }

        .form-header p {
            margin: 13px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.65
        }

        .login-form {
            margin-top: 32px
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 0 0 22px;
            padding: 13px 14px;
            border: 1px solid var(--danger-line);
            border-radius: 12px;
            background: var(--danger-bg);
            color: var(--danger);
            font-size: 13px;
            line-height: 1.5;
            animation: alert-in .35s ease both
        }

        .alert.success {
            border-color: var(--forest-200);
            background: var(--success-bg);
            color: var(--success)
        }

        .alert svg {
            flex: 0 0 auto;
            margin-top: 1px
        }

        .field {
            margin-bottom: 20px
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: var(--forest-800);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .015em
        }

        .input-wrap {
            position: relative
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            color: var(--forest-400);
            pointer-events: none;
            transform: translateY(-50%);
            transition: color .2s ease
        }

        .form-input {
            display: block;
            width: 100%;
            height: 52px;
            padding: 0 46px 0 45px;
            border: 1px solid var(--line);
            border-radius: 12px;
            outline: none;
            background: var(--cream-50);
            color: var(--ink);
            font-size: 14px;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease
        }

        .form-input::placeholder {
            color: #93A897
        }

        .form-input:hover {
            border-color: var(--forest-300)
        }

        .form-input:focus {
            border-color: var(--forest-500);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(79, 125, 92, .16)
        }

        .input-wrap:focus-within .input-icon {
            color: var(--forest-600)
        }

        .field.has-error .form-input {
            border-color: #D98CA0
        }

        .field.has-error .form-input:focus {
            box-shadow: 0 0 0 3px rgba(180, 67, 90, .10)
        }

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
            border-radius: 8px;
            background: transparent;
            color: var(--forest-400);
            cursor: pointer;
            transform: translateY(-50%);
            transition: color .2s ease, background .2s ease
        }

        .password-toggle:hover {
            background: var(--forest-50);
            color: var(--forest-700)
        }

        .password-toggle:focus-visible {
            outline: 2px solid var(--forest-500);
            outline-offset: 1px
        }

        .field-error {
            margin: 7px 0 0;
            color: var(--danger);
            font-size: 12px;
            line-height: 1.4
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 3px 0 26px
        }

        .remember {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--forest-600);
            cursor: pointer;
            font-size: 13px;
            user-select: none
        }

        .remember input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0
        }

        .custom-check {
            display: grid;
            width: 17px;
            height: 17px;
            place-items: center;
            border: 1px solid var(--forest-300);
            border-radius: 5px;
            background: var(--cream-50);
            color: #fff;
            transition: background .2s ease, border-color .2s ease, box-shadow .2s ease
        }

        .custom-check svg {
            opacity: 0;
            transform: scale(.6);
            transition: opacity .15s ease, transform .15s ease
        }

        .remember input:checked+.custom-check {
            border-color: var(--forest-700);
            background: var(--forest-700)
        }

        .remember input:checked+.custom-check svg {
            opacity: 1;
            transform: scale(1)
        }

        .remember input:focus-visible+.custom-check {
            box-shadow: 0 0 0 3px rgba(79, 125, 92, .20)
        }

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
            border-radius: 12px;
            background: var(--forest-800);
            box-shadow: 0 16px 32px -18px rgba(11, 31, 22, .85);
            color: var(--cream-50);
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .055em;
            transition: background .2s ease, box-shadow .2s ease, transform .2s ease
        }

        .submit-button::before {
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .16), transparent);
            content: '';
            transform: skewX(-20deg);
            transition: left .55s ease
        }

        .submit-button:hover {
            background: var(--forest-700);
            box-shadow: 0 20px 38px -18px rgba(11, 31, 22, .75);
            transform: translateY(-1px)
        }

        .submit-button:hover::before {
            left: 140%
        }

        .submit-button:active {
            transform: translateY(0)
        }

        .submit-button:focus-visible {
            outline: 3px solid rgba(79, 125, 92, .28);
            outline-offset: 3px
        }

        .submit-button:disabled {
            cursor: wait;
            opacity: .82;
            transform: none
        }

        .submit-button .arrow {
            color: var(--gold-300);
            transition: transform .2s ease
        }

        .submit-button:hover .arrow {
            transform: translateX(3px)
        }

        .spinner {
            width: 17px;
            height: 17px;
            border: 2px solid rgba(255, 255, 255, .35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite
        }

        .privacy-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin: 20px 0 0;
            color: var(--forest-400);
            font-size: 11px;
            line-height: 1.5;
            text-align: center
        }

        .form-footer {
            position: absolute;
            right: 24px;
            bottom: 24px;
            left: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            color: var(--forest-400);
            font-size: 10px;
            letter-spacing: .035em;
            text-align: center
        }

        .form-footer .foot-line {
            display: inline-flex;
            align-items: center;
            gap: 7px
        }

        .form-footer .foot-seal {
            width: 20px;
            height: 20px;
            padding: 1px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(11, 31, 22, .12)
        }

        .form-footer .foot-seal img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%
        }

        .form-footer .dev {
            color: var(--forest-500)
        }

        .form-footer .dev strong {
            color: var(--gold-600);
            font-weight: 600
        }

        /* ---------- Motion ---------- */
        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .65s cubic-bezier(.2, .7, .2, 1) var(--delay, 0ms), transform .65s cubic-bezier(.2, .7, .2, 1) var(--delay, 0ms)
        }

        .reveal-left {
            transform: translateX(-18px)
        }

        .is-ready .reveal {
            opacity: 1;
            transform: translate(0)
        }

        .no-react .reveal {
            opacity: 1;
            transform: none
        }

        @keyframes drift {
            0% {
                transform: translate3d(0, -8%, 0) rotate(0deg);
                opacity: 0
            }

            12% {
                opacity: var(--o, .2)
            }

            55% {
                transform: translate3d(40px, 55vh, 0) rotate(200deg)
            }

            88% {
                opacity: var(--o, .2)
            }

            100% {
                transform: translate3d(-26px, 118vh, 0) rotate(400deg);
                opacity: 0
            }
        }

        @keyframes grad {
            to {
                background-position: 200% 50%
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(212, 177, 101, .55)
            }

            70% {
                box-shadow: 0 0 0 8px rgba(212, 177, 101, 0)
            }

            100% {
                box-shadow: 0 0 0 0 rgba(212, 177, 101, 0)
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        @keyframes alert-in {
            from {
                opacity: 0;
                transform: translateY(-5px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 1100px) {
            .login-shell {
                grid-template-columns: minmax(0, 1fr) minmax(440px, .9fr)
            }

            .brand-panel {
                padding-right: 42px;
                padding-left: 42px
            }

            .hero h2 {
                font-size: 42px
            }

            .preview-wrap {
                margin-top: 34px
            }
        }

        @media (max-width: 900px) {
            .login-shell {
                display: block
            }

            .brand-panel {
                display: none
            }

            .form-panel {
                flex-direction: column;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                min-height: 100svh;
                padding: 54px 28px 54px
            }

            .form-column {
                max-width: 420px
            }

            .mobile-brand {
                display: inline-flex
            }

            .back-link {
                display: none
            }

            .form-footer {
                position: static;
                margin-top: 46px
            }
        }

        @media (max-width: 480px) {
            .form-panel {
                justify-content: flex-start;
                padding: 26px 22px 38px
            }

            .mobile-brand {
                margin-bottom: 38px
            }

            .brand-mark {
                width: 42px;
                height: 42px
            }

            .brand-name {
                font-size: 20px
            }

            .form-header h1 {
                font-size: 30px
            }

            .login-form {
                margin-top: 28px
            }

            .form-footer {
                margin-top: 38px;
                line-height: 1.6
            }
        }

        @media (max-height: 790px) and (min-width: 901px) {
            .brand-panel {
                min-height: 680px;
                padding-top: 30px;
                padding-bottom: 26px
            }

            .hero {
                margin-top: 42px
            }

            .hero h2 {
                font-size: 42px
            }

            .hero-copy {
                margin-top: 14px;
                font-size: 14px
            }

            .preview-wrap {
                width: min(100%, 500px);
                margin-top: 26px;
                margin-bottom: 22px
            }

            .preview-body {
                padding: 16px 19px
            }

            .form-panel {
                padding-top: 52px
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important
            }

            .reveal {
                opacity: 1;
                transform: none
            }

            .metric-fill {
                transform: scaleX(1)
            }

            .dashboard-preview {
                transform: none !important
            }

            .leaf {
                display: none
            }
        }
    </style>
</head>

<body class="no-react">
    <main class="login-shell" id="login-page">
        <section class="brand-panel" aria-labelledby="brand-heading">
            <div class="leaf" style="left:7%;--d:21s;--delay:-5s;--o:.18">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
            </div>
            <div class="leaf" style="left:32%;--d:26s;--delay:-13s;--o:.13">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
            </div>
            <div class="leaf" style="left:58%;--d:19s;--delay:-8s;--o:.16">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
            </div>
            <div class="leaf" style="left:82%;--d:24s;--delay:-3s;--o:.2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
            </div>
            <div class="leaf" style="left:93%;--d:23s;--delay:-16s;--o:.12">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
            </div>

            <div class="brand-inner">
                <a class="brand reveal reveal-left" style="--delay: 40ms" href="{{ url('/') }}" aria-label="UCO-TMS home">
                    <span class="brand-mark"><img class="brand-logo" src="/assets/images/uz-logo.png" alt="Universidad de Zamboanga seal"></span>
                    <span>
                        <span class="brand-name">UCO<span>·</span>TMS</span>
                        <span class="brand-caption">Universidad de Zamboanga</span>
                    </span>
                </a>

                <div class="hero">
                    <p class="eyebrow reveal" style="--delay: 100ms"><i></i>University Communication Office</p>
                    <h2 class="reveal" id="brand-heading" style="--delay: 160ms">Your team's work,<br><span>in one clear view.</span></h2>
                    <p class="hero-copy reveal" style="--delay: 220ms">Bring requests, creative projects, and team tasks together — so everyone knows what matters and what comes next.</p>
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
                                    <div class="metric-track"><div class="metric-fill" style="--value: 14%; --delay: 650ms; --fill: #C9A24B"></div></div>
                                </div>
                                <div class="preview-metric">
                                    <div class="metric-top"><span>In progress</span><strong>10</strong></div>
                                    <div class="metric-track"><div class="metric-fill" style="--value: 35%; --delay: 740ms; --fill: #4F7D5C"></div></div>
                                </div>
                                <div class="preview-metric">
                                    <div class="metric-top"><span>Closed</span><strong>14</strong></div>
                                    <div class="metric-track"><div class="metric-fill" style="--value: 50%; --delay: 830ms; --fill: #8FAE92"></div></div>
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
                        <span class="foot-seal"><img src="/assets/images/uco-logo.png" alt=""></span>
                        The Voice of Quality Education
                    </span>
                    <span>Plan. Collaborate. Deliver.</span>
                </div>
            </div>
        </section>

        <section class="form-panel" aria-labelledby="login-heading">
            <a class="back-link" href="{{ url('/') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                Back to request portal
            </a>

            <div class="form-column">
                <a class="brand mobile-brand reveal" href="{{ url('/') }}" aria-label="UCO-TMS home">
                    <span class="brand-mark"><img class="brand-logo" src="/assets/images/uz-logo.png" alt="Universidad de Zamboanga seal"></span>
                    <span>
                        <span class="brand-name">UCO<span>·</span>TMS</span>
                        <span class="brand-caption">University Communication Office</span>
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

            <footer class="form-footer">
                <span class="foot-line">
                    <span class="foot-seal"><img src="/assets/images/uco-logo.png" alt=""></span>
                    &copy; {{ date('Y') }} UCO-TMS &nbsp;&middot;&nbsp; University Communication Office
                </span>
                <span class="dev">Developed by <strong>Zaeed Cervantes</strong></span>
            </footer>
        </section>
    </main>

    <noscript>
        <style>
            .reveal {
                opacity: 1 !important;
                transform: none !important
            }

            .metric-fill {
                transform: scaleX(1) !important
            }
        </style>
    </noscript>

    <!-- React enhances the existing Laravel form; authentication remains a normal POST. -->
    <script src="https://cdn.jsdelivr.net/npm/react@18.3.1/umd/react.production.min.js" crossorigin></script>
    <script src="https://cdn.jsdelivr.net/npm/react-dom@18.3.1/umd/react-dom.production.min.js" crossorigin></script>
    <script>
        (function() {
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

                    useEffect(function() {
                        var frame = window.requestAnimationFrame(function() {
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

                        return function() {
                            window.cancelAnimationFrame(frame);
                            form.removeEventListener('submit', onSubmit);
                        };
                    }, []);

                    useEffect(function() {
                        var password = document.getElementById('password');
                        password.type = showPassword ? 'text' : 'password';
                    }, [showPassword]);

                    useEffect(function() {
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
                        return function() {
                            wrap.removeEventListener('pointermove', onMove);
                            wrap.removeEventListener('pointerleave', onLeave);
                        };
                    }, []);

                    var eyeIcon = showPassword ?
                        h('svg', { width: 18, height: 18, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: 1.8, strokeLinecap: 'round', strokeLinejoin: 'round', 'aria-hidden': 'true' },
                            h('path', { d: 'M3 3l18 18M10.6 10.7a2 2 0 0 0 2.7 2.7M9.9 4.3A10.8 10.8 0 0 1 12 4c5.5 0 9 6 9 6a17.3 17.3 0 0 1-2.1 2.8M6.5 6.4C3.7 8.3 3 10 3 10s3.5 6 9 6a9.8 9.8 0 0 0 4.1-.8' })) :
                        h('svg', { width: 18, height: 18, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: 1.8, strokeLinecap: 'round', strokeLinejoin: 'round', 'aria-hidden': 'true' },
                            h('path', { d: 'M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z' }),
                            h('circle', { cx: 12, cy: 12, r: 3 }));

                    return h('button', {
                        type: 'button',
                        className: 'password-toggle',
                        onClick: function() {
                            setShowPassword(function(visible) {
                                return !visible;
                            });
                        },
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
