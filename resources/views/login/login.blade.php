import { useState, useEffect } from "react";

/* ------------------------------------------------------------------
   UCO-TMS  |  Wildcat themed login (React)
   Palette taken from the UZ Unidad artwork:
   deep green + lime, with magenta / orange / yellow / cyan accents.
------------------------------------------------------------------- */

const FLAGS = ["#ff8a00", "#ff2e97", "#7b3fe4", "#ffd400", "#00c8d7", "#8df01a"];

const css = `
@import url('https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Poppins:wght@400;500;600;700&display=swap');

.wc { --g1:#06361a; --g2:#0c5a28; --g3:#12782f; --lime:#8df01a; --pink:#ff2e97; --orange:#ff8a00;
      --yellow:#ffd400; --cyan:#00c8d7; --purple:#7b3fe4; --text:#16301f; --muted:#7a8a80;
      position:relative; min-height:100vh; overflow:hidden; display:grid; place-items:center;
      padding:90px 16px 150px; font-family:'Poppins',system-ui,sans-serif;
      background: radial-gradient(900px 600px at 15% 10%, var(--g3), transparent 60%),
                  radial-gradient(800px 600px at 90% 90%, #0e6a2c, transparent 60%), var(--g1); }
.wc *{ box-sizing:border-box; }

/* blobs */
.wc .blob{ position:absolute; border-radius:50%; background:rgba(141,240,26,.08); filter:blur(2px); transition:transform .3s ease-out; }
.wc .b1{ width:420px; height:420px; top:-120px; left:-100px; }
.wc .b2{ width:340px; height:340px; bottom:60px; right:-80px; background:rgba(255,46,151,.08); }
.wc .b3{ width:200px; height:200px; top:40%; left:8%; background:rgba(0,200,215,.07); }

/* bunting */
.wc .bunting{ position:absolute; top:0; left:0; right:0; height:70px; display:flex; justify-content:space-around; pointer-events:none; }
.wc .bunting::before{ content:""; position:absolute; top:6px; left:0; right:0; height:2px; background:rgba(255,255,255,.35); }
.wc .flag{ width:34px; height:44px; margin-top:6px; transform-origin:top center; clip-path:polygon(0 0,100% 0,50% 100%);
           animation:sway 3.4s ease-in-out infinite; }
@keyframes sway{ 0%,100%{ transform:rotate(-7deg);} 50%{ transform:rotate(7deg);} }

/* bottom waves */
.wc .waves{ position:absolute; left:0; bottom:0; width:200%; height:150px; animation:drift 18s linear infinite; pointer-events:none; }
.wc .waves.w2{ bottom:-18px; animation-duration:26s; animation-direction:reverse; opacity:.9; }
@keyframes drift{ to{ transform:translateX(-50%);} }

/* card */
.wc .card{ position:relative; z-index:2; width:100%; max-width:410px; margin-top:70px; padding:84px 36px 30px;
           background:#fff; border-radius:24px; box-shadow:0 30px 70px rgba(0,0,0,.35);
           animation:rise .9s cubic-bezier(.2,.8,.2,1) both; }
@keyframes rise{ from{ opacity:0; transform:translateY(40px) scale(.96);} to{ opacity:1; transform:none;} }
.wc .card::before{ content:""; position:absolute; inset:0; border-radius:24px; padding:3px; pointer-events:none;
   background:linear-gradient(120deg,var(--lime),var(--cyan),var(--pink),var(--orange),var(--lime)); background-size:300% 300%;
   -webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0); -webkit-mask-composite:xor; mask-composite:exclude;
   animation:hue 8s linear infinite; }
@keyframes hue{ to{ background-position:300% 0; } }

/* stamp logo */
.wc .stamp{ position:absolute; top:-70px; left:50%; width:140px; height:140px; margin-left:-70px; animation:bob 4s ease-in-out infinite; }
@keyframes bob{ 0%,100%{ transform:translateY(0);} 50%{ transform:translateY(-8px);} }
.wc .stamp .ring{ transform-origin:center; animation:spin 40s linear infinite; }
@keyframes spin{ to{ transform:rotate(360deg);} }

.wc h1{ font-family:'Permanent Marker',cursive; font-weight:400; font-size:34px; line-height:1; text-align:center; color:var(--g2); margin-bottom:4px; }
.wc h1 span{ color:var(--lime); text-shadow:1px 1px 0 var(--g1), -1px -1px 0 var(--g1), 1px -1px 0 var(--g1), -1px 1px 0 var(--g1); }
.wc .sub{ text-align:center; color:var(--muted); font-size:13px; margin-bottom:24px; }

.wc .alert{ background:#fff0f4; color:#c01558; border:1px solid #ffc4d8; border-radius:10px; padding:10px 12px; font-size:13px; margin-bottom:16px; animation:shake .4s; }
@keyframes shake{ 25%{ transform:translateX(-5px);} 75%{ transform:translateX(5px);} }

.wc .field{ margin-bottom:16px; }
.wc label.lbl{ display:block; font-size:11px; font-weight:600; letter-spacing:.08em; text-transform:uppercase; color:var(--muted); margin-bottom:6px; }
.wc .wrap{ position:relative; }
.wc .wrap svg.ic{ position:absolute; left:14px; top:50%; width:18px; height:18px; transform:translateY(-50%); stroke:var(--muted); fill:none; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; transition:stroke .2s; }
.wc .wrap:focus-within svg.ic{ stroke:var(--g3); }
.wc input[type=email], .wc input[type=password], .wc input[type=text]{
   width:100%; height:48px; padding:0 44px 0 42px; font:inherit; font-size:14px; color:var(--text);
   background:#f5f8f6; border:2px solid #e3ebe6; border-radius:12px; outline:none; transition:.2s; }
.wc input:focus{ background:#fff; border-color:var(--lime); box-shadow:0 0 0 4px rgba(141,240,26,.25); }
.wc .invalid input{ border-color:var(--pink); }
.wc .err{ color:var(--pink); font-size:12px; margin-top:5px; }
.wc .eye{ position:absolute; right:6px; top:50%; transform:translateY(-50%); width:36px; height:36px; border:0; background:none; border-radius:8px; cursor:pointer; color:var(--muted); display:grid; place-items:center; }
.wc .eye:hover{ background:#eef3f0; color:var(--text); }
.wc .eye svg{ width:18px; height:18px; stroke:currentColor; fill:none; stroke-width:2; stroke-linecap:round; stroke-linejoin:round; }

.wc .row{ display:flex; justify-content:space-between; align-items:center; font-size:13px; margin:2px 0 22px; }
.wc .check{ display:flex; gap:8px; align-items:center; color:var(--muted); cursor:pointer; }
.wc .check input{ accent-color:var(--g3); width:16px; height:16px; }
.wc .row a{ color:var(--g3); font-weight:600; text-decoration:none; }
.wc .row a:hover{ text-decoration:underline; }

.wc .btn{ position:relative; overflow:hidden; width:100%; height:52px; border:0; border-radius:12px; cursor:pointer;
          font:inherit; font-weight:700; letter-spacing:.12em; text-transform:uppercase; font-size:14px; color:var(--g1);
          background:linear-gradient(135deg,var(--lime),#c6ff4d); box-shadow:0 10px 24px rgba(141,240,26,.45); transition:.2s; }
.wc .btn:hover{ transform:translateY(-2px); box-shadow:0 14px 30px rgba(141,240,26,.6); }
.wc .btn::after{ content:""; position:absolute; top:0; left:-60%; width:40%; height:100%; background:linear-gradient(90deg,transparent,rgba(255,255,255,.65),transparent); transform:skewX(-20deg); transition:left .6s; }
.wc .btn:hover::after{ left:130%; }
.wc .btn:disabled{ opacity:.85; cursor:wait; transform:none; }
.wc .spin{ width:20px; height:20px; border:3px solid rgba(6,54,26,.25); border-top-color:var(--g1); border-radius:50%; animation:spin .7s linear infinite; margin:auto; }
.wc .foot{ text-align:center; font-size:12px; color:var(--muted); margin-top:22px; }

@media (prefers-reduced-motion:reduce){ .wc *{ animation:none !important; transition:none !important; } }
`;

