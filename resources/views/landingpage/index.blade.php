<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UCO-TMS · Submit a Request</title>
    <link rel="icon" type="image/png" href="/assets/images/uz-logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700;9..144,800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script>
        tailwind.config = {
            darkMode: 'media',
            theme: {
                extend: {
                    colors: {
                        forest: {
                            50: '#F1F5EC',
                            100: '#DCE7D8',
                            200: '#B9CDB6',
                            300: '#8FAE92',
                            400: '#6B8F71',
                            500: '#4F7D5C',
                            600: '#3B6B4A',
                            700: '#2B5238',
                            800: '#1F3D2B',
                            900: '#152C1F',
                            950: '#0B1F16'
                        },
                        cream: {
                            50: '#FBF9F2',
                            100: '#F7F4EA',
                            200: '#EFEADB',
                            300: '#E4DCC8'
                        },
                        gold: {
                            300: '#E3C98A',
                            400: '#D4B165',
                            500: '#C9A24B',
                            600: '#A9853A'
                        }
                    },
                    fontFamily: {
                        display: ['Fraunces', 'Georgia', 'serif']
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            box-sizing: border-box;
            padding-top: env(safe-area-inset-top, 0px);
            padding-bottom: env(safe-area-inset-bottom, 0px)
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 88px
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif
        }

        section[id] {
            scroll-margin-top: 88px
        }

        ::selection {
            background: #C9A24B;
            color: #0B1F16
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1)
            }

            50% {
                transform: translateY(-26px) scale(1.06)
            }
        }

        @keyframes drift {
            0% {
                transform: translate3d(0, -8%, 0) rotate(0deg);
                opacity: 0
            }

            12% {
                opacity: var(--o, .3)
            }

            55% {
                transform: translate3d(42px, 55vh, 0) rotate(200deg)
            }

            88% {
                opacity: var(--o, .3)
            }

            100% {
                transform: translate3d(-28px, 118vh, 0) rotate(400deg);
                opacity: 0
            }
        }

        @keyframes sway {

            0%,
            100% {
                transform: translateY(0) rotate(-4deg)
            }

            50% {
                transform: translateY(-16px) rotate(5deg)
            }
        }

        @keyframes pop {
            from {
                opacity: 0;
                transform: translateY(20px) scale(.97)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @keyframes grad {
            to {
                background-position: 200% 50%
            }
        }

        @keyframes spinSlow {
            to {
                transform: rotate(360deg)
            }
        }

        @keyframes sealPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(201, 162, 75, .55)
            }

            70% {
                box-shadow: 0 0 0 26px rgba(201, 162, 75, 0)
            }

            100% {
                box-shadow: 0 0 0 0 rgba(201, 162, 75, 0)
            }
        }

        @keyframes ringSpin {
            to {
                transform: rotate(360deg)
            }
        }

        @keyframes preSlide {
            0% {
                transform: translateX(-110%)
            }

            100% {
                transform: translateX(360%)
            }
        }

        .blob {
            animation: float 10s ease-in-out infinite
        }

        .gtext {
            background: linear-gradient(90deg, #B9CDB6, #E3C98A, #8FAE92, #D4B165, #B9CDB6);
            background-size: 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: grad 8s linear infinite
        }

        .leaf {
            position: absolute;
            top: -10%;
            pointer-events: none;
            color: #8FAE92;
            opacity: .28;
            animation: drift var(--d, 15s) linear infinite;
            animation-delay: var(--delay, 0s)
        }

        .medallion {
            animation: sway 8s ease-in-out infinite
        }

        .orbit {
            animation: spinSlow 46s linear infinite
        }

        .logo-chip {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            background: #fff;
            padding: 3px;
            overflow: hidden;
            box-shadow: 0 1px 0 rgba(21, 44, 31, .05), 0 8px 20px -12px rgba(21, 44, 31, .45);
            transition: transform .3s ease
        }

        .logo-group:hover .logo-chip {
            transform: scale(1.07) rotate(-3deg)
        }

        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: all .8s cubic-bezier(.2, .7, .2, 1)
        }

        .reveal.show {
            opacity: 1;
            transform: none
        }

        .modal-box {
            animation: pop .35s ease both
        }

        #siteHeader.hdr-scrolled {
            box-shadow: 0 16px 36px -26px rgba(11, 31, 22, .65)
        }

        #preloader {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: grid;
            place-items: center;
            background: radial-gradient(1000px 620px at 50% 32%, #1F3D2B 0%, #0B1F16 72%);
            transition: opacity .7s ease, visibility .7s ease
        }

        #preloader.done {
            opacity: 0;
            visibility: hidden
        }

        .pre-seal {
            width: 88px;
            height: 88px;
            border-radius: 9999px;
            background: #fff;
            object-fit: contain;
            padding: 6px;
            animation: sealPulse 1.9s ease-out infinite
        }

        .pre-ring {
            position: absolute;
            inset: -10px;
            border-radius: 9999px;
            border: 1px solid rgba(201, 162, 75, .25);
            border-top-color: #D4B165;
            animation: ringSpin 1.4s linear infinite
        }

        .pre-bar {
            height: 2px;
            width: 150px;
            background: rgba(255, 255, 255, .12);
            border-radius: 9999px;
            overflow: hidden
        }

        .pre-bar span {
            display: block;
            height: 100%;
            width: 40%;
            background: linear-gradient(90deg, #6B8F71, #E3C98A);
            animation: preSlide 1.3s ease-in-out infinite
        }

        @media (prefers-reduced-motion:reduce) {
            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important
            }

            .reveal {
                opacity: 1;
                transform: none
            }

            #preloader {
                display: none
            }
        }
    </style>
</head>

