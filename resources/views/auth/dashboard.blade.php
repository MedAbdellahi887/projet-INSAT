@php
    $userId = session('utilisateur_id');

    // 1. Récupérer les IDs de toutes les conversations de l'utilisateur (Client ou Freelancer)
    $myConversationIds = \App\Models\Conversion::where('utilisateur_idutilisateur', $userId)
        ->orWhereHas('service.prestataire', function ($query) use ($userId) {
            $query->where('utilisateur_idutilisateur', $userId);
        })->pluck('idconversion');

    // 2. Récupérer les messages non lus envoyés par l'INTERLOCUTEUR (pas par soi-même)
    $unreadNotifications = \App\Models\Message::whereIn('conversion_idconversion', $myConversationIds)
        ->where('utilisateur_idutilisateur', '!=', $userId)
        ->where('lu', 0)
        ->orderBy('date_envoi', 'desc')
        ->get();

    // 3. Compter le nombre de messages non lus
    $unreadCount = $unreadNotifications->count();
@endphp

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
            /* ===== PALETTE TEAL DU PROJET ===== */
            --color-deepest:  #05161A;
            --color-dark:     #072E33;
            --color-mid:      #0C7075;
            --color-accent:   #0CABA8;
            --color-light:    #6DA5C0;
            --color-slate:    #294D61;
            --color-bg:       #EDF6F9;
            --color-surface:  #F5FBFC;
            --color-muted:    #3D6A7A;

            /* ===== ADAPTATION POUR LE DASHBOARD ===== */
            --blue:   var(--color-accent);
            --blue2:  var(--color-mid);
            --dark:   var(--color-deepest);
            --purple: var(--color-slate);
            --green:  #45C486; 
            --bg:     var(--color-bg);
            --white:  var(--color-surface);
            --gray:   var(--color-muted);
            --border: rgba(12, 171, 168, 0.2); 
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
            height: 75px;
            background: var(--white);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 15px rgba(0,0,0,0.06);
        }
 
        .logo { font-size: 28px; font-weight: 700; text-decoration: none; }
        .logo-blue { color: var(--blue); }
        .logo-dark { color: var(--dark); font-weight: 500; }
 
        /* Nav center links */
        .nav-center {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .nav-center a {
            padding: 10px 18px;
            font-size: 15px;
            font-weight: 500;
            color: var(--dark);
            text-decoration: none;
            border-radius: 12px;
            transition: 0.3s;
        }
        .nav-center a:hover { background: rgba(12, 171, 168, 0.1); color: var(--blue); }
        .nav-center a.active { color: var(--blue); font-weight: 600; background: rgba(12, 171, 168, 0.1); }
 
        /* Nav right */
        .nav-right { display: flex; align-items: center; gap: 20px; }
 
        /* Language toggle pill */
        .lang-toggle {
            display: flex;
            align-items: center;
            background: var(--bg);
            border-radius: 30px;
            padding: 4px;
            gap: 2px;
            border: 1px solid var(--border);
        }
        .lang-btn {
            padding: 6px 14px;
            border: none;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            background: transparent;
            color: var(--gray);
            font-family: inherit;
            transition: 0.3s;
        }
        .lang-btn.active {
            background: var(--blue);
            color: white;
            box-shadow: 0 4px 12px rgba(12,171,168,0.3);
        }
 
        /* Notification bell */
        .nav-bell {
            width: 42px; height: 42px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            color: var(--gray);
            cursor: pointer;
            background: var(--bg);
            border: 1px solid var(--border);
            position: relative;
            transition: 0.3s;
        }
        .nav-bell:hover { background: rgba(12,171,168,0.1); color: var(--blue); border-color: var(--blue); }
        .bell-dot {
            position: absolute;
            top: 8px; right: 8px;
            width: 10px; height: 10px;
            background: #EF4444;
            border-radius: 50%;
            border: 2px solid var(--white);
        }
 
        /* User avatar + dropdown */
        .user-menu { position: relative; }
        .user-avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--purple));
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 700; color: white;
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .user-avatar:hover { transform: scale(1.05); }
 
        .user-dropdown {
            display: none;
            position: absolute;
            top: 55px; right: 0;
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
            min-width: 240px;
            padding: 10px 0;
            z-index: 200;
            border: 1px solid var(--border);
        }
        .user-dropdown.open { display: block; }
        .dd-header { padding: 15px 20px; border-bottom: 1px solid var(--border); }
        .dd-name { font-size: 15px; font-weight: 700; color: var(--dark); }
        .dd-role {
            display: inline-block;
            margin-top: 6px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            background: rgba(12,171,168,0.15);
            color: var(--blue);
        }
        .dd-email { font-size: 13px; color: var(--gray); margin-top: 5px; }
        .user-dropdown a,
        .user-dropdown button.dd-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
            text-decoration: none;
            transition: 0.2s;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
        }
        .user-dropdown a:hover,
        .user-dropdown button.dd-btn:hover { background: var(--bg); color: var(--blue); padding-left: 25px; }
        .user-dropdown .dd-logout { color: #EF4444 !important; border-top: 1px solid var(--border); margin-top: 5px; }
        .user-dropdown .dd-logout:hover { background: rgba(239, 68, 68, 0.08); }
        .user-dropdown i { width: 20px; text-align: center; font-size: 16px; color: var(--gray); transition: 0.2s; }
        .user-dropdown a:hover i { color: var(--blue); }
        .user-dropdown .dd-logout i { color: #EF4444; }
 
        @media(max-width:900px) {
            .navbar { padding: 0 20px; }
            .nav-center { display: none; }
        }
 
        /* =========================================================
           WELCOME HEADER
        ========================================================= */
        .welcome-header {
            background: linear-gradient(135deg, var(--color-deepest) 0%, var(--color-dark) 50%, var(--color-slate) 100%);
            border-radius: 0 0 40px 40px;
            padding: 50px 80px 45px;
            position: relative;
            overflow: hidden;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .welcome-header::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(12,171,168,0.15);
            filter: blur(40px);
        }
        .welcome-header::after {
            content: '';
            position: absolute;
            bottom: -50px; left: 10%;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(109,165,192,0.15);
            filter: blur(30px);
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
            font-size: 14px;
            font-weight: 600;
            color: var(--color-accent);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .welcome-header h1 {
            font-size: 38px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 10px;
        }
        .welcome-header h1 span { color: var(--color-light); }
        .welcome-header .welcome-sub {
            font-size: 16px;
            color: rgba(255,255,255,0.7);
            max-width: 600px;
            line-height: 1.7;
        }
        .welcome-badge-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .w-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        .w-badge-green  { background: rgba(69, 196, 134, 0.15); color: #45C486; border: 1px solid rgba(69, 196, 134, 0.3); }
        .w-badge-blue   { background: rgba(12, 171, 168, 0.15); color: var(--color-accent); border: 1px solid rgba(12, 171, 168, 0.3); }
        .w-badge-purple { background: rgba(109, 165, 192, 0.15); color: var(--color-light); border: 1px solid rgba(109, 165, 192, 0.3); }
 
        @media(max-width:768px) {
            .welcome-header { padding: 30px 20px; border-radius: 0 0 25px 25px; margin-bottom: 25px; }
            .welcome-header h1 { font-size: 28px; }
        }
 
        /* =========================================================
           MAIN LAYOUT & CARDS OVERRIDE
        ========================================================= */
        .dashboard-layout {
            max-width: 1200px;
            margin: 0 auto 50px;
            padding: 0 60px;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 40px;
            align-items: start;
        }
        @media(max-width:1100px) {
            .dashboard-layout { grid-template-columns: 1fr; padding: 0 24px; }
        }

        .rec-card, .cat-card, .fl-card, .sidebar-card, .freelancer-trend-card, 
        div[style*="background:white"], div[style*="background: white"] {
            background: var(--white) !important;
            padding: 25px !important;
            border-radius: 30px !important;
            transition: 0.4s !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04) !important;
            border: 1px solid rgba(0,0,0,0.03) !important;
        }

        .rec-card:hover, .cat-card:hover, .fl-card:hover, a > div[style*="background:white"]:hover {
            transform: translateY(-8px) !important;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
            border-color: var(--blue) !important;
        }
 
        /* ── Section labels ──────────────────────────────────── */
        .section-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--blue);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-label::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--border);
            border-radius: 2px;
        }
        .rec-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 45px;
        }
        @media(max-width:700px) { .rec-grid { grid-template-columns: 1fr; } }
        @media(max-width:900px) { .rec-grid { grid-template-columns: 1fr 1fr; } }
 
        .rec-icon {
            width: 55px; height: 55px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
            margin-bottom: 15px;
        }
        .rec-card { flex-direction: column; align-items: flex-start; gap: 0; }
        
        .rec-ic-blue   { background: rgba(12,171,168,0.1); color: var(--blue); }
        .rec-ic-purple { background: rgba(41,77,97,0.1); color: var(--purple); }
        .rec-ic-green  { background: rgba(69,196,134,0.1); color: var(--green); }
        
        .rec-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 6px;
        }
        .rec-title { font-size: 16px; font-weight: 700; color: var(--dark); margin-bottom: 6px; }
        .rec-desc  { font-size: 13px; color: var(--gray); line-height: 1.6; }
 
        /* ── Section headers ─────────────────────────────────── */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border);
        }
        .section-header h2 {
            font-size: 20px; font-weight: 800; color: var(--dark);
            display: flex; align-items: center; gap: 12px;
        }
        .section-header h2::before {
            content: '';
            display: inline-block;
            width: 5px; height: 22px;
            background: linear-gradient(180deg, var(--blue), var(--purple));
            border-radius: 5px;
        }
        .section-header a {
            font-size: 13px; color: var(--blue); font-weight: 700;
            text-decoration: none; padding: 8px 20px;
            border-radius: 30px; border: 2px solid var(--border); transition: 0.3s;
        }
        .section-header a:hover { background: var(--blue); color: white; border-color: var(--blue); box-shadow: 0 5px 15px rgba(12,171,168,0.3); }
 
        /* =========================================================
           FREELANCER CARDS OVERRIDE
        ========================================================= */
        .fl-card::before { display: none; } 
        
        .fl-head { display: flex; align-items: center; gap: 15px; margin-bottom: 18px; }
        .fl-av {
            width: 55px; height: 55px; border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 700; color: white; flex-shrink: 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .fl-meta h4 { font-size: 16px; font-weight: 800; color: var(--dark); }
        .fl-meta span { font-size: 13px; color: var(--gray); font-weight: 500; }
 
        .fl-badge {
            display: inline-block;
            padding: 5px 14px; border-radius: 25px;
            font-size: 12px; font-weight: 700; margin-bottom: 15px;
        }
        .badge-student { background: rgba(12,171,168,0.1); color: var(--blue); }
        .badge-pro     { background: rgba(41,77,97,0.1); color: var(--purple); }
 
        .fl-skills { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 15px; }
        .fl-skill {
            padding: 5px 12px; background: var(--bg);
            border-radius: 8px; font-size: 12px; color: var(--gray); font-weight: 600;
        }
        .fl-desc { font-size: 14px; color: var(--gray); line-height: 1.7; margin-bottom: 18px; }
        .fl-footer-row {
            display: flex; justify-content: space-between; align-items: center;
            padding-top: 15px; border-top: 1px solid var(--border);
        }
        .fl-rate  { font-size: 13px; color: #F59E0B; font-weight: 700; }
        .fl-price { font-size: 16px; font-weight: 800; color: var(--dark); }
 
        .btn-contact {
            width: 100%; margin-top: 15px; padding: 14px;
            border: none;
            background: var(--blue);
            color: white; border-radius: 15px; font-size: 14px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 8px 20px rgba(12,171,168,0.3);
        }
        .btn-contact:hover { background: var(--color-mid); transform: translateY(-2px); }
 
        /* =========================================================
           SIDEBAR
        ========================================================= */
        .sidebar { display: flex; flex-direction: column; gap: 25px; }
        .sidebar-card h3 { font-size: 16px; font-weight: 800; color: var(--dark); margin-bottom: 5px; display: flex; align-items: center; gap: 10px; }
        .sidebar-card h3 i { color: var(--blue); font-size: 18px; }
        .sidebar-card .sc-sub { font-size: 13px; color: var(--gray); margin-bottom: 20px; line-height: 1.5; }
        .progress-wrap { background: var(--bg); border-radius: 20px; height: 10px; overflow: hidden; margin-bottom: 8px; }
        .progress-fill { height: 100%; background: linear-gradient(90deg, var(--blue), var(--color-light)); transition: 1s ease-out; border-radius: 20px; }
        .progress-pct { font-size: 14px; font-weight: 800; color: var(--dark); text-align: right; margin-bottom: 20px; }
        .checklist { display: flex; flex-direction: column; gap: 12px; }
        .check-item {
            display: flex; align-items: center; gap: 12px;
            font-size: 14px; font-weight: 600; color: var(--dark); cursor: pointer; transition: 0.3s;
            padding: 10px; border-radius: 12px;
        }
        .check-item:hover { background: var(--bg); color: var(--blue); }
        .ci-icon { font-size: 18px; }
        .ci-todo { color: var(--gray); }
        .check-item.done { color: var(--gray); text-decoration: line-through; pointer-events: none; }
        .check-item.done .ci-icon { color: var(--green); }
 
        /* =========================================================
           FOOTER
        ========================================================= */
        .footer {
            background: var(--color-deepest);
            padding: 40px 60px;
            color: rgba(255,255,255,0.7);
        }
        .footer-inner {
            max-width: 1200px; margin: 0 auto;
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 20px;
        }
        .footer-logo { font-size: 24px; font-weight: 800; color: white; }
        .footer-logo span { color: var(--blue); }
        .footer-copy { font-size: 14px; font-weight: 500; }
        .footer-made { font-size: 14px; color: var(--blue); font-weight: 600; }
        @media(max-width:600px) { .footer { padding: 30px 20px; } .footer-inner { flex-direction: column; text-align: center; } }
 
        /* =========================================================
           TOAST & MODAL
        ========================================================= */
        .toast {
            position: fixed; bottom: 30px; right: 30px;
            background: var(--dark); color: white;
            padding: 16px 24px; border-radius: 16px;
            font-size: 15px; font-weight: 600;
            transform: translateY(100px); opacity: 0; transition: 0.4s;
            z-index: 9999; display: flex; align-items: center; gap: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        
        #photoUploadModal > div {
            background: var(--white) !important;
            border-radius: 30px !important;
            border: none !important;
        }
        
        /* =========================================================
           RTL
        ========================================================= */
        [dir="rtl"] .nav-right { flex-direction: row-reverse; }
        [dir="rtl"] .nav-center { flex-direction: row-reverse; }
        [dir="rtl"] .welcome-header-inner { flex-direction: row-reverse; }
        [dir="rtl"] .fl-head { flex-direction: row-reverse; }
        [dir="rtl"] .rec-card { flex-direction: row-reverse; align-items: flex-end; text-align: right; }
        [dir="rtl"] .section-header { flex-direction: row-reverse; }
        [dir="rtl"] .fl-footer-row { flex-direction: row-reverse; }
        [dir="rtl"] .sml { flex-direction: row-reverse; }
        [dir="rtl"] .sidebar-card h3 { flex-direction: row-reverse; justify-content: flex-end; }
        [dir="rtl"] .progress-pct { text-align: left; }
        [dir="rtl"] .check-item { flex-direction: row-reverse; }
        [dir="rtl"] .footer-inner { flex-direction: row-reverse; }
        [dir="rtl"] .user-dropdown a, [dir="rtl"] .user-dropdown button.dd-btn { flex-direction: row-reverse; text-align: right; }
        /* ================= DROPDOWN NOTIFICATIONS PREMIUM & DYNAMIQUE ================= */
.notification-container {
    position: relative;
    display: inline-block;
}

.notification-bell {
    position: relative;
    background: none;
    border: none;
    color: #294D61;
    font-size: 22px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.notification-bell:hover {
    color: var(--color-accent);
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.08);
}

/* --- Animation d'alerte sur la Cloche --- */
@keyframes cool-shake {
    0% { transform: rotate(0); }
    15% { transform: rotate(12deg); }
    30% { transform: rotate(-12deg); }
    45% { transform: rotate(8deg); }
    60% { transform: rotate(-8deg); }
    75% { transform: rotate(4deg); }
    100% { transform: rotate(0); }
}

.bell-has-unread i {
    animation: cool-shake 2.5s infinite ease-in-out;
    transform-origin: top center;
    color: var(--color-accent);
}

/* --- Badge Rouge qui pulse --- */
@keyframes modern-pulse {
    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

.notification-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background: #EF4444;
    color: white;
    font-size: 10px;
    font-weight: 700;
    min-width: 18px;
    height: 18px;
    padding: 0 4px;
    border-radius: 10px;
    border: 2px solid var(--color-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    animation: modern-pulse 1.8s infinite;
}

/* --- Menu Déroulant style Glassmorphism (Effet Flou Premium) --- */
@keyframes dropdown-bounce {
    from {
        opacity: 0;
        transform: translateY(15px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.notification-dropdown {
    display: none;
    position: absolute;
    right: -10px;
    top: 100%;
    margin-top: 12px;
    width: 340px;
    background: rgba(255, 255, 255, 0.88) !important;
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-radius: 20px !important;
    box-shadow: 0 20px 40px rgba(5, 22, 26, 0.18), 0 5px 15px rgba(12, 171, 168, 0.05) !important;
    border: 1px solid rgba(12, 171, 168, 0.25) !important;
    z-index: 1000;
    overflow: hidden;
}

/* Ce sélecteur déclenche l'animation dès que le JS applique le display block */
.notification-dropdown[style*="display: block"] {
    display: block !important;
    animation: dropdown-bounce 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.notification-header {
    background: linear-gradient(135deg, var(--color-dark) 0%, #0c434a 100%);
    color: white;
    padding: 14px 18px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

.notification-list {
    max-height: 300px;
    overflow-y: auto;
}

/* --- Items de Notification Dynamiques --- */
.notification-item {
    display: block;
    padding: 14px 18px;
    border-bottom: 1px solid rgba(109,165,192,0.15);
    text-decoration: none;
    color: #05161A;
    border-left: 0 solid var(--color-accent);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.notification-item:hover {
    background: rgba(12, 171, 168, 0.06) !important;
    border-left: 4px solid var(--color-accent);
    transform: translateX(4px);
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-sender {
    font-weight: 600;
    font-size: 13.5px;
    color: var(--color-dark);
    margin-bottom: 3px;
    display: flex;
    align-items: center;
}

.notification-text {
    font-size: 12.5px;
    color: #4A5568;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-left: 20px;
}

.notification-time {
    font-size: 10.5px;
    color: #A0AEC0;
    margin-top: 5px;
    text-align: right;
    font-weight: 500;
}

/* --- État Vide Style "Cool" --- */
.notification-empty {
    padding: 35px 20px;
    text-align: center;
    color: var(--color-slate);
    font-size: 13.5px;
}

.notification-empty i {
    font-size: 32px;
    color: var(--color-light);
    display: block;
    margin-bottom: 12px;
    opacity: 0.6;
    transition: transform 0.5s ease;
}

.notification-container:hover .notification-empty i {
    transform: scale(1.15) rotate(10deg);
}
    </style>
</head>
 
<body>
 
{{-- ===================== NAVBAR ===================== --}}
<header class="navbar">
 
    <a href="{{ route('home') }}" class="logo">
        <span class="logo-blue">Etud</span><span class="logo-dark">Serv</span>
    </a>
 
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
        <a href="{{ route('contact') }}" class="nav-item-link" style="display: flex; align-items: center; gap: 8px; color: #072E33; text-decoration: none; font-size: 14px; font-weight: 600; padding: 10px 16px; border-radius: 12px; transition: background 0.3s ease, color 0.3s ease; font-family: 'Poppins', 'Cairo', sans-serif;" onmouseover="this.style.background='rgba(41,77,97, 0.08)'; this.style.color='#294D61';" onmouseout="this.style.background='transparent'; this.style.color='#072E33';">
            <i class="fa-solid fa-headset" style="font-size: 16px;"></i>
            <span>{{ session('lang','fr') === 'ar' ? 'اتصل بنا' : 'Contact' }}</span>
        </a>
    </nav>
 
    <div class="nav-right">
 
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
            {{-- Notifications icon --}}
        <div class="notification-container">
    <button class="notification-bell {{ $unreadCount > 0 ? 'bell-has-unread' : '' }}" id="notificationBellBtn">
        <i class="fa-solid fa-bell"></i>
        @if($unreadCount > 0)
            <span class="notification-badge">{{ $unreadCount }}</span>
        @endif
    </button>

    <div class="notification-dropdown" id="notificationDropdownMenu">
        <div class="notification-header">
            <span><i class="fa-regular fa-message" style="margin-right: 6px;"></i> Discussions</span>
            @if($unreadCount > 0)
                <span style="background: var(--color-accent); color: white; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; box-shadow: 0 2px 8px rgba(12,171,168,0.4);">
                    {{ $unreadCount }} Nouveau(x)
                </span>
            @endif
        </div>
        
        <div class="notification-list">
            @forelse($unreadNotifications as $notif)
                <a href="{{ route('messagerie', ['id' => $notif->conversion_idconversion]) }}" class="notification-item">
                    <div class="notification-sender">
                        <i class="fa-solid fa-circle shadow-sm" style="color: var(--color-accent); font-size: 8px; margin-right: 12px;"></i>
                        {{ $notif->expediteur ?? 'Nouveau message' }}
                    </div>
                    <div class="notification-text">
                        {{ Str::limit($notif->contenu, 40) }}
                    </div>
                    <div class="notification-time">
                        <i class="fa-regular fa-clock" style="font-size: 10px; margin-right: 3px;"></i>
                        {{ \Carbon\Carbon::parse($notif->date_envoi)->diffForHumans() }}
                    </div>
                </a>
            @empty
                <div class="notification-empty">
                    <i class="fa-regular fa-bell"></i>
                    <p style="font-weight: 500;">Votre boîte est à jour !</p>
                    <span style="font-size: 12px; opacity: 0.7;">Aucun message non lu.</span>
                </div>
            @endforelse
        </div>
    </div>
</div>

        {{-- User avatar + dropdown --}}
        <div class="user-menu" style="position: relative; display: inline-block;">
            @php
                $uConnecte = \App\Models\Utilisateur::find(session('utilisateur_id') ?? session('idutilisateur'));
            @endphp

            <div class="user-avatar" onclick="toggleDropdown()" title="{{ session('utilisateur_nom', '') }}" style="padding: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 50%; background: var(--color-mid, #0C7075); font-size: 16px; font-weight: 700; color: white; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(5,22,26, 0.15);">
                @if($uConnecte && $uConnecte->photo)
                    <img src="{{ asset('storage/' . $uConnecte->photo) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr(session('utilisateur_nom', 'U'), 0, 1)) }}
                @endif
            </div>

            <div class="user-dropdown" id="userDropdown">
                <div class="dd-header">
                    <div class="dd-name">{{ session('utilisateur_nom', '') }}</div>
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
                
                <a href="{{ route('profil.show', session('utilisateur_id') ?? session('idutilisateur')) }}">
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
                
                {{-- PARAMÈTRES --}}
                <div class="settings-dropdown-container" style="position: relative; display: block; z-index: 9999;">
                    <a href="javascript:void(0)" onclick="toggleSettingsDropdown(event)" style="text-decoration: none; display: flex; align-items: center; gap: 8px; color: inherit; cursor: pointer; position: relative; z-index: 10001; outline: none;">
                        <i class="fa-solid fa-gear"></i>
                        {{ session('lang','fr') === 'ar' ? 'الإعدادات' : 'Paramètres' }}
                    </a>
                    
                    <div id="settingsDropdownMenu" style="display: none; position: absolute; right: 0; top: calc(100% + 12px); width: 220px; background: #ffffff; border-radius: 12px; box-shadow: 0 10px 25px rgba(5,22,26, 0.15); border: 1px solid rgba(109,165,192, 0.3); z-index: 10000; overflow: hidden; padding: 6px 0; text-align: left;">
                        <a href="{{ route('profil.show', ['id' => (session('utilisateur_id') ?? session('idutilisateur'))]) }}" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; color: var(--color-dark, #072E33); text-decoration: none; font-size: 13px; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='rgba(12,112,117, 0.08)'" onmouseout="this.style.background='transparent'">
                            <i class="fa-solid fa-user-gear" style="color: var(--color-mid, #0C7075); font-size: 14px;"></i>
                            <span>{{ session('lang','fr') === 'ar' ? 'عرض الملف الشخصي' : 'Mon Profil' }}</span>
                        </a>
                        
                        <div onclick="toggleDarkModeEffect(event)" style="display: flex; align-items: center; justify-content: space-between; padding: 11px 16px; color: var(--color-dark, #072E33); cursor: pointer; font-size: 13px; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='rgba(12,112,117, 0.08)'" onmouseout="this.style.background='transparent'">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fa-solid fa-moon" style="color: #F59E0B; font-size: 14px;"></i>
                                <span>{{ session('lang','fr') === 'ar' ? 'الوضع الداكن' : 'Mode Sombre' }}</span>
                            </div>
                            <div id="darkModeSwitch" style="width: 32px; height: 18px; background: #E5E7EB; border-radius: 20px; position: relative; transition: background 0.3s;">
                                <div id="darkModeHandle" style="width: 12px; height: 12px; background: white; border-radius: 50%; position: absolute; top: 3px; left: 3px; transition: left 0.3s;"></div>
                            </div>
                        </div>
                        
                        <hr style="border: 0; border-top: 1px solid rgba(109,165,192, 0.2); margin: 5px 0;">
                        
                        <div onclick="openDeleteAccountModal()" style="display: flex; align-items: center; gap: 12px; padding: 11px 16px; color: #EF4444; cursor: pointer; font-size: 13px; font-weight: 600; transition: background 0.2s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.08)'" onmouseout="this.style.background='transparent'">
                            <i class="fa-solid fa-trash-can" style="font-size: 14px;"></i>
                            <span>{{ session('lang','fr') === 'ar' ? 'حذف الحساب' : 'Supprimer le compte' }}</span>
                        </div>
                    </div>
                </div>

                {{-- SE DÉCONNECTER (Idéalement placé sous Paramètres) --}}
                <hr style="border: 0; border-top: 1px solid rgba(109,165,192, 0.2); margin: 8px 0;">
                <form method="POST" action="{{ route('logout') }}" style="display: none;" id="logout-form">
                    @csrf
                </form>
                <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dd-btn dd-logout" style="text-align: left; width: 100%; border: none; background: transparent; cursor: pointer; padding: 10px 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>{{ session('lang','fr') === 'ar' ? 'تسجيل الخروج' : 'Se déconnecter' }}</span>
                </button>
            </div>
        </div>
 
    </div>

    {{-- Fenêtre modale masquée pour suppression de compte --}}
    <div id="deleteAccountModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(5,22,26, 0.4); z-index: 10005; align-items: center; justify-content: center; backdrop-filter: blur(3px);">
        <div style="background: #ffffff; width: 90%; max-width: 420px; padding: 25px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.15); text-align: center; border: 1px solid #E5E7EB;">
            <div style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1); color: #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 style="font-size: 17px; font-weight: 700; color: var(--color-dark, #072E33); margin-bottom: 8px;">
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
                    حسابك جاهز. تصفّح المواهب، أطلق مشروعك الأول, أو قدّم خدماتك للعملاء.
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
 
    <div class="main-col">
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
 
        <div style="background: #ffffff; border-radius: 20px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(7,46,51, 0.02); border: 1px solid rgba(221,238,241, 0.6); overflow: hidden; pointer-events: none; user-select: none;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                <h2 style="font-size: 20px; font-weight: 700; color: #072E33; font-family: 'Poppins', sans-serif;">
                    {{ session('lang','fr') === 'ar' ? 'اتجاهات سوق الطلاب' : 'Tendances du Marché Étudiant' }}
                </h2>
                <span class="pulsing-purple-dot"></span>
            </div>
            
            <p style="font-size: 13px; color: #6E8B95; margin-bottom: 25px; font-family: 'Poppins', sans-serif;">
                {{ session('lang','fr') === 'ar' ? 'القطاعات التي تشهد أعلى طلب على المهام هذا الأسبوع.' : 'Secteurs générant la plus forte demande de missions cette semaine.' }}
            </p>

            <div style="display: block; text-align: center; font-size: 0; width: 100%;">
                <div class="freelancer-trend-card" style="--accent: #294D61; --delay: 1;">
                    <div class="shimmer-sweep"></div>
                    <div style="padding: 24px; position: relative; z-index: 2; text-align: left;">
                        <div class="trend-icon-box" style="color: #294D61;"><i class="fa-solid fa-bolt"></i></div>
                        <h3 style="font-size: 15px; font-weight: 700; color: #072E33; margin-bottom: 6px; font-family: 'Poppins', sans-serif;">Forte Demande : Dev Web</h3>
                        <p style="font-size: 12px; color: #6B7280; line-height: 1.5; font-family: 'Poppins', sans-serif; margin: 0;">Augmentation de +34% de briefs déposés pour des scripts ou sites vitrines.</p>
                    </div>
                </div>

                <div class="freelancer-trend-card" style="--accent: #1FA67A; --delay: 2;">
                    <div class="shimmer-sweep"></div>
                    <div style="padding: 24px; position: relative; z-index: 2; text-align: left;">
                        <div class="trend-icon-box" style="color: #1FA67A;"><i class="fa-solid fa-arrow-trend-up"></i></div>
                        <h3 style="font-size: 15px; font-weight: 700; color: #072E33; margin-bottom: 6px; font-family: 'Poppins', sans-serif;">Budgets en Hausse : Design</h3>
                        <p style="font-size: 12px; color: #6B7280; line-height: 1.5; font-family: 'Poppins', sans-serif; margin: 0;">Les demandes de chartes graphiques et d'UI mobile sont revalorisées.</p>
                    </div>
                </div>

                <div class="freelancer-trend-card" style="--accent: #0F929C; --delay: 3;">
                    <div class="shimmer-sweep"></div>
                    <div style="padding: 24px; position: relative; z-index: 2; text-align: left;">
                        <div class="trend-icon-box" style="color: #0F929C;"><i class="fa-solid fa-fire"></i></div>
                        <h3 style="font-size: 15px; font-weight: 700; color: #072E33; margin-bottom: 6px; font-family: 'Poppins', sans-serif;">Secteur Urgent : Rédaction</h3>
                        <p style="font-size: 12px; color: #6B7280; line-height: 1.5; font-family: 'Poppins', sans-serif; margin: 0;">Pic saisonnier d'aide pour les rédactions de rapports et mémoires.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- MES PROJETS --}}
        @php
            $prestataireConnecte = \App\Models\prestataire::where('utilisateur_idutilisateur', session('utilisateur_id') ?? session('idutilisateur'))->first();
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
                        <div style="background:white; border:1px solid #E3EEF1; border-radius:14px; padding:18px 22px; display:flex; justify-content:space-between; align-items:center; gap:16px; box-shadow: 0 4px 10px rgba(7,46,51, 0.02);">
                            <div>
                                <div style="font-weight:700; color:#072E33; margin-bottom:4px;">
                                    {{ $projet->service->titre ?? 'Service' }}
                                </div>
                                <div style="font-size:13px; color:#6E8B95;">
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
                    <p style="color:#6E8B95; text-align:center; padding:30px 0; background:white; border:1.5px solid #E3EEF1; border-radius:16px; font-size:13.5px;">
                        {{ session('lang','fr') === 'ar' ? 'لا توجد مشاريع حتى الآن.' : 'Aucun projet pour le moment.' }}
                    </p>
                @endforelse
            </div>
        </div>

<style>
.pulsing-purple-dot {
    width: 7px;
    height: 7px;
    background-color: #294D61;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 0 rgba(41,77,97, 0.5);
    animation: purpleDotPulse 2s infinite;
}
.freelancer-trend-card {
    display: inline-block;
    vertical-align: top;
    width: calc(33.33% - 16px);
    margin: 8px;
    background: #EFF8FA;
    border-radius: 16px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(221,238,241, 0.5);
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
    box-shadow: 0 4px 10px rgba(7,46,51, 0.02);
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
@keyframes smoothLévitation {
    0% { transform: translateY(0px); box-shadow: 0 4px 10px rgba(7,46,51, 0.01); }
    100% { transform: translateY(-8px); box-shadow: 0 12px 20px rgba(7,46,51, 0.04); border-color: rgba(41,77,97, 0.25); }
}
@keyframes sweepingGlow {
    0% { left: -150%; }
    50% { left: 150%; }
    100% { left: 150%; }
}
@keyframes purpleDotPulse {
    0% { box-shadow: 0 0 0 0 rgba(41,77,97, 0.5); }
    70% { box-shadow: 0 0 0 6px rgba(41,77,97, 0); }
    100% { box-shadow: 0 0 0 0 rgba(41,77,97, 0); }
}
@media (max-width: 768px) {
    .freelancer-trend-card { width: 100%; margin: 8px 0; }
}
</style>
 
    </div>
 
    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar">
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
 
</div>
 
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
 
{{-- PHOTO UPLOAD MODAL (CORRIGÉE AVEC LE FORMULAIRE REQUIS ET L'INPUT NOMMÉ) --}}
<div id="photoUploadModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9998; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:20px; padding:32px; width:360px; max-width:90vw; box-shadow:0 20px 60px rgba(0,0,0,0.2); text-align:center; position:relative;">
        <button type="button" onclick="document.getElementById('photoUploadModal').style.display='none'"
            style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:20px; color:#6E8B95; cursor:pointer;">×</button>
        <div style="width:64px; height:64px; background:#E1F2F5; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:26px;">📷</div>
        <h3 style="font-size:17px; font-weight:700; color:#072E33; margin-bottom:6px;">
            {{ session('lang','fr') === 'ar' ? 'إضافة صورة شخصية' : 'Ajouter une photo de profil' }}
        </h3>
        <p style="font-size:13px; color:#6E8B95; margin-bottom:22px; line-height:1.5;">
            {{ session('lang','fr') === 'ar' ? 'اختر صورة واضحة لملفك الشخصis' : 'Choisissez une photo claire pour votre profil' }}
        </p>
        
        <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data" id="laravelPhotoForm">
            @csrf
            <label for="photoInput" style="display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #E5E7EB; border-radius:14px; padding:24px; cursor:pointer; margin-bottom:18px; transition:border-color 0.2s; gap:8px;" onmouseover="this.style.borderColor='#0F929C'" onmouseout="this.style.borderColor='#E5E7EB'">
                <i class="fa-solid fa-cloud-arrow-up" style="font-size:28px; color:#0F929C;"></i>
                <span style="font-size:13px; font-weight:600; color:#072E33;">
                    {{ session('lang','fr') === 'ar' ? 'انقر لاختيار صورة' : 'Cliquer pour choisir une image' }}
                </span>
                <span style="font-size:11px; color:#6E8B95;">JPG, PNG — max 2 MB</span>
            </label>
            <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none" onchange="handlePhotoUpload(this)">
            <button type="submit"
                style="width:100%; padding:12px; background:#0F929C; color:white; border:none; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; font-family:inherit; transition:0.2s;"
                onmouseover="this.style.background='#0C6A74'" onmouseout="this.style.background='#0F929C'">
                {{ session('lang','fr') === 'ar' ? 'حفظ' : 'Enregistrer' }}
            </button>
        </form>
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

    /* ── Photo upload handler modifiée pour soumettre réellement le formulaire ── */
    function handlePhotoUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validation de la taille côté client (2 Mo)
            if (file.size > 2 * 1024 * 1024) {
                alert('{{ session('lang','fr') === 'ar' ? 'حجم الملف كبير جداً. الحد الأقصى هو 2 ميغابايت.' : 'Le fichier est trop volumineux. Le maximum est de 2 Mo.' }}');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const avatar = document.querySelector('.user-avatar');
                if(avatar) {
                    avatar.style.background = 'transparent';
                    avatar.style.padding = '0';
                    avatar.style.overflow = 'hidden';
                    avatar.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
                }
                
                // Soumettre automatiquement le formulaire HTML vers Laravel après sélection
                document.getElementById('laravelPhotoForm').submit();
            };
            reader.readAsDataURL(file);
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
            const bar = document.getElementById('profileBar');
            if(bar) bar.style.width = '30%';
        }, 400);
    });
 
    @if(session('welcome'))
        setTimeout(() => showToast("✅ {{ session('welcome') }}"), 700);
    @endif
    @if(session('success'))
        setTimeout(() => showToast("{{ session('success') }}"), 400);
    @endif

    /* ── CONTROLE INTERACTIF DES PARAMETRES ── */
    function toggleSettingsDropdown(event) {
        event.preventDefault();
        event.stopPropagation();
        const menu = document.getElementById('settingsDropdownMenu');
        menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
    }

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('settingsDropdownMenu');
        const container = document.querySelector('.settings-dropdown-container');
        if (menu && container && !container.contains(e.target)) {
            menu.style.display = 'none';
        }
    });

    function openDeleteAccountModal() {
        document.getElementById('deleteAccountModal').style.display = 'flex';
        document.getElementById('settingsDropdownMenu').style.display = 'none';
    }

    function closeDeleteAccountModal() {
        document.getElementById('deleteAccountModal').style.display = 'none';
    }

    function toggleDarkModeEffect(event) {
        event.stopPropagation();
        const body = document.body;
        const isDark = body.classList.toggle('dark-mode-active');
        const switchBg = document.getElementById('darkModeSwitch');
        const handle = document.getElementById('darkModeHandle');
        
        if (isDark) {
            switchBg.style.background = '#294D61';
            handle.style.left = '17px';
            body.style.filter = 'invert(0.93) hue-rotate(180deg)';
            document.querySelectorAll('img:not(.user-avatar img), i, .avatar').forEach(el => el.style.filter = 'invert(1) hue-rotate(180deg)');
            body.style.backgroundColor = '#020B0D'; 
        } else {
            switchBg.style.background = '#E5E7EB';
            handle.style.left = '3px';
            body.style.filter = 'none';
            document.querySelectorAll('img, i, .avatar').forEach(el => el.style.filter = 'none');
            body.style.backgroundColor = ''; 
        }
    }
//================= SCRIPT NOTIFICATIONS ================= 
document.addEventListener('DOMContentLoaded', function() {
    const bellBtn = document.getElementById('notificationBellBtn');
    const dropdownMenu = document.getElementById('notificationDropdownMenu');

    if (bellBtn && dropdownMenu) {
        // 1. Ouvrir ou fermer au clic sur la cloche
        bellBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Empêche le clic de se propager au reste de la page
            if (dropdownMenu.style.display === 'block') {
                dropdownMenu.style.display = 'none';
            } else {
                dropdownMenu.style.display = 'block';
            }
        });

        // 2. Fermer le menu si on clique n'importe où à l'extérieur
        document.addEventListener('click', function(e) {
            if (!dropdownMenu.contains(e.target) && !bellBtn.contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });

        // 3. EFFET COOL : Disparition immédiate de la notification au clic
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Animation de glissement vers la droite et fondu transparent
                this.style.transition = 'all 0.35s ease-out';
                this.style.opacity = '0';
                this.style.transform = 'translateX(40px)';
                this.style.background = 'rgba(12, 171, 168, 0.1)'; 

                // Mise à jour en temps réel du badge rouge
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    let currentCount = parseInt(badge.textContent);
                    if (currentCount > 1) {
                        badge.textContent = currentCount - 1;
                    } else {
                        // S'il ne reste plus de messages non lus, on enlève le badge et la secousse
                        badge.remove();
                        bellBtn.classList.remove('bell-has-unread');
                    }
                }
            });
        });
    }
});    
</script>
 
</body>
</html>