/* Stylised wildcat head (replace with your real logo: <img src="/images/wildcat.png" />) */
function Wildcat() {
  return (
    <svg className="stamp" viewBox="0 0 140 140" aria-label="UZ Wildcat">
      <circle cx="70" cy="70" r="64" fill="#fff" />
      <circle className="ring" cx="70" cy="70" r="64" fill="none" stroke="#e9f2ec" strokeWidth="9"
              strokeLinecap="round" strokeDasharray="0.1 10" />
      <defs>
        <linearGradient id="tail" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0" stopColor="#ffd400" /><stop offset=".35" stopColor="#ff8a00" />
          <stop offset=".65" stopColor="#ff2e97" /><stop offset="1" stopColor="#7b3fe4" />
        </linearGradient>
      </defs>
      <path d="M92 26c20 10 22 34 4 46-14 9-6 22 6 28-18 4-28-8-22-22 4-10 20-12 16-28-2-8-8-12-4-24z" fill="url(#tail)" />
      <path d="M22 78l14-22-3-14 18 6 20-14 30 8 16-12-6 26-16 14-26 8-12 14-30 2z" fill="#0c5a28" />
      <path d="M52 68l26-9-6 12z" fill="#8df01a" />
    </svg>
  );
}

const Icon = ({ d, extra }) => (
  <svg className="ic" viewBox="0 0 24 24">{d}{extra}</svg>
);