<body class="bg-cream-100 text-forest-900 antialiased">

    <div id="scrollProgress" class="fixed top-0 left-0 z-50 h-[3px] w-0 bg-gradient-to-r from-forest-500 via-gold-400 to-gold-300 transition-[width] duration-150" aria-hidden="true"></div>

    <div id="preloader" aria-hidden="true">
        <div class="text-center">
            <div class="relative mx-auto w-24 h-24 grid place-items-center">
                <span class="pre-ring"></span>
                <img src="/assets/images/uz-logo.png" alt="" class="pre-seal">
            </div>
            <p class="mt-7 font-display text-xl font-semibold tracking-wide text-cream-100">UCO<span class="text-gold-400">·</span>TMS</p>
            <p class="mt-1 text-[10px] uppercase tracking-[.35em] text-forest-300">Preparing your experience</p>
            <div class="pre-bar mx-auto mt-5"><span></span></div>
        </div>
    </div>
    <noscript><style>#preloader{display:none}</style></noscript>

    <header id="siteHeader" class="sticky top-0 z-40 backdrop-blur-md bg-cream-100/85 border-b border-forest-200/60 transition-shadow duration-300">
        <div class="max-w-6xl mx-auto px-5 h-[72px] flex items-center justify-between gap-4">
            <a href="#" class="logo-group flex items-center gap-3">
                <span class="logo-chip"><img src="/assets/images/uz-logo.png" alt="Universidad de Zamboanga seal" class="w-full h-full object-contain"></span>
                <span class="leading-tight">
                    <span class="block font-display font-bold text-lg text-forest-900 tracking-tight">UCO<span class="text-gold-500">·</span>TMS</span>
                    <span class="hidden sm:block text-[10px] uppercase tracking-[.2em] text-forest-500">Universidad de Zamboanga</span>
                </span>
            </a>
            <nav class="hidden sm:flex gap-7 text-sm font-medium text-forest-700">
                <a class="relative hover:text-forest-950 transition after:absolute after:-bottom-1.5 after:left-0 after:h-px after:w-0 after:bg-gold-500 after:transition-all hover:after:w-full" href="#requests">Requests</a>
                <a class="relative hover:text-forest-950 transition after:absolute after:-bottom-1.5 after:left-0 after:h-px after:w-0 after:bg-gold-500 after:transition-all hover:after:w-full" href="#how">How it works</a>
                <a class="relative hover:text-forest-950 transition after:absolute after:-bottom-1.5 after:left-0 after:h-px after:w-0 after:bg-gold-500 after:transition-all hover:after:w-full" href="#track">Track</a>
            </nav>
            <a href="/login" class="whitespace-nowrap rounded-full border border-forest-300 text-sm font-semibold px-4 sm:px-5 py-2 text-forest-800 transition hover:bg-forest-800 hover:text-cream-50 hover:-translate-y-0.5">Staff login</a>
        </div>
    </header>

    <section class="relative overflow-hidden bg-forest-950 text-cream-100">
        <div class="absolute inset-0 bg-[radial-gradient(900px_500px_at_15%_0%,rgba(107,143,113,.28),transparent_60%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(800px_520px_at_90%_30%,rgba(201,162,75,.14),transparent_60%)]"></div>
        <div class="blob absolute -top-24 -left-24 w-96 h-96 rounded-full bg-forest-600/30 blur-3xl"></div>
        <div class="blob absolute top-20 right-0 w-96 h-96 rounded-full bg-gold-500/10 blur-3xl" style="animation-delay:-4s"></div>

        <div class="leaf" style="left:6%;--d:19s;--delay:-5s;--o:.22">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
        </div>
        <div class="leaf" style="left:22%;--d:24s;--delay:-12s;--o:.16">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
        </div>
        <div class="leaf" style="left:44%;--d:21s;--delay:-8s;--o:.18">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
        </div>
        <div class="leaf" style="left:62%;--d:26s;--delay:-2s;--o:.2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
        </div>
        <div class="leaf" style="left:78%;--d:18s;--delay:-10s;--o:.24">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
        </div>
        <div class="leaf" style="left:92%;--d:23s;--delay:-15s;--o:.14">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
        </div>

        <div class="relative max-w-6xl mx-auto px-5 pt-20 pb-32 lg:pt-24 lg:pb-36 grid lg:grid-cols-[1.12fr_.88fr] gap-14 lg:gap-10 items-center">
            <div class="text-center lg:text-left">
                <span class="reveal inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-1.5 text-xs font-semibold text-forest-100 backdrop-blur">
                    <span class="w-1.5 h-1.5 rounded-full bg-gold-400 animate-pulse"></span>
                    University Communication Office
                </span>
                <h1 class="reveal mt-6 font-display text-4xl sm:text-5xl lg:text-[3.6rem] font-bold leading-[1.06] tracking-tight" style="transition-delay:.1s">
                    Need something from UCO?<br>
                    <span class="gtext">Request it in minutes.</span>
                </h1>
                <p class="reveal mt-6 text-base sm:text-lg text-forest-200 max-w-xl mx-auto lg:mx-0" style="transition-delay:.2s">
                    Choose a request type, fill out the form, and get a reference number to follow your request from submission to completion.
                </p>
                <div class="reveal mt-9 flex flex-wrap justify-center lg:justify-start gap-4" style="transition-delay:.3s">
                    <a href="#requests" class="group rounded-xl bg-gold-500 text-forest-950 font-semibold px-7 py-3 shadow-[0_18px_40px_-18px_rgba(201,162,75,.8)] transition hover:bg-gold-400 hover:-translate-y-0.5">
                        Choose a request
                        <span class="inline-block transition group-hover:translate-x-1">→</span>
                    </a>
                    <a href="#track" class="rounded-xl border border-white/25 text-cream-100 font-semibold px-7 py-3 transition hover:bg-white/10 hover:-translate-y-0.5">Track my request</a>
                </div>
                <div class="reveal mt-12 grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0" style="transition-delay:.4s">
                    <div>
                        <div class="font-display text-3xl font-bold text-cream-50" data-count="5">0</div>
                        <div class="mt-1 text-[11px] uppercase tracking-wider text-forest-300">Request types</div>
                    </div>
                    <div>
                        <div class="font-display text-3xl font-bold text-cream-50" data-count="7">0</div>
                        <div class="mt-1 text-[11px] uppercase tracking-wider text-forest-300">Days AVP production</div>
                    </div>
                    <div>
                        <div class="font-display text-3xl font-bold text-cream-50">24/7</div>
                        <div class="mt-1 text-[11px] uppercase tracking-wider text-forest-300">Submit anytime</div>
                    </div>
                </div>
            </div>

            <div class="reveal relative mx-auto w-60 h-60 sm:w-72 sm:h-72 lg:w-80 lg:h-80" style="transition-delay:.25s" data-parallax="0.06">
                <div class="absolute inset-0 rounded-full bg-gold-400/20 blur-3xl"></div>
                <div class="orbit absolute -inset-5 rounded-full border border-dashed border-gold-400/30"></div>
                <div class="absolute inset-0 rounded-full border border-white/10"></div>
                <div class="medallion relative h-full w-full rounded-full bg-white p-5 sm:p-7 shadow-[0_30px_80px_-30px_rgba(0,0,0,.8)] ring-1 ring-white/30">
                    <img src="/assets/images/uz-logo.png" alt="Universidad de Zamboanga official seal" class="h-full w-full object-contain">
                </div>
                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-forest-900/90 px-4 py-2 text-[11px] font-semibold text-cream-100 ring-1 ring-white/15 backdrop-blur">
                    <span class="mr-1.5 inline-block h-1.5 w-1.5 rounded-full bg-gold-400 align-middle"></span>Official UCO request portal
                </div>
            </div>
        </div>

        <svg class="absolute bottom-0 left-0 w-full h-10 sm:h-14 text-cream-100" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true">
            <path fill="currentColor" d="M0 64C240 8 480 8 720 40s480 32 720-8v48H0Z"></path>
        </svg>
    </section>

    <section id="requests" class="relative max-w-6xl mx-auto px-5 py-20 sm:py-24">
        <div class="text-center">
            <p class="reveal text-[11px] font-bold uppercase tracking-[.3em] text-gold-600">Our services</p>
            <h2 class="reveal mt-3 font-display text-3xl sm:text-4xl font-bold tracking-tight">What do you need?</h2>
            <p class="reveal mt-3 text-forest-500">Select a request type to open its form.</p>
        </div>
        <div id="cards" class="mt-12 grid sm:grid-cols-2 lg:grid-cols-6 gap-6"></div>
    </section>

    <section id="how" class="relative overflow-hidden bg-forest-900 text-cream-100">
        <div class="absolute inset-0 bg-[radial-gradient(700px_400px_at_85%_20%,rgba(201,162,75,.12),transparent_65%)]"></div>
        <div class="absolute -bottom-24 -left-20 w-80 h-80 rounded-full bg-forest-700/30 blur-3xl"></div>
        <div class="relative max-w-6xl mx-auto px-5 py-20 sm:py-24">
            <div class="text-center">
                <p class="reveal text-[11px] font-bold uppercase tracking-[.3em] text-gold-300">Simple process</p>
                <h2 class="reveal mt-3 font-display text-3xl sm:text-4xl font-bold tracking-tight">How it works</h2>
            </div>
            <div class="relative mt-14 grid md:grid-cols-4 gap-10 text-center">
                <div class="hidden md:block absolute top-7 left-[13%] right-[13%] h-px bg-gradient-to-r from-transparent via-gold-400/35 to-transparent"></div>
                <div class="reveal relative">
                    <div class="mx-auto w-14 h-14 rounded-full bg-forest-800 ring-1 ring-gold-400/30 text-gold-300 grid place-items-center font-display text-xl font-bold shadow-[0_0_30px_-8px_rgba(201,162,75,.4)]">1</div>
                    <h3 class="mt-4 font-semibold">Choose</h3>
                    <p class="text-sm text-forest-300 mt-1">Pick the request type.</p>
                </div>
                <div class="reveal relative" style="transition-delay:.1s">
                    <div class="mx-auto w-14 h-14 rounded-full bg-forest-800 ring-1 ring-gold-400/30 text-gold-300 grid place-items-center font-display text-xl font-bold shadow-[0_0_30px_-8px_rgba(201,162,75,.4)]">2</div>
                    <h3 class="mt-4 font-semibold">Fill the form</h3>
                    <p class="text-sm text-forest-300 mt-1">Use your official UZ email.</p>
                </div>
                <div class="reveal relative" style="transition-delay:.2s">
                    <div class="mx-auto w-14 h-14 rounded-full bg-forest-800 ring-1 ring-gold-400/30 text-gold-300 grid place-items-center font-display text-xl font-bold shadow-[0_0_30px_-8px_rgba(201,162,75,.4)]">3</div>
                    <h3 class="mt-4 font-semibold">Get a reference no.</h3>
                    <p class="text-sm text-forest-300 mt-1">Save it to follow up.</p>
                </div>
                <div class="reveal relative" style="transition-delay:.3s">
                    <div class="mx-auto w-14 h-14 rounded-full bg-forest-800 ring-1 ring-gold-400/30 text-gold-300 grid place-items-center font-display text-xl font-bold shadow-[0_0_30px_-8px_rgba(201,162,75,.4)]">4</div>
                    <h3 class="mt-4 font-semibold">Track</h3>
                    <p class="text-sm text-forest-300 mt-1">See the status until it’s done.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="track" class="max-w-2xl mx-auto px-5 py-20 sm:py-24">
        <div class="text-center">
            <p class="reveal text-[11px] font-bold uppercase tracking-[.3em] text-gold-600">Stay updated</p>
            <h2 class="reveal mt-3 font-display text-3xl sm:text-4xl font-bold tracking-tight">Track your request</h2>
        </div>
        <div class="reveal mt-9 rounded-3xl border border-forest-200/70 bg-white p-6 sm:p-8 shadow-[0_30px_60px_-45px_rgba(11,31,22,.5)]">
            <form id="trackForm" class="flex gap-3">
                <input name="ref" id="trackRef" required maxlength="20" autocomplete="off" placeholder="e.g. A1B2C3" aria-label="Reference number"
                    class="flex-1 rounded-xl border border-forest-200 bg-cream-50 px-4 py-3 outline-none transition focus:ring-2 focus:ring-forest-500 focus:border-forest-400">
                <button type="submit" class="rounded-xl bg-forest-800 text-cream-50 font-semibold px-6 transition hover:bg-forest-700 hover:-translate-y-0.5">Track</button>
            </form>
            <p class="mt-4 text-sm text-forest-500 text-center">
                Lost your reference?
                <button id="recoverToggle" type="button" class="font-semibold text-gold-600 hover:underline">Find it with your email</button>
            </p>
            <form id="recoverForm" class="hidden mt-5 grid gap-3 text-left sm:grid-cols-2">
                <input id="recoverEmail" name="email" type="email" required autocomplete="email" placeholder="name@uz.edu.ph" aria-label="Email address"
                    class="rounded-xl border border-forest-200 bg-cream-50 px-4 py-3 outline-none transition focus:ring-2 focus:ring-forest-500 focus:border-forest-400">
                <input name="l_name" type="text" required autocomplete="family-name" placeholder="Last name" aria-label="Last name"
                    class="rounded-xl border border-forest-200 bg-cream-50 px-4 py-3 outline-none transition focus:ring-2 focus:ring-forest-500 focus:border-forest-400">
                <button type="submit" class="sm:col-span-2 rounded-xl border border-forest-700 text-forest-800 font-semibold py-3 transition hover:bg-forest-800 hover:text-cream-50">Find my references</button>
            </form>
            <div id="trackResult" class="mt-6 text-left" aria-live="polite"></div>
            <p id="trackMsg" class="mt-4 text-sm text-forest-500 text-center" aria-live="polite"></p>
        </div>
    </section>

    <footer class="relative overflow-hidden bg-forest-950 text-cream-100">
        <div class="absolute inset-0 bg-[radial-gradient(600px_300px_at_20%_0%,rgba(107,143,113,.18),transparent_70%)]"></div>
        <div class="leaf" style="left:70%;--d:26s;--delay:-8s;--o:.1">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M21 3C10 4 4 10 4 19c9 0 15-7 17-16Z"/></svg>
        </div>
        <div class="relative max-w-6xl mx-auto px-5 py-14">
            <div class="grid gap-10 md:grid-cols-[auto_1fr_auto] items-center">
                <div class="flex items-center gap-5 justify-center md:justify-start text-center md:text-left">
                    <span class="logo-group shrink-0 w-24 h-24 rounded-full bg-white p-2 shadow-[0_20px_40px_-20px_rgba(0,0,0,.8)] ring-1 ring-white/20">
                        <img src="/assets/images/uco-logo.png" alt="University Communication Office seal" class="w-full h-full object-contain rounded-full">
                    </span>
                    <div>
                        <p class="font-display text-xl font-semibold">University Communication Office</p>
                        <p class="mt-1 text-sm text-forest-300 max-w-xs md:max-w-sm">Universidad de Zamboanga · The Voice of Quality Education</p>
                    </div>
                </div>
                <div class="hidden md:block h-px w-full bg-gradient-to-r from-white/15 via-white/5 to-transparent"></div>
                <div class="text-center md:text-right text-sm text-forest-300">
                    <p class="text-[10px] uppercase tracking-[.25em] text-forest-400">Developer</p>
                    <p class="mt-1 font-semibold text-gold-300">Zaeed Cervantes</p>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row gap-3 items-center justify-between text-xs text-forest-400">
                <p>© 2026 UCO-TMS. All rights reserved.</p>
                <p>Universidad de Zamboanga · University Communication Office</p>
            </div>
        </div>
    </footer>

    <div id="modal" class="hidden fixed inset-0 z-50 bg-forest-950/70 backdrop-blur-sm overflow-y-auto p-4">
        <div class="modal-box max-w-3xl mx-auto my-6 rounded-2xl bg-cream-50 border border-forest-200/70 shadow-2xl p-6 sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div id="mIcon" class="shrink-0 w-14 h-14 rounded-2xl bg-forest-100 text-forest-700 grid place-items-center"></div>
                    <div>
                        <h3 id="mTitle" class="font-display text-2xl font-bold text-forest-900"></h3>
                        <p class="text-sm text-forest-500">Submit a request to the UCO team</p>
                    </div>
                </div>
                <button id="mClose" class="text-3xl leading-none text-forest-400 hover:text-forest-800 transition" aria-label="Close">×</button>
            </div>
            <form id="mForm" enctype="multipart/form-data" class="mt-6 grid grid-cols-12 gap-4"></form>
            <div id="mDone" class="hidden text-center py-8">
                <div class="mx-auto w-16 h-16 rounded-full bg-forest-100 text-forest-700 grid place-items-center text-3xl">✓</div>
                <h4 class="mt-4 font-display text-xl font-bold">Request submitted!</h4>
                <p class="text-forest-500 mt-1">Your reference number:</p>
                <p id="mRef" class="mt-2 inline-block rounded-lg bg-forest-100 px-4 py-2 font-mono font-bold text-forest-900"></p>
            </div>
        </div>
    </div>

    <script>
        // ---------- Small helpers to describe fields ----------
        // f(name, label, type, width(1-12), options)   options: opt (optional), ph, hint, pat, show:[field,value], ...
        const f = (n, l, t = 'text', w = 12, o = {}) => ({
            n,
            l,
            t,
            w,
            ...o
        });
        const sel = (n, l, opts, w = 6, o = {}) => f(n, l, 'select', w, {
            o: opts,
            ...o
        });
        const H = (h, s) => ({
            h,
            s
        });
        const NOTE = note => ({
            note
        });
        const DRIVE = 'https://.*drive\\.google\\.com/.*';
        const YESNO = [
            ['1', 'Yes'],
            ['0', 'No']
        ];
        const SECTIONS = ['Institutional', 'Academic', 'Administrative', ['Affilate', 'Affiliate'], 'External'];
        const PLATFORMS = ['Facebook Page', 'Website', 'Twitter', 'Youtube', 'Instagram', 'Campus Connect (GLOBE/TM/SMART)'];
        const SPECS = ['Advertisements', 'Advisory', 'Album', 'Announcement', 'Urgent', 'Banner', 'Celebration', 'Congratulatory', 'Guidelines', 'Live Stream'];

        const NAME3 = (mid = 'middle') => [f('f_name', 'First name', 'text', 4, {
            ph: 'e.g. Juan'
        }), f(mid, 'Middle name', 'text', 4, {
            opt: 1,
            ph: 'e.g. Santos'
        }), f('l_name', 'Last name', 'text', 4, {
            ph: 'e.g. Dela Cruz'
        })];
        const DEPT = sel('department', 'Department', [
            ['UCO', 'University Communication Office']
        ], 6, {
            noph: 1
        });
        const UZMAIL = f('email', 'Email address', 'email', 6, {
            ph: 'name@uz.edu.ph',
            pat: '.*@uz\\.edu\\.ph$',
            hint: 'Please use your official UZ email address; other emails will not be accepted.'
        });
        const SECTION = sel('section', 'Section', SECTIONS, 6);
        const OTHERCAT = f('other_category', 'Please specify other category', 'text', 12, {
            show: ['category', 'others'],
            ph: 'Type category here...'
        });
        const REMINDER = NOTE('<b>NOTE:</b> AVP needs at least 7 DAYS production time. Sit-in is encouraged before the Final Cut.');
        const APPROVE = f('approve', 'Approved by', 'text', 12, {
            ph: 'Department Head'
        });
        const DETAILS = H('Request Details', 'Tell us what service or assistance you need.');

        // Inline line icons so every card shares the same forest-toned style.
        const ICON = paths => `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8" aria-hidden="true">${paths}</svg>`;
        const ICONS = {
            social: ICON('<path d="M3 11v2a1 1 0 0 0 1 1h2l4 3V7L6 10H4a1 1 0 0 0-1 1Z"/><path d="M14 8a5 5 0 0 1 0 8"/><path d="M17 5a9 9 0 0 1 0 14"/>'),
            layout: ICON('<rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9h18M9 21V9"/><path d="M14 15l1.7 1.7L19 13.4"/>'),
            print: ICON('<path d="M7 8V3h10v5"/><rect x="3" y="8" width="18" height="9" rx="2"/><path d="M7 14h10v7H7z"/><path d="M17 11h.01"/>'),
            trademark: ICON('<path d="M12 3l7 3v5c0 4.4-2.9 7.6-7 10-4.1-2.4-7-5.6-7-10V6l7-3Z"/><path d="M9 12l2 2 4-4"/>'),
            photo: ICON('<rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="9" cy="10" r="2"/><path d="M21 16l-5-5-9 9"/>')
        };

        // ---------- The five request forms (copied from your Blade files) ----------
        const TYPES = [{
                id: 'socmed',
                icon: ICONS.social,
                color: 'forest',
                title: 'Social Media Request',
                action: '/request/socmed',
                desc: 'Posts and announcements for UCO social media and websites.',
                fields: [
                    H('Requester Information'), ...NAME3(), DEPT, UZMAIL, DETAILS,
                    f('purpose', 'Purpose', 'text', 6, {
                        ph: 'Purpose of the request'
                    }),
                    f('date_needed', 'Date needed', 'date', 6),
                    sel('category', 'Category', PLATFORMS),
                    sel('specification', 'Specification', SPECS),
                    f('album_photo[]', 'Album photos attachment', 'file', 12, {
                        multi: 1,
                        acc: 'image/png,image/jpeg',
                        show: ['specification', 'Album'],
                        hint: 'JPEG or PNG. You can select multiple photos. Max 10 MB per file.'
                    }),
                    f('content_information', 'Content information', 'textarea', 12, {
                        ph: 'e.g. 🎉 Facebook promotional post for Foundation Day...'
                    }),
                    SECTION,
                    f('needs_layout', 'Do you need a layout for this request?', 'radio', 6, {
                        o: YESNO,
                        def: '0'
                    }),
                    {
                        h: 'Layout Details',
                        show: ['needs_layout', '1']
                    },
                    sel('category_layout', 'Layout category', ['Advisory', 'Congratulatory', 'Social Card/Facebook Banner'], 6, {
                        show: ['needs_layout', '1']
                    }),
                    f('reference[]', 'Layout reference attachments', 'file', 6, {
                        multi: 1,
                        opt: 1,
                        show: ['needs_layout', '1'],
                        hint: 'PDF, DOCX, JPG, PNG. Max 10 MB.'
                    }),
                    f('content_attachement', 'Resource link / cloud storage URL', 'url', 12, {
                        show: ['needs_layout', '0'],
                        ph: 'https://drive.google.com/... or cloud link',
                        hint: 'Share a public link (Google Drive, OneDrive, etc.). If you have no attachment, choose Yes for layout.'
                    }),
                    APPROVE
                ]
            },
            {
                id: 'layout',
                icon: ICONS.layout,
                color: 'gold',
                title: 'Layout Request',
                action: '/request/layout',
                desc: 'Advisories, banners, e-invitations, logos, programmes, and more.',
                fields: [
                    H('Requester Information'), ...NAME3(), DEPT, UZMAIL,
                    f('date_needed', 'Date needed', 'date', 6), DETAILS, SECTION,
                    sel('category', 'Category', ['Advisory', 'Congratulatory', 'Social Card/Facebook Banner', 'Press Release', 'e-Invitation', 'LED (The Summit Centre)', 'IHK eBillboard', 'Event Programme', 'Logo Production', 'Logo Enhancement', ['website', 'Website'],
                        ['others', 'Others']
                    ]),
                    OTHERCAT,
                    f('purpose', 'Purpose of request', 'textarea', 12, {
                        ph: 'Event of the request'
                    }),
                    f('content_info', 'Content information', 'textarea', 12),
                    f('content_attachment', 'Content information attachment', 'url', 12, {
                        opt: 1,
                        pat: DRIVE,
                        ph: 'https://drive.google.com/...',
                        hint: 'Google Drive links only, for easy access to your materials (optional).'
                    }),
                    f('needs_layout', 'Do you need a posting schedule for this request?', 'radio', 12, {
                        o: YESNO,
                        def: '0'
                    }),
                    {
                        h: 'Posting Schedule Details',
                        show: ['needs_layout', '1']
                    },
                    sel('specification_sod', 'Specification', SPECS, 6, {
                        show: ['needs_layout', '1']
                    }),
                    sel('category_sod', 'Platform', PLATFORMS, 6, {
                        show: ['needs_layout', '1']
                    }),
                    f('content_caption', 'Content caption', 'textarea', 12, {
                        show: ['needs_layout', '1'],
                        ph: 'e.g. 🎉 Facebook promotional post for Foundation Day...'
                    }),
                    f('date_needed_socmed', 'Posting date needed', 'date', 6, {
                        show: ['needs_layout', '1']
                    }),
                    REMINDER, APPROVE
                ]
            },
            {
                id: 'production',
                icon: ICONS.print,
                color: 'sage',
                title: 'Production Request',
                action: '/request/production',
                desc: 'Certificates, plaques, invitations, and printed materials.',
                fields: [
                    H('Requester Information'), ...NAME3(), DEPT, UZMAIL,
                    f('date_needed', 'Date needed', 'date', 6), DETAILS, SECTION,
                    sel('category', 'Category', ['Certificate', 'Plaque', 'Invitation', 'Newspaper', ['others', 'Others']]),
                    OTHERCAT,
                    f('size', 'Size', 'text', 6, {
                        ph: 'e.g. A4, 8.5x11'
                    }),
                    f('quantity', 'Quantity', 'number', 6, {
                        opt: 1,
                        min: 1,
                        ph: 'Enter the quantity'
                    }),
                    f('type_material', 'Type of material', 'text', 6, {
                        ph: 'Enter the type of material'
                    }),
                    f('purpose', 'Purpose of request', 'textarea', 12, {
                        ph: 'Event of the request'
                    }),
                    f('content_info', 'Content information', 'textarea', 12),
                    f('content_attachment', 'Content information attachment', 'url', 12, {
                        pat: DRIVE,
                        ph: 'https://drive.google.com/...',
                        hint: 'Google Drive links only, for easy access to your materials.'
                    }),
                    REMINDER, APPROVE
                ]
            },
            {
                id: 'trademark',
                icon: ICONS.trademark,
                color: 'moss',
                title: 'Trademark Request',
                action: '/request/trademark',
                desc: 'Permission to use the official logo, seal, mascot, or tagline.',
                fields: [
                    H('1. Requestor Information'),
                    sel('salutation', 'Salutation', ['Mr.', 'Ms.', 'Mrs.', 'Dr.', 'Prof.'], 3),
                    f('f_name', 'First name', 'text', 3, {
                        ph: 'John'
                    }), f('middle_name', 'Middle name', 'text', 3, {
                        opt: 1,
                        ph: 'M.'
                    }), f('l_name', 'Last name', 'text', 3, {
                        ph: 'Doe'
                    }),
                    sel('category_requestor', 'Requestor category', ['Student', 'Faculty / Teacher', 'Administrative Staff', 'Alumni', 'External Partner / Organization']),
                    f('position', 'Position / designation', 'text', 6, {
                        ph: 'e.g. Club President, Instructor'
                    }),
                    sel('department_org', 'Department / organization', ('Arturo Eustaquio Libraries and Information Centre|Education Technology Centre|External Affairs and Institutional Development Centre|Finance Department|F45 Zamboanga|General Services Department|High School Department|Human Resources Department|Institute of Technical Education|Logistics and Procurement Department|Management and Information Systems Department|Office of the Bldg. Admin (Lab. & HS)|Office of the Vice President for Academic Affairs|Office of the Vice President for Administration|Office of the Vice President for Finance|Office of the University President|Quality Management Systems Department|Safety, Health, and Environment Department|School of Allied Medicine|School of Business and Management|School of Criminal Justice|School of Dentistry|School of Education|School of Engineering, Information, Communications, and Technology|School of Liberal Arts and Sciences|Student Affairs and Services Department|Summit Centre|University Bookstore and Merchandise|University Communications Department|University Registrar|University Research and Development Centre|UZ-Ipil Branch|UZ-Pagadian Branch|Others').split('|')),
                    f('contact_number', 'Contact number', 'tel', 6, {
                        ph: 'e.g. 09123456789'
                    }),
                    f('email', 'Email address', 'email', 6, {
                        ph: 'name@example.com'
                    }),
                    f('office_address', 'Office / mailing address', 'text', 6, {
                        ph: 'Room / building / address'
                    }),
                    H('2. Trademark Usage Details'),
                    sel('trademark_to_use', 'Trademark to use', ['University Official Logo', 'Institutional Seal', 'Wildcat Mascot Logo', 'Department / School Emblem', 'Official Typography / Tagline']),
                    f('date_to_use', 'Date to use', 'date', 6),
                    sel('category_trademark_use', 'Category for trademark use', [
                        ['profit', 'Profit'],
                        ['non-profit', 'Non-Profit'],
                        ['fundrasing', 'Fundraising']
                    ]),
                    f('target_audience', 'Target audience', 'text', 6, {
                        ph: 'e.g. Enrolled students, general public'
                    }),
                    f('materials_media', 'Materials / media', 'text', 6, {
                        ph: 'e.g. Posters, shirts, social media banner'
                    }),
                    f('location_use', 'Location of use', 'text', 6, {
                        ph: 'e.g. Main campus, Facebook page'
                    }),
                    f('purpose_of_use', 'Purpose of use', 'textarea', 12, {
                        ph: 'Explain the context and reason for requesting the trademark...'
                    }),
                    f('note', 'Additional note / remarks', 'textarea', 12, {
                        opt: 1
                    }),
                    f('approved_by', 'Approved by', 'text', 12, {
                        opt: 1,
                        ph: 'e.g. Dr. Jane Doe / Dean’s Office'
                    })
                ]
            },
            {
                id: 'softcopy',
                icon: ICONS.photo,
                color: 'bark',
                title: 'Soft Copy Request',
                action: '/request/softcopy',
                desc: 'Soft copies of your graduation or yearbook photos.',
                fields: [
                    H('Requester Information'), ...NAME3('middle_name'),
                    f('number', 'Contact number', 'text', 6, {
                        ph: '09XXXXXXXXX'
                    }),
                    f('id_number', 'Student number', 'text', 6),
                    f('email', 'Email address', 'email', 6, {
                        ph: 'name@uz.edu.ph',
                        pat: '.*@uz\\.edu\\.ph$',
                        hint: 'Please use your official UZ email address.'
                    }),
                    H('Status'),
                    f('is_graduating', 'Are you graduating?', 'radio', 12, {
                        o: [
                            ['Yes', 'Yes'],
                            ['No', 'No']
                        ]
                    }),
                    H('Academic & Photo Details'),
                    sel('school_level', 'School level', [
                        ['college', 'College'],
                        ['gradstudies', 'Graduate Studies']
                    ]),
                    sel('school', 'School', [
                        ['1', 'School of Allied Medicine'],
                        ['2', 'School of Engineering, Information, Communications, and Technology'],
                        ['3', 'School of Business and Management'],
                        ['4', 'Institute of Technical Education'],
                        ['5', 'School of Education'],
                        ['6', 'School of Liberal Arts and Sciences'],
                        ['7', 'Graduate Studies Program'],
                        ['8', 'School of Dentistry'],
                        ['9', 'Ipil Campus'],
                        ['10', 'Pagadian Campus']
                    ]),
                    f('program', 'Program / course', 'text', 6, {
                        ph: 'e.g. BS Information Technology'
                    }),
                    sel('year_level', 'Year level', ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year', 'N/A']),
                    f('grad', 'Year graduated (if alumni)', 'text', 6, {
                        opt: 1,
                        ph: 'e.g. 2025'
                    }),
                    f('date_photo', 'Date of photo', 'date', 6),
                    f('id_attachment', 'ID attachment', 'file', 12, {
                        acc: 'image/jpeg,image/png',
                        hint: 'Upload your school ID or a valid ID (JPG, PNG, max 10 MB).'
                    })
                ]
            }
        ];

        // ---------- Cards ----------
        const COLORS = {
            forest: 'bg-forest-100 text-forest-700',
            gold: 'bg-gold-500/15 text-gold-600',
            sage: 'bg-forest-200/70 text-forest-700',
            moss: 'bg-forest-700 text-cream-100',
            bark: 'bg-cream-300 text-[#7A5A34]'
        };
        const esc = s => String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
        const cards = document.getElementById('cards');
        TYPES.forEach((t, i) => {
            const b = document.createElement('button');
            b.className = 'reveal group relative overflow-hidden text-left rounded-2xl border border-forest-200/70 bg-white p-6 shadow-[0_20px_45px_-38px_rgba(11,31,22,.7)] hover:shadow-[0_28px_60px_-30px_rgba(11,31,22,.35)] hover:-translate-y-2 hover:border-forest-400 transition-all duration-300 lg:col-span-2' + (i === 3 ? ' lg:col-start-2' : '');
            b.style.transitionDelay = (i % 3) * 0.1 + 's';
            b.innerHTML = `<div class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 rounded-full bg-forest-100/70 opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-100"></div>
    <div class="relative w-14 h-14 rounded-2xl ${COLORS[t.color]} grid place-items-center group-hover:scale-110 group-hover:-rotate-3 transition duration-300">${t.icon}</div>
    <h3 class="relative mt-4 font-display text-lg font-bold">${t.title}</h3><p class="relative mt-1 text-sm text-forest-500">${t.desc}</p>
    <span class="relative mt-4 inline-block text-sm font-semibold text-forest-700">Fill out form <span class="inline-block group-hover:translate-x-1 transition">→</span></span>`;
            b.onclick = () => openModal(t);
            cards.appendChild(b);
        });

        // ---------- Form builder: turns one field description into HTML ----------
        const IN = 'w-full rounded-xl border border-forest-200 bg-cream-50 px-4 py-2.5 outline-none transition focus:ring-2 focus:ring-forest-500 focus:border-forest-400 file:mr-3 file:rounded-lg file:border-0 file:bg-forest-100 file:px-3 file:py-1 file:text-forest-700';

        function render(x) {
            const sh = x.show ? ` data-sn="${x.show[0]}" data-sv="${x.show[1]}"` : '';
            if (x.h) return `<div class="col-span-12 mt-3 border-b border-forest-200 pb-1"${sh}><h4 class="text-xs font-bold uppercase tracking-wider text-forest-600">${esc(x.h)}</h4>${x.s?`<p class="text-xs text-forest-500">${esc(x.s)}</p>`:''}</div>`;
            if (x.note) return `<div class="col-span-12 rounded-xl border border-gold-500/40 bg-gold-500/10 p-3 text-sm"${sh}>${x.note}</div>`;
            const req = x.opt ? '' : 'required',
                ph = x.ph ? ` placeholder="${esc(x.ph)}"` : '';
            let c;
            if (x.t === 'textarea') c = `<textarea name="${x.n}" rows="3" ${req}${ph} class="${IN}"></textarea>`;
            else if (x.t === 'select') c = `<select name="${x.n}" ${req} class="${IN}">${x.noph?'':'<option value="" selected disabled>Select</option>'}${x.o.map(v=>{v=Array.isArray(v)?v:[v,v];return `<option value="${esc(v[0])}">${esc(v[1])}</option>`}).join('')}</select>`;
            else if (x.t === 'radio') c = `<div class="flex gap-6 pt-1">${x.o.map(v=>`<label class="inline-flex items-center gap-2 font-normal"><input type="radio" name="${x.n}" value="${v[0]}" ${req} ${v[0]===x.def?'checked':''} class="accent-forest-700 w-4 h-4"> ${v[1]}</label>`).join('')}</div>`;
            else c = `<input type="${x.t}" name="${x.n}" ${req}${ph}${x.pat?` pattern="${esc(x.pat)}"`:''}${x.min?` min="${x.min}"`:''}${x.multi?' multiple':''}${x.acc?` accept="${x.acc}"`:''} class="${IN}">`;
            const star = x.opt ? '<span class="font-normal text-forest-400"> (optional)</span>' : '<span class="text-red-500"> *</span>';
            return `<label class="col-span-12 sm:col-span-${x.w} block text-sm font-semibold"${sh}>${esc(x.l)}${star}${x.hint?`<span class="block text-xs font-normal text-forest-500 mt-0.5">${esc(x.hint)}</span>`:''}<span class="block mt-1 font-normal">${c}</span></label>`;
        }

        // Show / hide fields that depend on another field (e.g. "Others", "Yes/No")
        const modal = document.getElementById('modal'),
            form = document.getElementById('mForm'),
            done = document.getElementById('mDone');

        function sync() {
            form.querySelectorAll('[data-sn]').forEach(w => {
                const cur = form.elements[w.dataset.sn];
                const hide = !cur || cur.value !== w.dataset.sv;
                w.classList.toggle('hidden', hide);
                w.querySelectorAll('input,select,textarea').forEach(i => i.disabled = hide); // disabled = not required, not sent
            });
        }
        form.addEventListener('change', sync);

        function openModal(t) {
            document.getElementById('mIcon').innerHTML = t.icon;
            document.getElementById('mTitle').textContent = t.title;
            form.innerHTML = t.fields.map(render).join('') +
                '<div class="col-span-12"><button class="w-full rounded-xl bg-forest-800 hover:bg-forest-700 text-cream-50 font-semibold py-3 shadow-[0_18px_35px_-20px_rgba(11,31,22,.7)] transition hover:-translate-y-0.5">Submit request</button></div>';
            form.classList.remove('hidden');
            done.classList.add('hidden');
            sync();
            form.onsubmit = async e => {
                e.preventDefault();
                const btn = form.querySelector('button');
                if (btn) {
                    btn.disabled = true;
                    btn.textContent = 'Submitting…';
                }
                try {
                    const r = await fetch(t.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await r.json().catch(() => ({}));
                    if (!r.ok) {
                        const first = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Please check the form and try again.');
                        alert(first);
                        return;
                    }
                    // The reference shown is the request_id that was saved in the database.
                    document.getElementById('mRef').textContent = data.ref;
                    form.classList.add('hidden');
                    done.classList.remove('hidden');
                } catch (err) {
                    alert('Network error. Please check your connection and try again.');
                } finally {
                    if (btn) {
                        btn.disabled = false;
                        btn.textContent = 'Submit request';
                    }
                }
            };
            modal.classList.remove('hidden');
            modal.scrollTop = 0;
            document.body.style.overflow = 'hidden';
        }
        const closeModal = () => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        };
        document.getElementById('mClose').onclick = closeModal;
        modal.addEventListener('click', e => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });
        const trackForm = document.getElementById('trackForm'),
            trackMsg = document.getElementById('trackMsg'),
            trackResult = document.getElementById('trackResult'),
            recoverForm = document.getElementById('recoverForm');
        const TRACK_TONES = {
            warning: 'bg-gold-500/15 text-gold-600',
            success: 'bg-forest-100 text-forest-700',
            info: 'bg-forest-50 text-forest-600 ring-1 ring-forest-200',
            danger: 'bg-rose-100 text-rose-700',
            primary: 'bg-cream-300 text-[#7A5A34]'
        };
        const fmtDate = v => v ? new Date(v).toLocaleString(undefined, {
            dateStyle: 'medium',
            timeStyle: 'short'
        }) : '';
        const trackBadges = parts => parts.map(p => `<span class="rounded-full px-3 py-1 text-xs font-bold ${TRACK_TONES[p.tone]||TRACK_TONES.info}">${esc(p.type)} · ${esc(p.status)}</span>`).join('');

        document.getElementById('recoverToggle').onclick = () => {
            recoverForm.classList.toggle('hidden');
            if (!recoverForm.classList.contains('hidden')) document.getElementById('recoverEmail').focus();
        };

        trackForm.onsubmit = async e => {
            e.preventDefault();
            const btn = trackForm.querySelector('button');
            const ref = document.getElementById('trackRef').value.trim();
            if (!ref) return;

            btn.disabled = true;
            trackResult.innerHTML = '';
            trackMsg.textContent = 'Looking up your request…';

            try {
                const r = await fetch('/track?ref=' + encodeURIComponent(ref), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await r.json().catch(() => ({}));

                if (!r.ok) {
                    trackMsg.textContent = data.message || 'No request was found with that reference number.';
                    return;
                }

                trackMsg.textContent = '';
                const submitted = fmtDate(data.submitted_at);
                trackResult.innerHTML = `
                    <div class="rounded-2xl border border-forest-200/70 bg-cream-50 p-6">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-forest-500">Reference number</p>
                                <p class="font-mono text-xl font-bold">${esc(data.request_id)}</p>
                                ${submitted?`<p class="mt-1 text-xs text-forest-500">Submitted ${esc(submitted)}</p>`:''}
                            </div>
                            <span class="rounded-full bg-forest-100 px-3 py-1 text-xs font-bold text-forest-700">${data.parts.length} request${data.parts.length>1?'s':''}</span>
                        </div>
                        <div class="mt-5 space-y-2">
                            ${data.parts.map(p=>`<div class="flex flex-wrap items-center justify-between gap-3 rounded-xl bg-white px-4 py-3 border border-forest-100">
                                <span class="text-sm font-semibold">${esc(p.type)}</span>
                                <span class="rounded-full px-3 py-1 text-xs font-bold ${TRACK_TONES[p.tone]||TRACK_TONES.info}">${esc(p.status)}</span>
                            </div>`).join('')}
                        </div>
                    </div>`;
            } catch (err) {
                trackMsg.textContent = 'Network error. Please check your connection and try again.';
            } finally {
                btn.disabled = false;
            }
        };

        recoverForm.onsubmit = async e => {
            e.preventDefault();
            const btn = recoverForm.querySelector('button[type=submit]');
            btn.disabled = true;
            trackResult.innerHTML = '';
            trackMsg.textContent = 'Looking up your references…';

            try {
                const r = await fetch('/track/recover', {
                    method: 'POST',
                    body: new FormData(recoverForm),
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });
                const data = await r.json().catch(() => ({}));

                if (!r.ok) {
                    trackMsg.textContent = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'No requests were found for that email address and last name.');
                    return;
                }

                trackMsg.textContent = '';
                trackResult.innerHTML = `
                    <div class="rounded-2xl border border-forest-200/70 bg-cream-50 p-6">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-forest-500">Matching references</p>
                                <p class="mt-1 text-sm text-forest-500">Select a reference to see its status.</p>
                            </div>
                            <span class="rounded-full bg-forest-100 px-3 py-1 text-xs font-bold text-forest-700">${data.requests.length} found</span>
                        </div>
                        <div class="mt-4 space-y-2">
                            ${data.requests.map(req=>`<button type="button" data-ref="${esc(req.request_id)}" class="w-full text-left rounded-xl bg-white border border-forest-100 px-4 py-3 transition hover:ring-2 hover:ring-forest-400">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-mono font-bold">${esc(req.request_id)}</span>
                                    <span class="text-xs text-forest-500">${esc(fmtDate(req.submitted_at))}</span>
                                </div>
                                <div class="mt-2 flex flex-wrap gap-2">${trackBadges(req.parts)}</div>
                            </button>`).join('')}
                        </div>
                    </div>`;
            } catch (err) {
                trackMsg.textContent = 'Network error. Please check your connection and try again.';
            } finally {
                btn.disabled = false;
            }
        };

        trackResult.addEventListener('click', e => {
            const row = e.target.closest('[data-ref]');
            if (!row) return;
            document.getElementById('trackRef').value = row.dataset.ref;
            trackForm.requestSubmit();
        });

        // ---------- Scroll reveal + counters ----------
        const io = new IntersectionObserver(es => es.forEach(x => {
            if (x.isIntersecting) {
                x.target.classList.add('show');
                io.unobserve(x.target);
            }
        }), {
            threshold: .15
        });
        document.querySelectorAll('.reveal').forEach(el => io.observe(el));

        // Count-up stats only once they scroll into view.
        const countIO = new IntersectionObserver(es => es.forEach(x => {
            if (!x.isIntersecting) return;
            const el = x.target,
                end = +el.dataset.count;
            let n = 0;
            const step = Math.max(1, Math.round(end / 18));
            const s = setInterval(() => {
                n = Math.min(end, n + step);
                el.textContent = n;
                if (n >= end) clearInterval(s);
            }, 55);
            countIO.unobserve(el);
        }), {
            threshold: .5
        });
        document.querySelectorAll('[data-count]').forEach(el => countIO.observe(el));

        // ---------- Preloader ----------
        const preloader = document.getElementById('preloader');
        const hidePreloader = () => preloader && preloader.classList.add('done');
        document.addEventListener('DOMContentLoaded', () => setTimeout(hidePreloader, 800));
        window.addEventListener('load', () => setTimeout(hidePreloader, 400));
        setTimeout(hidePreloader, 2800); // safety net if a resource stalls

        // ---------- Header shadow + subtle hero parallax ----------
        const header = document.getElementById('siteHeader');
        const progress = document.getElementById('scrollProgress');
        const parallaxEls = [...document.querySelectorAll('[data-parallax]')];
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function onScroll() {
            if (header) header.classList.toggle('hdr-scrolled', window.scrollY > 8);
            if (progress) {
                const max = document.documentElement.scrollHeight - window.innerHeight;
                progress.style.width = (max > 0 ? Math.min(100, window.scrollY / max * 100) : 0) + '%';
            }
            if (reduceMotion) return;
            parallaxEls.forEach(el => {
                const speed = parseFloat(el.dataset.parallax) || 0;
                const y = Math.min(window.scrollY, 900) * speed;
                el.style.transform = `translate3d(0, ${y}px, 0)`;
            });
        }
        window.addEventListener('scroll', onScroll, {
            passive: true
        });
        onScroll();
    </script>
</body>

</html>