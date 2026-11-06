<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="">
    <title>UCO-TMS · Submit a Request</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script>
        tailwind.config = {
            darkMode: 'media'
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
            scroll-padding-top: env(safe-area-inset-top, 0px)
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1)
            }

            50% {
                transform: translateY(-30px) scale(1.08)
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

        .blob {
            animation: float 9s ease-in-out infinite
        }

        .gtext {
            background: linear-gradient(90deg, #6366f1, #ec4899, #f59e0b, #6366f1);
            background-size: 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: grad 6s linear infinite
        }

        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: all .7s cubic-bezier(.2, .7, .2, 1)
        }

        .reveal.show {
            opacity: 1;
            transform: none
        }

        .modal-box {
            animation: pop .35s ease both
        }

        @media (prefers-reduced-motion:reduce) {
            * {
                animation: none !important;
                transition: none !important
            }

            .reveal {
                opacity: 1;
                transform: none
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-100 antialiased">

    <header class="sticky top-0 z-30 backdrop-blur bg-white/70 dark:bg-slate-950/70 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between">
            <a href="#" class="font-extrabold text-xl tracking-tight">UCO<span class="text-indigo-500">-TMS</span></a>
            <nav class="hidden sm:flex gap-6 text-sm font-medium">
                <a class="hover:text-indigo-500" href="#requests">Requests</a>
                <a class="hover:text-indigo-500" href="#how">How it works</a>
                <a class="hover:text-indigo-500" href="#track">Track</a>
            </nav>
            <a href="/login" class="rounded-full border border-slate-300 dark:border-slate-700 text-sm font-semibold px-4 py-2 transition hover:scale-105">Staff login</a>
        </div>
    </header>

    <section class="relative overflow-hidden">
        <div class="blob absolute -top-24 -left-24 w-96 h-96 rounded-full bg-indigo-400/30 blur-3xl"></div>
        <div class="blob absolute top-20 right-0 w-96 h-96 rounded-full bg-pink-400/30 blur-3xl" style="animation-delay:-3s"></div>
        <div class="blob absolute bottom-0 left-1/3 w-80 h-80 rounded-full bg-amber-300/30 blur-3xl" style="animation-delay:-6s"></div>
        <div class="relative max-w-4xl mx-auto px-5 py-24 sm:py-32 text-center">
            <span class="reveal inline-block rounded-full border border-indigo-300 dark:border-indigo-700 bg-white/60 dark:bg-slate-900/60 px-4 py-1 text-xs font-semibold text-indigo-600 dark:text-indigo-300">University Communication Office</span>
            <h1 class="reveal mt-6 text-4xl sm:text-6xl font-extrabold leading-tight" style="transition-delay:.1s">Need something from UCO?<br><span class="gtext">Request it in minutes.</span></h1>
            <p class="reveal mt-6 text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto" style="transition-delay:.2s">Choose a request type, fill out the form, and get a reference number to follow your request.</p>
            <div class="reveal mt-10 flex flex-wrap justify-center gap-4" style="transition-delay:.3s">
                <a href="#requests" class="rounded-xl bg-indigo-600 text-white font-semibold px-7 py-3 shadow-lg shadow-indigo-500/30 hover:-translate-y-1 transition">Choose a request</a>
                <a href="#track" class="rounded-xl border border-slate-300 dark:border-slate-700 font-semibold px-7 py-3 hover:-translate-y-1 transition">Track my request</a>
            </div>
            <div class="reveal mt-14 grid grid-cols-3 gap-4 max-w-xl mx-auto" style="transition-delay:.4s">
                <div>
                    <div class="text-3xl font-extrabold" data-count="5">0</div>
                    <div class="text-xs text-slate-500">Request types</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold" data-count="7">0</div>
                    <div class="text-xs text-slate-500">Days AVP production time</div>
                </div>
                <div>
                    <div class="text-3xl font-extrabold">24/7</div>
                    <div class="text-xs text-slate-500">Submit anytime</div>
                </div>
            </div>
        </div>
    </section>

    <section id="requests" class="max-w-6xl mx-auto px-5 py-20">
        <h2 class="reveal text-3xl font-extrabold text-center">What do you need?</h2>
        <p class="reveal text-center text-slate-500 mt-2">Select a request type to open its form.</p>
        <div id="cards" class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </section>

    <section id="how" class="bg-white dark:bg-slate-900 border-y border-slate-200 dark:border-slate-800">
        <div class="max-w-6xl mx-auto px-5 py-20">
            <h2 class="reveal text-3xl font-extrabold text-center">How it works</h2>
            <div class="mt-12 grid md:grid-cols-4 gap-8 text-center">
                <div class="reveal">
                    <div class="mx-auto w-14 h-14 rounded-full bg-indigo-100 dark:bg-indigo-950 text-indigo-600 grid place-items-center text-xl font-bold">1</div>
                    <h3 class="mt-4 font-bold">Choose</h3>
                    <p class="text-sm text-slate-500 mt-1">Pick the request type.</p>
                </div>
                <div class="reveal" style="transition-delay:.1s">
                    <div class="mx-auto w-14 h-14 rounded-full bg-pink-100 dark:bg-pink-950 text-pink-600 grid place-items-center text-xl font-bold">2</div>
                    <h3 class="mt-4 font-bold">Fill the form</h3>
                    <p class="text-sm text-slate-500 mt-1">Use your official UZ email.</p>
                </div>
                <div class="reveal" style="transition-delay:.2s">
                    <div class="mx-auto w-14 h-14 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-600 grid place-items-center text-xl font-bold">3</div>
                    <h3 class="mt-4 font-bold">Get a reference no.</h3>
                    <p class="text-sm text-slate-500 mt-1">Save it to follow up.</p>
                </div>
                <div class="reveal" style="transition-delay:.3s">
                    <div class="mx-auto w-14 h-14 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 grid place-items-center text-xl font-bold">4</div>
                    <h3 class="mt-4 font-bold">Track</h3>
                    <p class="text-sm text-slate-500 mt-1">See the status until it’s done.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="track" class="max-w-2xl mx-auto px-5 py-20 text-center">
        <h2 class="reveal text-3xl font-extrabold">Track your request</h2>
        <form id="trackForm" class="reveal mt-8 flex gap-3">
            <input required placeholder="e.g. TMS-2026-1A2B3C" class="flex-1 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-3 outline-none focus:ring-2 focus:ring-indigo-500">
            <button class="rounded-xl bg-indigo-600 text-white font-semibold px-6">Track</button>
        </form>
        <p id="trackMsg" class="mt-4 text-sm text-slate-500"></p>
    </section>

    <footer class="text-center text-sm text-slate-500 py-8 border-t border-slate-200 dark:border-slate-800">© 2026 UCO-TMS. All rights reserved.</footer>

    <div id="modal" class="hidden fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm overflow-y-auto p-4">
        <div class="modal-box max-w-3xl mx-auto my-6 rounded-2xl bg-white dark:bg-slate-900 shadow-2xl p-6 sm:p-8">
            <div class="flex items-start justify-between">
                <div>
                    <div id="mIcon" class="text-3xl"></div>
                    <h3 id="mTitle" class="text-2xl font-extrabold mt-1"></h3>
                    <p class="text-sm text-slate-500">Submit a request to the UCO team</p>
                </div>
                <button id="mClose" class="text-3xl leading-none text-slate-400 hover:text-slate-700 dark:hover:text-white" aria-label="Close">×</button>
            </div>
            <form id="mForm" enctype="multipart/form-data" class="mt-6 grid grid-cols-12 gap-4"></form>
            <div id="mDone" class="hidden text-center py-8">
                <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 grid place-items-center text-3xl">✓</div>
                <h4 class="mt-4 text-xl font-bold">Request submitted!</h4>
                <p class="text-slate-500 mt-1">Your reference number:</p>
                <p id="mRef" class="mt-2 inline-block rounded-lg bg-slate-100 dark:bg-slate-800 px-4 py-2 font-mono font-bold"></p>
            </div>
        </div>
    </div>

    <script>
        const DEMO = true; // set to false in Laravel so forms are really sent to the server

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

        // ---------- The five request forms (copied from your Blade files) ----------
        const TYPES = [{
                id: 'socmed',
                icon: '📱',
                color: 'indigo',
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
                icon: '🎨',
                color: 'pink',
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
                icon: '🏆',
                color: 'amber',
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
                icon: '™️',
                color: 'emerald',
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
                icon: '🎓',
                color: 'sky',
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
            indigo: 'bg-indigo-100 dark:bg-indigo-950',
            pink: 'bg-pink-100 dark:bg-pink-950',
            amber: 'bg-amber-100 dark:bg-amber-950',
            emerald: 'bg-emerald-100 dark:bg-emerald-950',
            sky: 'bg-sky-100 dark:bg-sky-950'
        };
        const esc = s => String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
        const cards = document.getElementById('cards');
        TYPES.forEach((t, i) => {
            const b = document.createElement('button');
            b.className = 'reveal group text-left rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm hover:shadow-xl hover:-translate-y-2 hover:border-indigo-400 transition-all duration-300';
            b.style.transitionDelay = (i % 3) * 0.1 + 's';
            b.innerHTML = `<div class="w-14 h-14 rounded-xl ${COLORS[t.color]} grid place-items-center text-3xl group-hover:rotate-12 group-hover:scale-110 transition">${t.icon}</div>
    <h3 class="mt-4 text-lg font-bold">${t.title}</h3><p class="mt-1 text-sm text-slate-500">${t.desc}</p>
    <span class="mt-4 inline-block text-sm font-semibold text-indigo-600 dark:text-indigo-400">Fill out form <span class="inline-block group-hover:translate-x-1 transition">→</span></span>`;
            b.onclick = () => openModal(t);
            cards.appendChild(b);
        });

        // ---------- Form builder: turns one field description into HTML ----------
        const IN = 'w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 outline-none focus:ring-2 focus:ring-indigo-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-1 file:text-indigo-700';

        function render(x) {
            const sh = x.show ? ` data-sn="${x.show[0]}" data-sv="${x.show[1]}"` : '';
            if (x.h) return `<div class="col-span-12 mt-3 border-b border-slate-200 dark:border-slate-700 pb-1"${sh}><h4 class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">${esc(x.h)}</h4>${x.s?`<p class="text-xs text-slate-500">${esc(x.s)}</p>`:''}</div>`;
            if (x.note) return `<div class="col-span-12 rounded-xl border border-amber-200 dark:border-amber-900 bg-amber-50 dark:bg-amber-950/40 p-3 text-sm"${sh}>${x.note}</div>`;
            const req = x.opt ? '' : 'required',
                ph = x.ph ? ` placeholder="${esc(x.ph)}"` : '';
            let c;
            if (x.t === 'textarea') c = `<textarea name="${x.n}" rows="3" ${req}${ph} class="${IN}"></textarea>`;
            else if (x.t === 'select') c = `<select name="${x.n}" ${req} class="${IN}">${x.noph?'':'<option value="" selected disabled>Select</option>'}${x.o.map(v=>{v=Array.isArray(v)?v:[v,v];return `<option value="${esc(v[0])}">${esc(v[1])}</option>`}).join('')}</select>`;
            else if (x.t === 'radio') c = `<div class="flex gap-6 pt-1">${x.o.map(v=>`<label class="inline-flex items-center gap-2 font-normal"><input type="radio" name="${x.n}" value="${v[0]}" ${req} ${v[0]===x.def?'checked':''} class="accent-indigo-600 w-4 h-4"> ${v[1]}</label>`).join('')}</div>`;
            else c = `<input type="${x.t}" name="${x.n}" ${req}${ph}${x.pat?` pattern="${esc(x.pat)}"`:''}${x.min?` min="${x.min}"`:''}${x.multi?' multiple':''}${x.acc?` accept="${x.acc}"`:''} class="${IN}">`;
            const star = x.opt ? '<span class="font-normal text-slate-400"> (optional)</span>' : '<span class="text-red-500"> *</span>';
            return `<label class="col-span-12 sm:col-span-${x.w} block text-sm font-semibold"${sh}>${esc(x.l)}${star}${x.hint?`<span class="block text-xs font-normal text-slate-500 mt-0.5">${esc(x.hint)}</span>`:''}<span class="block mt-1 font-normal">${c}</span></label>`;
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
            document.getElementById('mIcon').textContent = t.icon;
            document.getElementById('mTitle').textContent = t.title;
            form.innerHTML = t.fields.map(render).join('') +
                '<div class="col-span-12"><button class="w-full rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 transition hover:scale-[1.02]">Submit request</button></div>';
            form.classList.remove('hidden');
            done.classList.add('hidden');
            sync();
            form.onsubmit = async e => {
                e.preventDefault();
                let ref = 'TMS-2026-' + Math.random().toString(16).slice(2, 8).toUpperCase();
                if (!DEMO) {
                    const r = await fetch(t.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        }
                    });
                    if (!r.ok) {
                        alert('Please check the form and try again.');
                        return;
                    }
                    ref = (await r.json()).ref;
                }
                document.getElementById('mRef').textContent = ref;
                form.classList.add('hidden');
                done.classList.remove('hidden');
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
        document.getElementById('trackForm').onsubmit = e => {
            e.preventDefault();
            document.getElementById('trackMsg').textContent = 'Demo only: status lookup will come from your database.';
        };

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
        document.querySelectorAll('[data-count]').forEach(el => {
            const end = +el.dataset.count;
            let n = 0;
            const s = setInterval(() => {
                n += 1;
                el.textContent = n;
                if (n >= end) clearInterval(s);
            }, 120);
        });
    </script>
</body>

</html>