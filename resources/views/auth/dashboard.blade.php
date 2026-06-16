{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="{{ session('lang', 'fr') }}" dir="{{ session('lang', 'fr') === 'ar' ? 'rtl' : 'ltr' }}">
 
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EtudServ – Tableau de bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap');
 
        * { margin: 0; padding: 0; box-sizing: border-box; }
 
        :root {
            --blue:   #52D6F4;
            --blue2:  #39C6E9;
            --dark:   #1B2559;
            --purple: #7B61FF;
            --green:  #45C486;
            --bg:     #F4F7FB;
            --white:  #ffffff;
            --gray:   #8A94A6;
            --border: #E5E7EB;
        }
 
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--dark);
        }
        [lang="ar"] body, [lang="ar"] * { font-family: 'Cairo', sans-serif !important; }
 
        /* =========================================================
           NAVBAR
        ========================================================= */
        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 60px;
            height: 68px;
            background: var(--white);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 0 var(--border);
        }
 
        .logo { font-size: 24px; font-weight: 700; text-decoration: none; }
        .logo-blue { color: var(--blue2); }
        .logo-dark { color: var(--dark); font-weight: 500; }
 
        /* Nav center links */
        .nav-center {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-center a {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #4B5563;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .nav-center a:hover { background: #F4F7FB; color: var(--dark); }
        .nav-center a.active { color: var(--blue2); font-weight: 600; }
 
        /* Nav right */
        .nav-right { display: flex; align-items: center; gap: 16px; }
 
        /* Language toggle pill */
        .lang-toggle {
            display: flex;
            align-items: center;
            background: #F4F7FB;
            border-radius: 30px;
            padding: 3px 4px;
            gap: 2px;
        }
        .lang-btn {
            padding: 5px 13px;
            border: none;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            background: transparent;
            color: var(--gray);
            font-family: inherit;
            transition: 0.2s;
        }
        .lang-btn.active {
            background: var(--blue);
            color: white;
            box-shadow: 0 3px 10px rgba(82,214,244,0.35);
        }
 
        /* Notification bell */
        .nav-bell {
            width: 38px; height: 38px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
            color: #6B7280;
            cursor: pointer;
            background: transparent;
            border: none;
            position: relative;
            transition: background 0.2s;
        }
        .nav-bell:hover { background: #F4F7FB; color: var(--dark); }
        .bell-dot {
            position: absolute;
            top: 7px; right: 7px;
            width: 8px; height: 8px;
            background: #EF4444;
            border-radius: 50%;
            border: 2px solid white;
        }
 
        /* User avatar + dropdown */
        .user-menu { position: relative; }
        .user-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--purple));
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 700; color: white;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s;
        }
        .user-avatar:hover { border-color: var(--blue); }
 
        .user-dropdown {
            display: none;
            position: absolute;
            top: 48px; right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            min-width: 210px;
            padding: 8px 0;
            z-index: 200;
            border: 1px solid var(--border);
        }
        .user-dropdown.open { display: block; }
        .dd-header { padding: 12px 18px 10px; border-bottom: 1px solid var(--border); }
        .dd-name { font-size: 14px; font-weight: 700; color: var(--dark); }
        .dd-role {
            display: inline-block;
            margin-top: 4px;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 20px;
            background: #EAFBFF;
            color: var(--blue2);
        }
        .dd-email { font-size: 12px; color: var(--gray); margin-top: 3px; }
        .user-dropdown a,
        .user-dropdown button.dd-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            font-size: 13.5px;
            color: var(--dark);
            text-decoration: none;
            transition: background 0.15s;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
        }
        .user-dropdown a:hover,
        .user-dropdown button.dd-btn:hover { background: #F4F7FB; }
        .user-dropdown .dd-logout { color: #EF4444 !important; border-top: 1px solid var(--border); margin-top: 4px; }
        .user-dropdown i { width: 17px; text-align: center; font-size: 14px; color: var(--gray); }
        .user-dropdown .dd-logout i { color: #EF4444; }
 
        @media(max-width:900px) {
            .navbar { padding: 0 20px; }
            .nav-center { display: none; }
        }
 
        /* =========================================================
           WELCOME HEADER  (like Fiverr "Welcome to Fiverr, ahmed")
        ========================================================= */
        .welcome-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 50%, #3a3a5c 100%);
            border-bottom: none;
            padding: 36px 60px 32px;
            position: relative;
            overflow: hidden;
        }
        .welcome-header::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .welcome-header::after {
            content: '';
            position: absolute;
            bottom: -30px; left: 25%;
            width: 150px; height: 150px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
        }
        .welcome-header-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }
        .welcome-text-block { flex: 1; }
        .welcome-eyebrow {
            font-size: 12px;
            font-weight: 600;
            color: rgba(82,214,244,0.85);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .welcome-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .welcome-header h1 span { color: #52D6F4; }
        .welcome-header .welcome-sub {
            font-size: 14px;
            color: rgba(255,255,255,0.55);
            max-width: 520px;
            line-height: 1.6;
        }
        .welcome-badge-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
            flex-wrap: wrap;
        }
        .w-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .w-badge-green  { background: rgba(69,196,134,0.15); color: #6EFFC0; border: 1px solid rgba(69,196,134,0.25); }
        .w-badge-blue   { background: rgba(82,214,244,0.12); color: #52D6F4; border: 1px solid rgba(82,214,244,0.25); }
        .w-badge-purple { background: rgba(123,97,255,0.15); color: #B49EFF; border: 1px solid rgba(123,97,255,0.25); }
 
        @media(max-width:768px) {
            .welcome-header { padding: 24px 20px 20px; }
            .welcome-header h1 { font-size: 22px; }
        }
 
        /* =========================================================
           MAIN LAYOUT
        ========================================================= */
        .dashboard-layout {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 60px;
            display: grid;
            grid-template-columns: 1fr 285px;
            gap: 30px;
            align-items: start;
        }
        @media(max-width:1100px) {
            .dashboard-layout { grid-template-columns: 1fr; padding: 0 24px; }
        }
 
        /* ── Section labels ──────────────────────────────────── */
        .section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--blue2);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .rec-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 36px;
        }
        @media(max-width:700px) { .rec-grid { grid-template-columns: 1fr; } }
        @media(max-width:900px) { .rec-grid { grid-template-columns: 1fr 1fr; } }
 
        .rec-card {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 20px 18px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            cursor: pointer;
            transition: box-shadow 0.25s, transform 0.25s;
        }
        .rec-card:hover {
            box-shadow: 0 8px 28px rgba(57,198,233,0.12);
            transform: translateY(-3px);
            border-color: var(--blue);
        }
        .rec-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .rec-ic-blue   { background: #EAFBFF; color: var(--blue2); }
        .rec-ic-purple { background: #F2EEFF; color: var(--purple); }
        .rec-ic-green  { background: #EAFBF3; color: var(--green); }
        .rec-body { flex: 1; }
        .rec-eyebrow {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 4px;
        }
        .rec-title { font-size: 14px; font-weight: 700; color: var(--dark); margin-bottom: 4px; }
        .rec-desc  { font-size: 12px; color: var(--gray); line-height: 1.5; }
 
        /* ── Section headers ─────────────────────────────────── */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border);
        }
        .section-header h2 {
            font-size: 16px; font-weight: 700; color: var(--dark);
            display: flex; align-items: center; gap: 9px;
        }
        .section-header h2::before {
            content: '';
            display: inline-block;
            width: 4px; height: 18px;
            background: linear-gradient(180deg, var(--blue), var(--purple));
            border-radius: 4px;
        }
        .section-header a {
            font-size: 12px; color: var(--blue2); font-weight: 600;
            text-decoration: none; padding: 4px 12px;
            border-radius: 20px; border: 1px solid var(--border); transition: 0.2s;
        }
        .section-header a:hover { background: #EAFBFF; border-color: var(--blue); }
 
        .categories-row {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            margin-bottom: 36px;
        }
        @media(max-width:900px) { .categories-row { grid-template-columns: repeat(3, 1fr); } }
        @media(max-width:500px) { .categories-row { grid-template-columns: repeat(2, 1fr); } }
 
        .cat-card {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 18px 12px;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
        }
        .cat-card:hover {
            background: #EAFBFF;
            border-color: var(--blue);
            box-shadow: 0 4px 16px rgba(82,214,244,0.14);
            transform: translateY(-4px);
        }
        .cat-emoji { font-size: 28px; line-height: 1; }
        .cat-card span { font-size: 12px; font-weight: 600; color: var(--dark); }
 
        /* =========================================================
           FREELANCER CARDS
        ========================================================= */
        .freelancers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 18px;
            margin-bottom: 36px;
        }
 
        .fl-card {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 22px;
            transition: box-shadow 0.25s, transform 0.25s, border-color 0.25s;
            cursor: pointer;
            position: relative; overflow: hidden;
        }
        .fl-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--blue), var(--purple));
            opacity: 0; transition: opacity 0.25s;
        }
        .fl-card:hover { box-shadow: 0 10px 32px rgba(0,0,0,0.09); transform: translateY(-4px); border-color: transparent; }
        .fl-card:hover::before { opacity: 1; }
 
        .fl-head { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
        .fl-av {
            width: 48px; height: 48px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 19px; font-weight: 700; color: white; flex-shrink: 0;
        }
        .fl-meta h4 { font-size: 14px; font-weight: 700; color: var(--dark); }
        .fl-meta span { font-size: 12px; color: var(--gray); }
 
        .fl-badge {
            display: inline-block;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600; margin-bottom: 10px;
        }
        .badge-student { background: #EAFBFF; color: var(--blue2); }
        .badge-pro     { background: #F2EEFF; color: var(--purple); }
 
        .fl-skills { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 12px; }
        .fl-skill {
            padding: 3px 9px; background: #F4F7FB;
            border-radius: 7px; font-size: 11px; color: #6B7280; font-weight: 500;
        }
        .fl-desc { font-size: 12.5px; color: var(--gray); line-height: 1.6; margin-bottom: 14px; }
        .fl-footer-row {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 12px; border-top: 1px solid var(--border);
        }
        .fl-rate  { font-size: 12px; color: #F59E0B; font-weight: 600; }
        .fl-price { font-size: 14px; font-weight: 700; color: var(--blue2); }
 
        .btn-contact {
            width: 100%; margin-top: 12px; padding: 10px;
            border: none;
            background: linear-gradient(90deg, var(--blue2), var(--purple));
            color: white; border-radius: 10px; font-size: 13px; font-weight: 600;
            cursor: pointer; font-family: inherit; transition: 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 7px;
        }
        .btn-contact:hover { opacity: 0.88; transform: scale(0.98); }
 
        /* =========================================================
           SIDEBAR
        ========================================================= */
        .sidebar { display: flex; flex-direction: column; gap: 18px; }
 
        .sidebar-card {
            background: white;
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 22px;
        }
        .sidebar-card h3 {
            font-size: 13.5px; font-weight: 700; color: var(--dark);
            margin-bottom: 3px;
            display: flex; align-items: center; gap: 7px;
        }
        .sidebar-card h3 i { color: var(--blue2); font-size: 13px; }
        .sidebar-card .sc-sub { font-size: 11.5px; color: var(--gray); margin-bottom: 16px; }
 
        .progress-wrap { background: #EEF2F7; border-radius: 99px; height: 8px; margin-bottom: 6px; }
        .progress-fill {
            height: 100%; border-radius: 99px;
            background: linear-gradient(90deg, var(--blue), var(--purple));
            transition: width 1s ease;
        }
        .progress-pct { font-size: 11px; font-weight: 700; color: var(--blue2); text-align: right; margin-bottom: 14px; }
 
        .checklist { display: flex; flex-direction: column; gap: 7px; }
        .check-item {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 13px; border-radius: 12px;
            background: #F4F7FB; border: 1.5px solid transparent;
            font-size: 12.5px; color: var(--dark); font-weight: 500;
            cursor: pointer; transition: background 0.15s, border-color 0.15s;
        }
        .check-item:hover { background: #EAFBFF; border-color: var(--blue); }
        .check-item.done { color: var(--gray); text-decoration: line-through; background: #fafafa; }
        .ci-icon {
            width: 24px; height: 24px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; flex-shrink: 0;
        }
        .ci-done { background: #EAFBF3; color: var(--green); }
        .ci-todo { background: #E9ECF1; color: var(--gray); }
 
        /* Quick stats sidebar */
        .stats-mini { display: flex; flex-direction: column; gap: 12px; }
        .stat-mini-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 14px; background: #F8FCFD; border-radius: 12px;
        }
        .sml { display: flex; align-items: center; gap: 10px; }
        .sml i { font-size: 16px; }
        .sml-label { font-size: 13px; font-weight: 500; color: var(--dark); }
        .sml-val { font-size: 16px; font-weight: 800; color: var(--dark); }
 

 
        /* =========================================================
           FOOTER
        ========================================================= */
        .footer {
            background: var(--dark);
            color: white;
            padding: 28px 60px;
            margin-top: 48px;
            border-top: 3px solid var(--blue2);
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer-logo { font-size: 20px; font-weight: 700; }
        .footer-logo span { color: var(--blue); }
        .footer-copy { font-size: 12.5px; color: rgba(255,255,255,0.45); }
        .footer-made { font-size: 12.5px; color: rgba(255,255,255,0.45); }
        @media(max-width:600px) { .footer { padding: 22px 20px; } }
 
        /* =========================================================
           TOAST
        ========================================================= */
        .toast {
            position: fixed; bottom: 28px; right: 28px;
            background: var(--dark); color: white;
            padding: 13px 20px; border-radius: 12px;
            font-size: 14px; font-weight: 500;
            transform: translateY(80px); opacity: 0;
            transition: 0.4s; z-index: 9999;
            display: flex; align-items: center; gap: 8px;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
 
        /* =========================================================
           RTL
        ========================================================= */
        [dir="rtl"] .nav-right    { flex-direction: row-reverse; }
        [dir="rtl"] .nav-center   { flex-direction: row-reverse; }
        [dir="rtl"] .welcome-header-inner { flex-direction: row-reverse; }
        [dir="rtl"] .fl-head      { flex-direction: row-reverse; }
        [dir="rtl"] .rec-card     { flex-direction: row-reverse; }
        [dir="rtl"] .section-header { flex-direction: row-reverse; }
        [dir="rtl"] .fl-footer-row { flex-direction: row-reverse; }
        [dir="rtl"] .sml          { flex-direction: row-reverse; }
        [dir="rtl"] .check-item   { flex-direction: row-reverse; }
        [dir="rtl"] .stat-mini-row { flex-direction: row-reverse; }
        [dir="rtl"] .toast        { right: auto; left: 28px; }
        [dir="rtl"] .user-dropdown { right: auto; left: 0; }
        [dir="rtl"] .user-dropdown a,
        [dir="rtl"] .user-dropdown button.dd-btn { text-align: right; flex-direction: row-reverse; }
    </style>
</head>
 
<body>
 
{{-- ===================== NAVBAR ===================== --}}
<header class="navbar">
 
    <a href="{{ route('home') }}" class="logo">
        <span class="logo-blue">Etud</span><span class="logo-dark">Serv</span>
    </a>
 
    {{-- Centre nav links --}}
    <nav class="nav-center">
        <a href="{{ route('home') }}">
            {{ session('lang','fr') === 'ar' ? 'الرئيسية' : 'Accueil' }}
        </a>
        <a href="{{ route('home') }}" class="active">
            {{ session('lang','fr') === 'ar' ? 'لوحة التحكم' : 'Tableau de bord' }}
        </a>
        <a href="#mes-projets-section">
            {{ session('lang','fr') === 'ar' ? 'مشاريعي' : 'Mes projets' }}
        </a>
        <a href="{{ route('messagerie.index') }}" class="nav-icon-link" title="Messages" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
            <i class="fa-solid fa-envelope" style="font-size: 20px; color: var(--dark);"></i>
            <span style="color: var(--dark); font-weight: 500; font-size: 15px;">
                {{ session('lang','fr') === 'ar' ? 'الرسائل' : 'Messagerie' }}
            </span>
        </a>
        <a href="{{ route('contact') }}" class="nav-item-link" style="display: flex; align-items: center; gap: 8px; color: #1B2559; text-decoration: none; font-size: 14px; font-weight: 600; padding: 10px 16px; border-radius: 12px; transition: background 0.3s ease, color 0.3s ease; font-family: 'Poppins', 'Cairo', sans-serif;" onmouseover="this.style.background='rgba(123, 97, 255, 0.08)'; this.style.color='#7B61FF';" onmouseout="this.style.background='transparent'; this.style.color='#1B2559';">
            <i class="fa-solid fa-headset" style="font-size: 16px;"></i>
            <span>{{ session('lang','fr') === 'ar' ? 'اتصل بنا' : 'Contact' }}</span>
        </a>
    </nav>
 
    <div class="nav-right">
 
        {{-- Language toggle --}}
        <div class="lang-toggle">
            <form method="POST" action="{{ route('lang.switch') }}" style="display:inline">
                @csrf
                <input type="hidden" name="lang" value="fr">
                <button type="submit" class="lang-btn {{ session('lang','fr') === 'fr' ? 'active' : '' }}">FR</button>
            </form>
            <form method="POST" action="{{ route('lang.switch') }}" style="display:inline">
                @csrf
                <input type="hidden" name="lang" value="ar">
                <button type="submit" class="lang-btn {{ session('lang','fr') === 'ar' ? 'active' : '' }}">عربي</button>
            </form>
        </div>
 
        {{-- Notification bell --}}
        <button class="nav-bell" title="{{ session('lang','fr') === 'ar' ? 'الإشعارات' : 'Notifications' }}">
            <i class="fa-regular fa-bell"></i>
            <span class="bell-dot"></span>
        </button>
 
        {{-- User avatar + dropdown --}}
        <div class="user-menu">
            <div class="user-avatar" onclick="toggleDropdown()"
                 title="{{ session('utilisateur_nom', '') }}">
                {{ strtoupper(substr(session('utilisateur_nom', 'U'), 0, 1)) }}
            </div>
            <div class="user-dropdown" id="userDropdown">
                <div class="dd-header">
                    <div class="dd-name">
                        {{ session('utilisateur_nom', '') }}
                    </div>
                    <span class="dd-role">
                        @php $role = session('utilisateur_role', 'client'); @endphp
                        @if($role === 'freelancer')
                            ⚡ {{ session('lang','fr') === 'ar' ? 'مستقل' : 'Freelancer' }}
                        @elseif($role === 'client')
                            💼 {{ session('lang','fr') === 'ar' ? 'عميل' : 'Client' }}
                        @else
                            🎓 {{ session('lang','fr') === 'ar' ? 'طالب' : 'Étudiant' }}
                        @endif
                    </span>
                    <div class="dd-email">{{ session('utilisateur_email', '') }}</div>
                </div>
                <a href="{{ route('profil.show', session('utilisateur_id')) }}">
                    <i class="fa-regular fa-user"></i>
                    {{ session('lang','fr') === 'ar' ? 'ملفي الشخصي' : 'Mon profil' }}
                </a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('userDropdown').classList.remove('open'); document.getElementById('photoUploadModal').style.display='flex'">
                    <i class="fa-regular fa-image"></i>
                    {{ session('lang','fr') === 'ar' ? 'إضافة صورة شخصية' : 'Ajouter une photo' }}
                </a>
                <a href="#mes-projets-section">
                    <i class="fa-solid fa-briefcase"></i>
                    {{ session('lang','fr') === 'ar' ? 'مشاريعي' : 'Mes projets' }}
                </a>
                <div class="settings-dropdown-container" style="position: relative; display: inline-block; z-index: 9999;">
    <a href="javascript:void(0)" onclick="toggleSettingsDropdown(event)" style="text-decoration: none; display: flex; align-items: center; gap: 8px; color: inherit; cursor: pointer; position: relative; z-index: 10001; outline: none;">
        <i class="fa-solid fa-gear"></i>
        {{ session('lang','fr') === 'ar' ? 'الإعدادات' : 'Paramètres' }}
    </a>
    
    <div id="settingsDropdownMenu" style="display: none; position: absolute; right: 0; top: calc(100% + 12px); width: 220px; background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(27, 37, 89, 0.15); border: 1px solid rgba(229, 231, 235, 0.8); z-index: 10000; overflow: hidden; padding: 6px 0; text-align: left;">
        
        <a href="{{ route('profil.show', ['id' => session('utilisateur_id')]) }}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; color: #1B2559; text-decoration: none; font-size: 13px; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='rgba(123, 97, 255, 0.08)'" onmouseout="this.style.background='transparent'">
            <i class="fa-solid fa-user-gear" style="color: #7B61FF; font-size: 14px;"></i>
            <span>{{ session('lang','fr') === 'ar' ? 'عرض الملف الشخصي' : 'Mon Profil' }}</span>
        </a>
        
        <div onclick="toggleDarkModeEffect(event)" style="display: flex; align-items: center; justify-content: space-between; padding: 11px 16px; color: #1B2559; cursor: pointer; font-size: 13px; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='rgba(123, 97, 255, 0.08)'" onmouseout="this.style.background='transparent'">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-moon" style="color: #F59E0B; font-size: 14px;"></i>
                <span>{{ session('lang','fr') === 'ar' ? 'الوضع الداكن' : 'Mode Sombre' }}</span>
            </div>
            <div id="darkModeSwitch" style="width: 32px; height: 18px; background: #E5E7EB; border-radius: 20px; position: relative; transition: background 0.3s;">
                <div id="darkModeHandle" style="width: 12px; height: 12px; background: white; border-radius: 50%; position: absolute; top: 3px; left: 3px; transition: left 0.3s;"></div>
            </div>
        </div>
        
        <hr style="border: 0; border-top: 1px solid rgba(229, 231, 235, 0.6); margin: 5px 0;">
        
        <div onclick="openDeleteAccountModal()" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; color: #EF4444; cursor: pointer; font-size: 13px; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.08)'" onmouseout="this.style.background='transparent'">
            <i class="fa-solid fa-trash-can" style="font-size: 14px;"></i>
            <span>{{ session('lang','fr') === 'ar' ? 'حذف الحساب' : 'Supprimer le compte' }}</span>
        </div>
    </div>
</div>

<div id="deleteAccountModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(27, 37, 89, 0.4); z-index: 10005; align-items: center; justify-content: center; backdrop-filter: blur(3px);">
    <div style="background: #ffffff; width: 90%; max-width: 420px; padding: 25px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); text-align: center; border: 1px solid #E5E7EB;">
        <div style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1); color: #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 20px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h3 style="font-size: 17px; font-weight: 700; color: #1B2559; margin-bottom: 8px;">
            {{ session('lang','fr') === 'ar' ? 'هل أنت متأكد من حذف الحساب؟' : 'Supprimer définitivement le compte ?' }}
        </h3>
        <p style="font-size: 13px; color: #6B7280; margin-bottom: 20px; line-height: 1.5;">
            {{ session('lang','fr') === 'ar' ? 'هذا الإجراء نهائي. يرجى كتابة بريدك الإلكتروني للتأكيد.' : 'Cette opération est irréversible. Pour valider l\'action, veuillez saisir votre adresse e-mail.' }}
        </p>
        
        <form action="{{ route('users.delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="email" name="email" required placeholder="exemple@email.com" style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #E5E7EB; margin-bottom: 18px; font-size: 13px; text-align: center; outline: none;">
            
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" onclick="closeDeleteAccountModal()" style="padding: 9px 16px; background: #F3F4F6; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; color: #4B5563; cursor: pointer;">
                    {{ session('lang','fr') === 'ar' ? 'إلغاء' : 'Annuler' }}
                </button>
                <button type="submit" style="padding: 9px 16px; background: #EF4444; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; color: #ffffff; cursor: pointer;">
                    {{ session('lang','fr') === 'ar' ? 'تأكيد' : 'Confirmer' }}
                </button>
            </div>
        </form>
    </div>
</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dd-btn dd-logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        {{ session('lang','fr') === 'ar' ? 'تسجيل الخروج' : 'Se déconnecter' }}
                    </button>
                </form>
            </div>
        </div>
 
    </div>
</header>
 
{{-- ===================== WELCOME HEADER ===================== --}}
<section class="welcome-header">
    <div class="welcome-header-inner">
        <div class="welcome-text-block">
            <p class="welcome-eyebrow">
                {{ session('lang','fr') === 'ar' ? '👋 مرحباً بك في EtudServ' : '👋 Bienvenue sur EtudServ' }}
            </p>
            <h1>
                @if(session('lang','fr') === 'ar')
                    مرحبًا، <span>{{ session('utilisateur_nom', 'Utilisateur') }}</span> في EtudServ !
                @else
                    Bonjour, <span>{{ session('utilisateur_nom', 'Utilisateur') }}</span> sur EtudServ !
                @endif
            </h1>
            <p class="welcome-sub">
                @if(session('lang','fr') === 'ar')
                    حسابك جاهز. تصفّح المواهب، أطلق مشروعك الأول، أو قدّم خدماتك للعملاء.
                @else
                    Votre compte est actif. Explorez les talents, lancez votre premier projet ou proposez vos services.
                @endif
            </p>
            <div class="welcome-badge-row">
                <span class="w-badge w-badge-green">
                    <i class="fa-solid fa-circle-check" style="font-size:12px"></i>
                    {{ session('lang','fr') === 'ar' ? 'حساب مفعّل' : 'Compte actif' }}
                </span>
                @if(session('utilisateur_role'))
                <span class="w-badge w-badge-blue">
                    @if(session('utilisateur_role') === 'freelancer')
                        ⚡ {{ session('lang','fr') === 'ar' ? 'مستقل' : 'Freelancer' }}
                    @elseif(session('utilisateur_role') === 'client')
                        💼 {{ session('lang','fr') === 'ar' ? 'عميل' : 'Client' }}
                    @else
                        🎓 {{ session('lang','fr') === 'ar' ? 'طالب' : 'Étudiant' }}
                    @endif
                </span>
                @endif
                <span class="w-badge w-badge-purple">
                    <i class="fa-solid fa-location-dot" style="font-size:12px"></i>
                    Mauritanie
                </span>
            </div>
        </div>
    </div>
</section>
 
{{-- ===================== DASHBOARD LAYOUT ===================== --}}
<div class="dashboard-layout">
 
    {{-- ── MAIN COLUMN ── --}}
    <div class="main-col">
 
        {{-- RECOMMENDED FOR YOU (3 cards like Fiverr) --}}
        <p class="section-label">
            {{ session('lang','fr') === 'ar' ? 'موصى به لك' : 'RECOMMANDÉ POUR VOUS' }}
        </p>
        <div class="rec-grid">
 
            <div class="rec-card" style="grid-column: span 3;">
                <div class="rec-icon rec-ic-green">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <div class="rec-body">
                    <p class="rec-eyebrow">
                        {{ session('lang','fr') === 'ar' ? 'ملفك الشخصي' : 'PROFIL' }}
                    </p>
                    <p class="rec-title">
                        {{ session('lang','fr') === 'ar' ? 'أكمل ملفك' : 'Compléter le profil' }}
                    </p>
                    <p class="rec-desc">
                        {{ session('lang','fr') === 'ar' ? 'ملف مكتمل يجذب 3x أكثر من العملاء' : 'Un profil complet attire 3× plus de clients' }}
                    </p>
                </div>
            </div>
 
        </div>
 
        {{-- POPULAR CATEGORIES --}}
        {{-- ================= CONTENEUR DES TENDANCES DU MARCHÉ ETUDSERV (NON CLIQUABLE) ================= --}}
<div style="background: #ffffff; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(27, 37, 89, 0.02); border: 1px solid rgba(229, 231, 235, 0.6); overflow: hidden; pointer-events: none; user-select: none;">
    
    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
        <h2 style="font-size: 20px; font-weight: 700; color: #1B2559; font-family: 'Poppins', sans-serif;">
            {{ session('lang','fr') === 'ar' ? 'اتجاهات سوق الطلاب' : 'Tendances du Marché Étudiant' }}
        </h2>
        <span class="pulsing-purple-dot"></span>
    </div>
    
    <p style="font-size: 13px; color: #8A94A6; margin-bottom: 25px; font-family: 'Poppins', sans-serif;">
        {{ session('lang','fr') === 'ar' ? 'القطاعات التي تشهد أعلى طلب على المهام هذا الأسبوع.' : 'Secteurs générant la plus forte demande de missions cette semaine.' }}
    </p>

    {{-- Grille alignée compatible sans Flex global --}}
    <div style="display: block; text-align: center; font-size: 0; width: 100%;">
        
        <div class="freelancer-trend-card" style="--accent: #7B61FF; --delay: 1;">
            <div class="shimmer-sweep"></div>
            <div style="padding: 24px; position: relative; z-index: 2; text-align: left;">
                <div class="trend-icon-box" style="color: #7B61FF;"><i class="fa-solid fa-bolt"></i></div>
                <h3 style="font-size: 15px; font-weight: 700; color: #1B2559; margin-bottom: 6px; font-family: 'Poppins', sans-serif;">Forte Demande : Dev Web</h3>
                <p style="font-size: 12px; color: #6B7280; line-height: 1.5; font-family: 'Poppins', sans-serif; margin: 0;">Augmentation de +34% de briefs déposés pour des scripts ou sites vitrines.</p>
            </div>
        </div>

        <div class="freelancer-trend-card" style="--accent: #45C486; --delay: 2;">
            <div class="shimmer-sweep"></div>
            <div style="padding: 24px; position: relative; z-index: 2; text-align: left;">
                <div class="trend-icon-box" style="color: #45C486;"><i class="fa-solid fa-arrow-trend-up"></i></div>
                <h3 style="font-size: 15px; font-weight: 700; color: #1B2559; margin-bottom: 6px; font-family: 'Poppins', sans-serif;">Budgets en Hausse : Design</h3>
                <p style="font-size: 12px; color: #6B7280; line-height: 1.5; font-family: 'Poppins', sans-serif; margin: 0;">Les demandes de chartes graphiques et d'UI mobile sont revalorisées.</p>
            </div>
        </div>

        <div class="freelancer-trend-card" style="--accent: #39C6E9; --delay: 3;">
            <div class="shimmer-sweep"></div>
            <div style="padding: 24px; position: relative; z-index: 2; text-align: left;">
                <div class="trend-icon-box" style="color: #39C6E9;"><i class="fa-solid fa-fire"></i></div>
                <h3 style="font-size: 15px; font-weight: 700; color: #1B2559; margin-bottom: 6px; font-family: 'Poppins', sans-serif;">Secteur Urgent : Rédaction</h3>
                <p style="font-size: 12px; color: #6B7280; line-height: 1.5; font-family: 'Poppins', sans-serif; margin: 0;">Pic saisonnier d'aide pour les rédactions de rapports et mémoires.</p>
            </div>
        </div>

    </div>
</div>

{{-- MES PROJETS (données réelles) --}}
@php
    $prestataireConnecte = \App\Models\prestataire::where('utilisateur_idutilisateur', session('utilisateur_id'))->first();
    $mesProjets = collect();
    if ($prestataireConnecte) {
        $serviceIdsConnecte = $prestataireConnecte->services->pluck('idservice');
        $mesProjets = \App\Models\Conversion::whereIn('service_idservice', $serviceIdsConnecte)
            ->with(['utilisateur', 'service'])
            ->orderBy('idconversion', 'desc')
            ->get();
    }
@endphp

<div id="mes-projets-section" style="margin-top: 30px;">
    <p class="section-label">
        {{ session('lang','fr') === 'ar' ? 'مشاريعي' : 'MES PROJETS' }}
    </p>

    <div style="display:flex; flex-direction:column; gap:14px; margin-bottom:30px;">
        @forelse($mesProjets as $projet)
            @php
                $client = $projet->utilisateur;
                $nomClient = $client ? trim($client->prenom . ' ' . $client->nom) : 'Client';
                $estTermine = $projet->statut === 'termine';
            @endphp
            <a href="{{ route('messagerie', ['id' => $projet->idconversion]) }}" style="text-decoration:none; color:inherit;">
                <div style="background:white; border:1px solid #E9EDF7; border-radius:14px; padding:18px 22px; display:flex; justify-content:space-between; align-items:center; gap:16px; box-shadow: 0 4px 10px rgba(27, 37, 89, 0.02);">
                    <div>
                        <div style="font-weight:700; color:#1B2559; margin-bottom:4px;">
                            {{ $projet->service->titre ?? 'Service' }}
                        </div>
                        <div style="font-size:13px; color:#8A94A6;">
                            {{ session('lang','fr') === 'ar' ? 'العميل' : 'Client' }} : {{ $nomClient }}
                        </div>
                    </div>
                    <span style="padding:6px 16px; border-radius:20px; font-size:12.5px; font-weight:700; white-space:nowrap;
                        background:{{ $estTermine ? '#DCFCE7' : '#FEF3C7' }};
                        color:{{ $estTermine ? '#16A34A' : '#D97706' }};">
                        {{ $estTermine
                            ? (session('lang','fr') === 'ar' ? 'مكتمل' : 'Terminé')
                            : (session('lang','fr') === 'ar' ? 'قيد التنفيذ' : 'En cours') }}
                    </span>
                </div>
            </a>
        @empty
            <p style="color:#8A94A6; text-align:center; padding:30px 0; background:white; border:1.5px solid #E9EDF7; border-radius:16px; font-size:13.5px;">
                {{ session('lang','fr') === 'ar' ? 'لا توجد مشاريع حتى الآن.' : 'Aucun projet pour le moment.' }}
            </p>
        @endforelse
    </div>
</div>

{{-- ================= INJECTION DES RÈGLES STYLES ET ANIMATIONS SÉCURISÉES ================= --}}
<style>
.pulsing-purple-dot {
    width: 7px;
    height: 7px;
    background-color: #7B61FF;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 0 rgba(123, 97, 255, 0.5);
    animation: purpleDotPulse 2s infinite;
}

.freelancer-trend-card {
    display: inline-block;
    vertical-align: top;
    width: calc(33.33% - 16px);
    margin: 8px;
    background: #F8FCFD;
    border-radius: 16px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(229, 231, 235, 0.5);
    animation: smoothLévitation 4s ease-in-out infinite alternate;
    animation-delay: calc(var(--delay) * 0.4s);
}

.trend-icon-box {
    width: 40px;
    height: 40px;
    background: #ffffff;
    border-radius: 10px;
    text-align: center;
    line-height: 40px;
    margin-bottom: 14px;
    box-shadow: 0 4px 10px rgba(27, 37, 89, 0.02);
    font-size: 16px;
}

.shimmer-sweep {
    position: absolute;
    top: 0; left: -150%;
    width: 60%; height: 100%;
    background: linear-gradient(to right, transparent, rgba(255,255,255,0.75), transparent);
    transform: skewX(-20deg);
    animation: sweepingGlow 4s infinite ease-in-out;
    animation-delay: calc(var(--delay) * 0.5s);
}

/* Keyframes de lévitation fluide à 60fps */
@keyframes smoothLévitation {
    0% { transform: translateY(0px); box-shadow: 0 4px 10px rgba(27, 37, 89, 0.01); }
    100% { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(27, 37, 89, 0.04); border-color: rgba(123, 97, 255, 0.25); }
}

@keyframes sweepingGlow {
    0% { left: -150%; }
    50% { left: 150%; }
    100% { left: 150%; }
}

@keyframes purpleDotPulse {
    0% { box-shadow: 0 0 0 0 rgba(123, 97, 255, 0.5); }
    70% { box-shadow: 0 0 0 6px rgba(123, 97, 255, 0); }
    100% { box-shadow: 0 0 0 0 rgba(123, 97, 255, 0); }
}

/* Fallback Mobile */
@media (max-width: 768px) {
    .freelancer-trend-card { width: 100%; margin: 8px 0; }
}
</style>
 
    </div>
    {{-- ── END MAIN COLUMN ── --}}
 
    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar">
 
        {{-- Profile completion --}}
        <div class="sidebar-card">
            <h3><i class="fa-solid fa-user-circle"></i> {{ session('lang','fr') === 'ar' ? 'إكمال الملف الشخصي' : 'Progression du profil' }}</h3>
            <p class="sc-sub">
                {{ session('lang','fr') === 'ar' ? 'ملف مكتمل يجذب 3x أكثر من العملاء' : 'Un profil complet attire 3× plus de clients' }}
            </p>
            <div class="progress-wrap">
                <div class="progress-fill" id="profileBar" style="width:0%"></div>
            </div>
            <p class="progress-pct">30%</p>
            <div class="checklist">
                <div class="check-item" onclick="document.getElementById('photoUploadModal').style.display='flex'">
                    <div class="ci-icon ci-todo"><i class="fa-regular fa-circle"></i></div>
                    {{ session('lang','fr') === 'ar' ? 'إضافة صورة شخصية' : 'Ajouter une photo' }}
                </div>
            </div>
        </div>
 

 

 
    </aside>
    {{-- ── END SIDEBAR ── --}}
 
</div>
{{-- ===================== END DASHBOARD LAYOUT ===================== --}}
 
{{-- ===================== FOOTER ===================== --}}
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-logo"><span>Etud</span>Serv</div>
        <span class="footer-copy">
            © 2026 EtudServ. {{ session('lang','fr') === 'ar' ? 'جميع الحقوق محفوظة' : 'Tous droits réservés' }}.
        </span>
        <span class="footer-made">
            {{ session('lang','fr') === 'ar' ? 'صُنع بـ ❤️ في موريتانيا' : 'Fait avec ❤️ en Mauritanie' }}
        </span>
    </div>
</footer>
 
{{-- PHOTO UPLOAD MODAL --}}
<div id="photoUploadModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9998; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:20px; padding:32px; width:360px; max-width:90vw; box-shadow:0 20px 60px rgba(0,0,0,0.2); text-align:center; position:relative;">
        <button onclick="document.getElementById('photoUploadModal').style.display='none'"
            style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:20px; color:#8A94A6; cursor:pointer;">×</button>
        <div style="width:64px; height:64px; background:#EAFBFF; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:26px;">📷</div>
        <h3 style="font-size:17px; font-weight:700; color:#1B2559; margin-bottom:6px;">
            {{ session('lang','fr') === 'ar' ? 'إضافة صورة شخصية' : 'Ajouter une photo de profil' }}
        </h3>
        <p style="font-size:13px; color:#8A94A6; margin-bottom:22px; line-height:1.5;">
            {{ session('lang','fr') === 'ar' ? 'اختر صورة واضحة لملفك الشخصي' : 'Choisissez une photo claire pour votre profil' }}
        </p>
        <label for="photoInput" style="display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #E5E7EB; border-radius:14px; padding:24px; cursor:pointer; margin-bottom:18px; transition:border-color 0.2s; gap:8px;" onmouseover="this.style.borderColor='#39C6E9'" onmouseout="this.style.borderColor='#E5E7EB'">
            <i class="fa-solid fa-cloud-arrow-up" style="font-size:28px; color:#39C6E9;"></i>
            <span style="font-size:13px; font-weight:600; color:#1B2559;">
                {{ session('lang','fr') === 'ar' ? 'انقر لاختيار صورة' : 'Cliquer pour choisir une image' }}
            </span>
            <span style="font-size:11px; color:#8A94A6;">JPG, PNG — max 5 MB</span>
        </label>
        <input type="file" id="photoInput" accept="image/*" style="display:none" onchange="handlePhotoUpload(this)">
        <button onclick="document.getElementById('photoUploadModal').style.display='none'"
            style="width:100%; padding:12px; background:#39C6E9; color:white; border:none; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; font-family:inherit; transition:0.2s;"
            onmouseover="this.style.background='#2AB5D8'" onmouseout="this.style.background='#39C6E9'">
            {{ session('lang','fr') === 'ar' ? 'حفظ' : 'Enregistrer' }}
        </button>
    </div>
</div>

{{-- TOAST --}}
<div class="toast" id="toast"></div>
 
<script>
    /* ── Avatar dropdown ── */
    function toggleDropdown() {
        document.getElementById('userDropdown').classList.toggle('open');
    }
    document.addEventListener('click', function(e) {
        const menu = document.querySelector('.user-menu');
        if (menu && !menu.contains(e.target)) {
            document.getElementById('userDropdown').classList.remove('open');
        }
    });

    /* ── Photo upload handler ── */
    function handlePhotoUpload(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update avatar in navbar with the uploaded photo
                const avatar = document.querySelector('.user-avatar');
                avatar.style.background = 'transparent';
                avatar.style.padding = '0';
                avatar.style.overflow = 'hidden';
                avatar.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
                // Mark checklist item as done
                const checkItem = document.querySelector('.check-item');
                if (checkItem) {
                    checkItem.classList.add('done');
                    checkItem.querySelector('.ci-icon').className = 'ci-icon ci-done';
                    checkItem.querySelector('i').className = 'fa-solid fa-check';
                }
                document.getElementById('photoUploadModal').style.display = 'none';
                showToast('{{ session('lang','fr') === 'ar' ? '✅ تم رفع الصورة بنجاح' : '✅ Photo ajoutée avec succès !' }}');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
 
    /* ── Toast ── */
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.innerHTML = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }
 
    /* ── Animate profile bar on load ── */
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('profileBar').style.width = '30%';
        }, 400);
    });
 
    /* ── Welcome flash message ── */
    @if(session('welcome'))
        setTimeout(() => showToast("✅ {{ session('welcome') }}"), 700);
    @endif

    /* ── CONTROLE INTERACTIF DES PARAMETRES (DROPDOWN FREELANCER) ── */
function toggleSettingsDropdown(event) {
    event.preventDefault();
    event.stopPropagation();
    const menu = document.getElementById('settingsDropdownMenu');
    menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
}

// Fermer le menu si l'utilisateur clique en dehors de l'élément
document.addEventListener('click', function(e) {
    const menu = document.getElementById('settingsDropdownMenu');
    const container = document.querySelector('.settings-dropdown-container');
    if (menu && container && !container.contains(e.target)) {
        menu.style.display = 'none';
    }
});

// Fonctions d'affichage de la modale de suppression
function openDeleteAccountModal() {
    document.getElementById('deleteAccountModal').style.display = 'flex';
    document.getElementById('settingsDropdownMenu').style.display = 'none';
}

function closeDeleteAccountModal() {
    document.getElementById('deleteAccountModal').style.display = 'none';
}
// Effet de bascule dynamique du Mode Sombre
function toggleDarkModeEffect(event) {
    event.stopPropagation();
    const body = document.body;
    const isDark = body.classList.toggle('dark-mode-active');
    const switchBg = document.getElementById('darkModeSwitch');
    const handle = document.getElementById('darkModeHandle');
    
    if (isDark) {
        switchBg.style.background = '#7B61FF';
        handle.style.left = '17px';
        
        // Application du filtre d'inversion d'origine
        body.style.filter = 'invert(0.93) hue-rotate(180deg)';
        document.querySelectorAll('img, i, .avatar').forEach(el => el.style.filter = 'invert(1) hue-rotate(180deg)');
        
        // AJOUT : On force le background à devenir Blue Velvet sous le filtre
        body.style.backgroundColor = '#0A1128'; 
    } else {
        switchBg.style.background = '#E5E7EB';
        handle.style.left = '3px';
        
        // Réinitialisation d'origine
        body.style.filter = 'none';
        document.querySelectorAll('img, i, .avatar').forEach(el => el.style.filter = 'none');
        
        // RETRAIT : On enlève le background Blue Velvet en mode clair
        body.style.backgroundColor = ''; 
    }
}
</script>
 
</body>
</html>
