<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in | UCO-TMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { --green:#06361a; --green2:#0c5a28; --lime:#8df01a; --pink:#ff2e97; --cyan:#00c8d7; --orange:#ff8a00; margin:0; min-height:100vh; display:grid; place-items:center; overflow:hidden; padding:80px 16px 120px; font-family:'Poppins',system-ui,sans-serif; background:radial-gradient(900px 600px at 15% 10%,#12782f,transparent 60%),radial-gradient(800px 600px at 90% 90%,#0e6a2c,transparent 60%),var(--green); }
        .blob { position:fixed; width:340px; height:340px; border-radius:50%; background:rgba(141,240,26,.08); filter:blur(2px); top:-110px; left:-90px; pointer-events:none; }
        .blob.alt { width:280px; height:280px; top:auto; left:auto; right:-90px; bottom:40px; background:rgba(255,46,151,.08); }
        .bunting { position:fixed; z-index:0; top:0; left:0; right:0; height:58px; display:flex; justify-content:space-around; pointer-events:none; }
        .bunting:before { content:""; position:absolute; top:6px; left:0; right:0; height:2px; background:rgba(255,255,255,.35); }
        .flag { width:28px; height:38px; margin-top:6px; clip-path:polygon(0 0,100% 0,50% 100%); animation:sway 3.4s ease-in-out infinite; }
        @keyframes sway { 0%,100%{transform:rotate(-7deg)} 50%{transform:rotate(7deg)} }
        .waves { position:fixed; z-index:0; left:0; bottom:0; width:200%; height:120px; pointer-events:none; animation:drift 18s linear infinite; }
        @keyframes drift { to{transform:translateX(-50%)} }
        .card { position:relative; z-index:1; width:min(100%,410px); margin-top:50px; padding:76px 36px 28px; background:#fff; border-radius:24px; box-shadow:0 30px 70px rgba(0,0,0,.35); animation:rise .7s ease both; }
        .card:before { content:""; position:absolute; inset:0; border:3px solid transparent; border-radius:24px; pointer-events:none; background:linear-gradient(120deg,var(--lime),var(--cyan),var(--pink),var(--orange)) border-box; -webkit-mask:linear-gradient(#000 0 0) padding-box,linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude; }
        @keyframes rise { from{opacity:0;transform:translateY(25px)} to{opacity:1;transform:none} }
        .stamp { position:absolute; top:-62px; left:50%; width:124px; height:124px; transform:translateX(-50%); }
        h1 { margin:0; text-align:center; color:var(--green2); font:400 32px/1.2 'Permanent Marker',cursive; }
        h1 span { color:var(--lime); text-shadow:1px 1px 0 var(--green),-1px -1px 0 var(--green),1px -1px 0 var(--green),-1px 1px 0 var(--green); }
        .sub { margin:8px 0 24px; text-align:center; color:#7a8a80; font-size:13px; }
        .alert { margin-bottom:16px; padding:10px 12px; border:1px solid #ffc4d8; border-radius:10px; background:#fff0f4; color:#c01558; font-size:13px; }
        .field { margin-bottom:16px; }
        label.lbl { display:block; margin-bottom:6px; color:#7a8a80; font-size:11px; font-weight:600; letter-spacing:.08em; text-transform:uppercase; }
        .field input[type=email],.field input[type=password] { width:100%; height:48px; padding:0 14px; border:2px solid #e3ebe6; border-radius:12px; outline:none; background:#f5f8f6; color:#16301f; font:inherit; font-size:14px; }
        .field input:focus { border-color:var(--lime); box-shadow:0 0 0 4px rgba(141,240,26,.2); }
        .error { margin-top:5px; color:var(--pink); font-size:12px; }
        .row { display:flex; align-items:center; justify-content:space-between; margin:2px 0 22px; color:#7a8a80; font-size:13px; }
        .check { display:flex; align-items:center; gap:8px; cursor:pointer; }
        .check input { width:16px; height:16px; accent-color:#12782f; }
        .btn { width:100%; height:52px; border:0; border-radius:12px; background:linear-gradient(135deg,var(--lime),#c6ff4d); color:var(--green); box-shadow:0 10px 24px rgba(141,240,26,.4); cursor:pointer; font:700 14px 'Poppins',sans-serif; letter-spacing:.12em; text-transform:uppercase; }
        .btn:hover { transform:translateY(-2px); }
        .foot { margin:22px 0 0; color:#7a8a80; text-align:center; font-size:12px; }
        @media (max-width:480px) { .card { padding:72px 24px 26px; } }
        @media (prefers-reduced-motion:reduce) { *,*:before { animation:none!important; transition:none!important; } }
    </style>
</head>
<body>
    <div class="blob"></div><div class="blob alt"></div>
    <div class="bunting" aria-hidden="true">
        @foreach (['#ff8a00','#ff2e97','#7b3fe4','#ffd400','#00c8d7','#8df01a'] as $color)
            @for ($i = 0; $i < 4; $i++)
                <span class="flag" style="background: {{ $color }}; animation-delay: {{ -$i * .5 }}s"></span>
            @endfor
        @endforeach
    </div>
    <svg class="waves" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true"><path fill="#ff2e97" d="M0 55c120-40 240 35 360 0s240-45 360-5 240 40 360 0 240-40 360 0v70H0z"/><path fill="#ff8a00" d="M0 80c120-30 240 25 360 0s240-30 360 0 240 30 360 0 240-25 360 0v45H0z"/></svg>

    <main class="card">
        <svg class="stamp" viewBox="0 0 140 140" role="img" aria-label="UZ Wildcat">
            <circle cx="70" cy="70" r="64" fill="#fff"/><circle cx="70" cy="70" r="64" fill="none" stroke="#e9f2ec" stroke-width="9" stroke-dasharray=".1 10" stroke-linecap="round"/>
            <defs><linearGradient id="tail" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffd400"/><stop offset=".35" stop-color="#ff8a00"/><stop offset=".65" stop-color="#ff2e97"/><stop offset="1" stop-color="#7b3fe4"/></linearGradient></defs>
            <path d="M92 26c20 10 22 34 4 46-14 9-6 22 6 28-18 4-28-8-22-22 4-10 20-12 16-28-2-8-8-12-4-24z" fill="url(#tail)"/><path d="M22 78l14-22-3-14 18 6 20-14 30 8 16-12-6 26-16 14-26 8-12 14-30 2z" fill="#0c5a28"/><path d="M52 68l26-9-6 12z" fill="#8df01a"/>
        </svg>
        <h1>UZ <span>Wildcats</span></h1>
        <p class="sub">Sign in to the UCO-TMS dashboard</p>

        @if ($errors->any())
            <div class="alert" role="alert">{{ $errors->first() }}</div>
        @endif
        @if (session('status'))
            <div class="alert" role="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="field">
                <label class="lbl" for="email">Email address</label>
                <input id="email" name="email" type="email" autocomplete="username" placeholder="you@uz.edu.ph" value="{{ old('email') }}" required autofocus>
                @error('email')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label class="lbl" for="password">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required>
                @error('password')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="row"><label class="check"><input type="checkbox" name="remember" value="1" @checked(old('remember'))> Remember me</label></div>
            <button class="btn" type="submit">Sign in</button>
        </form>
        <p class="foot">&copy; {{ date('Y') }} UCO-TMS &middot; Go Wildcats!</p>
    </main>
</body>
</html>