export default function WildcatLogin({ action = "/login", forgotUrl = null }) {
  const [form, setForm] = useState({ email: "", password: "", remember: false });
  const [showPw, setShowPw] = useState(false);
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState("");

  // subtle mouse parallax on blobs
  useEffect(() => {
    const move = (e) => {
      const x = (e.clientX / window.innerWidth - 0.5) * 30;
      const y = (e.clientY / window.innerHeight - 0.5) * 30;
      document.querySelectorAll(".wc .blob").forEach((b, i) => {
        b.style.transform = `translate(${x * (i + 1) * 0.6}px, ${y * (i + 1) * 0.6}px)`;
      });
    };
    window.addEventListener("mousemove", move);
    return () => window.removeEventListener("mousemove", move);
  }, []);

  const onChange = (e) => {
    const { name, value, type, checked } = e.target;
    setForm((f) => ({ ...f, [name]: type === "checkbox" ? checked : value }));
    setErrors((er) => ({ ...er, [name]: undefined }));
  };

  const submit = async (e) => {
    e.preventDefault();
    setMessage("");
    const local = {};
    if (!form.email) local.email = "Email is required.";
    if (!form.password) local.password = "Password is required.";
    if (Object.keys(local).length) return setErrors(local);

    setLoading(true);
    try {
      const token = document.querySelector('meta[name="csrf-token"]')?.content;
      const res = await fetch(action, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": token,
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
        body: JSON.stringify(form),
      });

      if (res.redirected) return (window.location.href = res.url); // success
      if (res.status === 422) {
        const data = await res.json();
        setErrors(Object.fromEntries(Object.entries(data.errors || {}).map(([k, v]) => [k, v[0]])));
        setMessage(data.message || "");
      } else if (res.ok) {
        window.location.reload();
      } else {
        setMessage("Something went wrong. Please try again.");
      }
    } catch {
      setMessage("Network error. Please check your connection.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="wc">
      <style>{css}</style>

      <div className="blob b1" /><div className="blob b2" /><div className="blob b3" />

      <div className="bunting">
        {Array.from({ length: 22 }).map((_, i) => (
          <span key={i} className="flag" style={{ background: FLAGS[i % FLAGS.length], animationDelay: `${(i % 6) * -0.5}s` }} />
        ))}
      </div>

      <svg className="waves" viewBox="0 0 1440 150" preserveAspectRatio="none">
        <path fill="#ff2e97" d="M0 70c120-50 240 40 360 0s240-60 360-10 240 50 360 0 240-50 360 0v90H0z" />
        <path fill="#ff8a00" d="M0 95c120-40 240 30 360 0s240-40 360 0 240 40 360 0 240-35 360 0v55H0z" />
      </svg>
      <svg className="waves w2" viewBox="0 0 1440 150" preserveAspectRatio="none">
        <path fill="#00c8d7" opacity=".85" d="M0 115c120-30 240 20 360 0s240-30 360 0 240 30 360 0 240-25 360 0v35H0z" />
        <path fill="#8df01a" d="M0 135c140-20 260 10 380 0s260-20 380 0 260 20 300 0v15H0z" />
      </svg>

      <form className="card" onSubmit={submit} noValidate>
        <Wildcat />

        <h1>UZ <span>Wildcats</span></h1>
        <p className="sub">Sign in to the UCO-TMS dashboard</p>

        {message && <div className="alert">{message}</div>}

        <div className={`field ${errors.email ? "invalid" : ""}`}>
          <label className="lbl" htmlFor="email">Email address</label>
          <div className="wrap">
            <Icon d={<path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z" />} extra={<polyline points="22,6 12,13 2,6" />} />
            <input id="email" name="email" type="email" autoComplete="username" placeholder="you@uz.edu.ph"
                   value={form.email} onChange={onChange} autoFocus />
          </div>
          {errors.email && <div className="err">{errors.email}</div>}
        </div>

        <div className={`field ${errors.password ? "invalid" : ""}`}>
          <label className="lbl" htmlFor="password">Password</label>
          <div className="wrap">
            <Icon d={<rect x="3" y="11" width="18" height="11" rx="2" />} extra={<path d="M7 11V7a5 5 0 0 1 10 0v4" />} />
            <input id="password" name="password" type={showPw ? "text" : "password"} autoComplete="current-password"
                   placeholder="Enter your password" value={form.password} onChange={onChange} />
            <button type="button" className="eye" onClick={() => setShowPw((s) => !s)} aria-label={showPw ? "Hide password" : "Show password"}>
              <svg viewBox="0 0 24 24">
                {showPw
                  ? <><path d="M17.94 17.94A10.9 10.9 0 0 1 12 20c-7 0-11-8-11-8a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A9.1 9.1 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19" /><line x1="1" y1="1" x2="23" y2="23" /></>
                  : <><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" /></>}
              </svg>
            </button>
          </div>
          {errors.password && <div className="err">{errors.password}</div>}
        </div>

        <div className="row">
          <label className="check"><input type="checkbox" name="remember" checked={form.remember} onChange={onChange} /> Remember me</label>
          {forgotUrl && <a href={forgotUrl}>Forgot password?</a>}
        </div>

        <button className="btn" type="submit" disabled={loading}>
          {loading ? <div className="spin" /> : "Sign in"}
        </button>

        <p className="foot">&copy; {new Date().getFullYear()} UCO-TMS &middot; Go Wildcats!</p>
      </form>
    </div>
  );
}