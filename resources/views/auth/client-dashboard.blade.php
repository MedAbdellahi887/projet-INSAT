@php
    // 1. Récupération des données du client (idutilisateur dans votre base sql)
    $client = \App\Models\Utilisateur::find(session('utilisateur_id'));
    
    // 2. Compter ses projets réels via la table conversion (qui possède bien 'utilisateur_idutilisateur')
    $nombreProjets = \App\Models\Conversion::where('utilisateur_idutilisateur', session('utilisateur_id'))->count(); 

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

{{-- resources/views/client/dashboard.blade.php --}}
{{-- Route middleware: role:client only --}}

<!DOCTYPE html>
<html lang="{{ session('lang', 'fr') }}" dir="{{ session('lang', 'fr') === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EtudServ – Espace Client</title>
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

            /* ===== ADAPTATION POUR LE DASHBOARD CLIENT ===== */
            --blue:   var(--color-accent);
            --blue2:  var(--color-mid);
            --dark:   var(--color-deepest);
            --purple: var(--color-slate);
            --green:  #45C486; 
            --orange: #F59E0B;
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
 
        /* Notification bell & Message */
        .nav-bell, .nav-msg {
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
            text-decoration: none;
        }
        .nav-bell:hover, .nav-msg:hover { background: rgba(12,171,168,0.1); color: var(--blue); border-color: var(--blue); }
        .bell-dot, .msg-dot {
            position: absolute;
            top: 8px; right: 8px;
            width: 10px; height: 10px;
            border-radius: 50%;
            border: 2px solid var(--white);
        }
        .bell-dot { background: #EF4444; }
        .msg-dot { background: var(--blue); }
 
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
           WELCOME HEADER (Adapté au visuel Hero de Projet)
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

        .header-cta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 15px;
        }
        .btn-post-project {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: var(--blue);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 8px 25px rgba(12,171,168,0.4);
        }
        .btn-post-project:hover { background: var(--color-mid); transform: translateY(-3px); }

        @media(max-width:768px) {
            .welcome-header { padding: 30px 20px; border-radius: 0 0 25px 25px; margin-bottom: 25px; }
            .welcome-header h1 { font-size: 28px; }
            .header-cta { align-items: flex-start; }
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

        /* Applique le style Bulle & Ombre du Projet aux éléments du Dashboard */
        .rec-card, .cat-card, .fl-card, .project-card, .messages-panel, 
        div[style*="background:white"], div[style*="background: white"] {
            background: var(--white) !important;
            border-radius: 30px !important;
            transition: 0.4s !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04) !important;
            border: 1px solid rgba(0,0,0,0.03) !important;
            overflow: hidden;
        }

        .rec-card, .cat-card, .fl-card, .project-card { padding: 25px !important; }

        .rec-card:hover, .cat-card:hover, .fl-card:hover, .project-card:hover, a > div[style*="background:white"]:hover {
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
           QUICK ACTIONS CARDS (top 3)
        ========================================================= */
        .rec-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 45px;
        }
        @media(max-width:700px) { .rec-grid { grid-template-columns: 1fr; } }
        @media(max-width:900px) { .rec-grid { grid-template-columns: 1fr 1fr; } }

        .rec-card { flex-direction: column; align-items: flex-start; gap: 0; }
        .rec-icon {
            width: 55px; height: 55px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
            margin-bottom: 15px;
        }
        .rec-ic-blue   { background: rgba(12,171,168,0.1); color: var(--blue); }
        .rec-ic-green  { background: rgba(69,196,134,0.1); color: var(--green); }
        .rec-ic-orange { background: rgba(245,158,11,0.1); color: var(--orange); }
        
        .rec-body { width: 100%; }
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

        /* =========================================================
           MESSAGES PANEL
        ========================================================= */
        .msg-panel-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--white);
        }
        .msg-panel-header h2 { font-size: 18px; font-weight: 800; color: var(--dark); }
        .msg-panel-header a  { font-size: 13px; color: var(--blue); font-weight: 700; text-decoration: none; }

        .msg-list { display: flex; flex-direction: column; }

        .msg-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 18px 25px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            color: inherit;
        }
        .msg-item:last-child { border-bottom: none; }
        .msg-item:hover { background: var(--bg); padding-left: 30px; }
        .msg-item.unread { background: rgba(12,171,168,0.05); border-left: 4px solid var(--blue); }

        .msg-av {
            width: 48px; height: 48px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 700; color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .msg-body { flex: 1; min-width: 0; }
        .msg-top  { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
        .msg-name { font-size: 15px; font-weight: 700; color: var(--dark); }
        .msg-time { font-size: 12px; color: var(--gray); white-space: nowrap; }
        .msg-role { font-size: 12px; color: var(--blue); font-weight: 600; margin-bottom: 4px; }
        .msg-preview {
            font-size: 13px;
            color: var(--gray);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .msg-item.unread .msg-preview { color: var(--dark); font-weight: 600; }
        .unread-dot {
            width: 10px; height: 10px;
            background: var(--blue);
            border-radius: 50%;
            flex-shrink: 0;
            box-shadow: 0 0 8px rgba(12,171,168,0.6);
        }

        .btn-compose {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--blue);
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(12,171,168,0.3);
        }
        .btn-compose:hover { background: var(--color-mid); transform: translateY(-2px); }

        /* =========================================================
           MY PROJECTS (client view)
        ========================================================= */
        .projects-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 45px;
        }
        .project-card {
            flex-direction: row;
            align-items: center;
            padding: 20px 25px !important;
        }
        .project-icon {
            width: 55px; height: 55px;
            border-radius: 16px;
            background: rgba(12,171,168,0.1);
            color: var(--blue);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; flex-shrink: 0;
        }
        .project-body { flex: 1; }
        .project-title { font-size: 16px; font-weight: 800; color: var(--dark); margin-bottom: 6px; }
        .project-meta { font-size: 13px; color: var(--gray); }
        .project-status {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .status-active { background: rgba(69,196,134,0.15); color: var(--green); }
        .status-pending { background: rgba(245,158,11,0.15); color: var(--orange); }
        .status-done { background: var(--bg); color: var(--gray); }
        .project-actions { display: flex; gap: 10px; flex-shrink: 0; }
        
        .btn-sm {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            border: none;
        }
        .btn-sm-blue { background: var(--blue); color: white; box-shadow: 0 4px 10px rgba(12,171,168,0.3); }
        .btn-sm-blue:hover { background: var(--color-mid); transform: translateY(-2px); }
        .btn-sm-ghost { background: var(--bg); color: var(--dark); }
        .btn-sm-ghost:hover { background: var(--border); }

        /* =========================================================
           FREELANCERS GRID
        ========================================================= */
        .freelancers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 45px;
        }
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
        .fl-rate  { font-size: 13px; color: var(--orange); font-weight: 700; }
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
           CATEGORIES
        ========================================================= */
        .categories-row {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 15px;
            margin-bottom: 45px;
        }
        @media(max-width:900px) { .categories-row { grid-template-columns: repeat(3, 1fr); } }
        @media(max-width:500px) { .categories-row { grid-template-columns: repeat(2, 1fr); } }
        
        .cat-card {
            text-align: center; cursor: pointer; text-decoration: none;
            display: flex; flex-direction: column; align-items: center; gap: 12px;
            padding: 25px 15px !important;
        }
        .cat-emoji { font-size: 32px; line-height: 1; margin-bottom: 5px; }
        .cat-card span { font-size: 13px; font-weight: 700; color: var(--dark); }

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
        [dir="rtl"] .project-card, [dir="rtl"] .msg-item { flex-direction: row-reverse; text-align: right; }
        [dir="rtl"] .msg-item:hover { padding-left: 25px; padding-right: 30px; border-left: none; border-right: 4px solid var(--blue); }
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
        <a href="{{ route('client.dashboard') }}" class="active">
            {{ session('lang','fr') === 'ar' ? 'لوحتي' : 'Mon espace' }}
        </a>
        <a href="#" class="btn-explore">
            {{ session('lang','fr') === 'ar' ? 'استكشاف' : 'Explorer' }}
        </a>
        <a href="{{ route('contact') }}" class="nav-item-link" style="display: flex; align-items: center; gap: 8px; color: #1B2559; text-decoration: none; font-size: 14px; font-weight: 600; padding: 10px 16px; border-radius: 12px; transition: background 0.3s ease, color 0.3s ease; font-family: 'Poppins', 'Cairo', sans-serif;" onmouseover="this.style.background='rgba(57, 198, 233, 0.08)'; this.style.color='#39C6E9';" onmouseout="this.style.background='transparent'; this.style.color='#1B2559';">
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

        {{-- Messages icon --}}
        @php
            $derniereConv = \App\Models\Conversion::where('utilisateur_idutilisateur', session('utilisateur_id') ?? session('idutilisateur'))
                ->orWhereHas('service.prestataire', function($q) {
                    $q->where('utilisateur_idutilisateur', session('utilisateur_id') ?? session('idutilisateur'));
                })
                ->latest('date_creation')
                ->first();
        @endphp

        <a href="{{ $derniereConv ? route('messagerie', ['id' => $derniereConv->idconversion]) : '#' }}" 
           style="text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 12px; color: var(--dark); font-weight: 500;">
            <i class="fa-solid fa-comments" style="color: var(--blue2); font-size: 20px;"></i>
            <span>{{ session('lang','fr') === 'ar' ? 'الرسائل' : 'Ma Messagerie' }}</span>
        </a>
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

                {{-- SE DÉCONNECTER (Placé idéalement sous Paramètres) --}}
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
                {{ session('lang','fr') === 'ar' ? '💼 مساحة الزبون' : '💼 Espace Client' }}
            </p>
            <h1>
                @if(session('lang','fr') === 'ar')
                    مرحباً، <span>{{ session('utilisateur_nom', 'Client') }}</span> !
                @else
                    Bonjour, <span>{{ session('utilisateur_nom', 'Client') }}</span> !
                @endif
            </h1>
            <p class="welcome-sub">
                @if(session('lang','fr') === 'ar')
                    ابحث عن المستقل المثالي، أطلق مشروعك، وتواصل مباشرة مع المواهب الموريتانية.
                @else
                    Trouvez le freelancer idéal, lancez votre projet et communiquez directement avec les talents mauritaniens.
                @endif
            </p>
            <div class="welcome-badge-row">
                <span class="w-badge w-badge-green">
                    <i class="fa-solid fa-circle-check" style="font-size:12px"></i>
                    {{ session('lang','fr') === 'ar' ? 'حساب مفعّل' : 'Compte actif' }}
                </span>
                <span class="w-badge w-badge-blue">
                    💼 {{ session('lang','fr') === 'ar' ? 'عميل' : 'Client' }}
                </span>
            </div>
        </div>
    </div>
</section>

{{-- ===================== DASHBOARD LAYOUT ===================== --}}
<div class="dashboard-layout">

    {{-- ── MAIN COLUMN ── --}}
    <div class="main-col">

        {{-- QUICK ACTIONS --}}
        <p class="section-label">
            {{ session('lang','fr') === 'ar' ? 'إجراءات سريعة' : 'ACTIONS RAPIDES' }}
        </p>
        <div class="rec-grid">

            <a href="#section-freelancers" onclick="document.getElementById('section-freelancers').scrollIntoView({behavior: 'smooth'}); return false;" class="rec-card" style="text-decoration: none; display: flex; align-items: center; gap: 15px;">
                <div class="rec-icon rec-ic-blue">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="rec-body">
                    <p class="rec-eyebrow">{{ session('lang','fr') === 'ar' ? 'اكتشف' : 'DÉCOUVRIR' }}</p>
                    <p class="rec-title">{{ session('lang','fr') === 'ar' ? 'استعرض المستقلين' : 'Explorer les freelancers' }}</p>
                    <p class="rec-desc">{{ session('lang','fr') === 'ar' ? 'اعثر على الموهبة المثالية لمشروعك' : 'Trouvez le talent parfait pour votre projet' }}</p>
                </div>
            </a>

        </div>

        {{-- MESSAGES --}}
        @php
            $nomCompletClient = trim(session('utilisateur_nom', '') . ' ' . session('utilisateur_prenom', ''));
            $messagesRecusClient = \App\Models\Message::where('destinataire', $nomCompletClient)
                ->orderBy('idmessage', 'desc')
                ->get();
            $clColors = ['#52D6F4','#7B61FF','#45C486','#F59E0B','#FF7AA8'];
        @endphp

        {{-- CATEGORIES --}}
        {{-- ================= NOUVELLE ZONE CATÉGORIES EN ANIMATION FLUIDE (NON CLIQUABLE) ================= --}}
<div class="categories-flow-section">
    <h2 class="flow-title">
        <span class="gradient-text">Nos Secteurs d'Expertise</span>
        <span class="pulse-dot"></span>
    </h2>
    <p class="flow-subtitle">Aperçu en temps réel des domaines de compétences de nos étudiants freelancers.</p>

    <div class="flow-grid-container">
        <div class="flow-card" style="--accent: #52D6F4; --delay: 1;">
            <div class="shimmer-line"></div>
            <div class="flow-card-content">
                <div class="flow-icon"><i class="fa-solid fa-code"></i></div>
                <h3>Développement Web</h3>
                <p>Applications sur-mesure, sites vitrines et plateformes e-commerce.</p>
            </div>
        </div>

        <div class="flow-card" style="--accent: #7B61FF; --delay: 2;">
            <div class="shimmer-line"></div>
            <div class="flow-card-content">
                <div class="flow-icon"><i class="fa-solid fa-palette"></i></div>
                <h3>Design & UI/UX</h3>
                <p>Création d'identités visuelles, logos et maquettes d'applications modernes.</p>
            </div>
        </div>

        <div class="flow-card" style="--accent: #45C486; --delay: 3;">
            <div class="shimmer-line"></div>
            <div class="flow-card-content">
                <div class="flow-icon"><i class="fa-solid fa-bullhorn"></i></div>
                <h3>Marketing Digital</h3>
                <p>Stratégies de croissance, SEO, rédaction web et gestion de réseaux sociaux.</p>
            </div>
        </div>

        <div class="flow-card" style="--accent: #F59E0B; --delay: 4;">
            <div class="shimmer-line"></div>
            <div class="flow-card-content">
                <div class="flow-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <h3>Soutien & Rédaction</h3>
                <p>Aide aux devoirs, cours particuliers et rapports de stage académiques.</p>
            </div>
        </div>
    </div>
</div>

<style>
/* --- Design et Animations Fluides --- */
.categories-flow-section {
    padding: 50px 30px;
    background: #ffffff;
    border-radius: 24px;
    margin: 30px 0;
    box-shadow: 0 10px 30px rgba(27, 37, 89, 0.02);
    border: 1px solid rgba(229, 231, 235, 0.5);
}
.flow-title {
    font-size: 24px;
    font-weight: 800;
    color: #1B2559;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 6px;
}
.gradient-text {
    background: linear-gradient(135deg, #1B2559 0%, #39C6E9 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.pulse-dot {
    width: 8px;
    height: 8px;
    background-color: #39C6E9;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(57, 198, 233, 0.5);
    animation: pulseDotEffect 2s infinite;
}
.flow-subtitle {
    font-size: 14px;
    color: #8A94A6;
    margin-bottom: 35px;
}
.flow-grid-container {
    display: block;
    text-align: center;
    font-size: 0;
}
.flow-card {
    pointer-events: none; /* REND LA CARTE ABSOLUMENT NON CLIQUABLE */
    user-select: none;    /* Évite la sélection du texte intempestive */
    display: inline-block;
    vertical-align: top;
    width: calc(25% - 20px);
    margin: 10px;
    font-size: 14px;
    background: #F8FCFD;
    border-radius: 20px;
    text-align: left;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(229, 231, 235, 0.6);
    animation: floatAnimation 4s ease-in-out infinite alternate;
    animation-delay: calc(var(--delay) * 0.4s);
}
.flow-card-content {
    padding: 25px 20px;
    position: relative;
    z-index: 2;
}
.flow-icon {
    width: 48px;
    height: 48px;
    background: #ffffff;
    border-radius: 14px;
    display: block;
    text-align: center;
    line-height: 48px;
    margin-bottom: 18px;
    box-shadow: 0 4px 12px rgba(27, 37, 89, 0.03);
}
.flow-icon i {
    font-size: 18px;
    color: var(--accent);
    vertical-align: middle;
}
.flow-card h3 {
    font-size: 16px;
    font-weight: 700;
    color: #1B2559;
    margin-bottom: 8px;
}
.flow-card p {
    font-size: 13px;
    color: #6B7280;
    line-height: 1.5;
}

/* Ligne lumineuse animée en continu */
.shimmer-line {
    position: absolute;
    top: 0; left: -150%;
    width: 50%; height: 100%;
    background: linear-gradient(to right, transparent, rgba(255,255,255,0.7), transparent);
    transform: skewX(-25deg);
    animation: sweepEffect 3.5s infinite ease-in-out;
    animation-delay: calc(var(--delay) * 0.5s);
}

/* --- Déclarations des Animations --- */
@keyframes floatAnimation {
    0% { transform: translateY(0px); box-shadow: 0 5px 15px rgba(27, 37, 89, 0.02); }
    100% { transform: translateY(-10px); box-shadow: 0 15px 25px rgba(27, 37, 89, 0.06); border-color: rgba(57, 198, 233, 0.3); }
}
@keyframes sweepEffect {
    0% { left: -150%; }
    50% { left: 150%; }
    100% { left: 150%; }
}
@keyframes pulseDotEffect {
    0% { box-shadow: 0 0 0 0 rgba(57, 198, 233, 0.5); }
    70% { box-shadow: 0 0 0 8px rgba(57, 198, 233, 0); }
    100% { box-shadow: 0 0 0 0 rgba(57, 198, 233, 0); }
}

/* Rendu responsive simple */
@media (max-width: 992px) { .flow-card { width: calc(50% - 20px); } }
@media (max-width: 576px) { .flow-card { width: 100%; margin: 10px 0; } }
</style>

    </div>
    {{-- ── END MAIN COLUMN ── --}}

    {{-- ── SIDEBAR ── --}}
    <aside class="sidebar">

        {{-- ===== PROFIL CLIENT ===== --}}
        @php
            $nomCompletProfil = trim(($client->nom ?? '') . ' ' . ($client->prenom ?? '')) ?: session('utilisateur_nom', 'Client');
            $emailProfil = $client->email ?? session('utilisateur_email', '');
            $conversationsActives = \App\Models\Message::where(function($q) use ($nomCompletClient) {
                    $q->where('destinataire', $nomCompletClient)
                      ->orWhere('expediteur', $nomCompletClient);
                })
                ->distinct()
                ->count('conversion_idconversion');
        @endphp
        <div class="sidebar-card" style="background:var(--white); border-radius:24px; padding:26px 24px; box-shadow:0 10px 30px rgba(5,22,26,0.06); margin-bottom:24px; text-align:center;">
            <div style="width:72px;height:72px;border-radius:50%;margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:700;color:white;background:linear-gradient(135deg, var(--blue), var(--purple));overflow:hidden;">
                @if($uConnecte && $uConnecte->photo)
                    <img src="{{ asset('storage/' . $uConnecte->photo) }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($nomCompletProfil, 0, 1)) }}
                @endif
            </div>

            <h3 style="font-size:18px;font-weight:700;color:var(--dark);margin-bottom:8px;">{{ $nomCompletProfil }}</h3>

            <p style="font-size:13px;color:var(--gray);display:flex;align-items:center;justify-content:center;gap:6px;margin-bottom:4px;">
                <i class="fa-regular fa-envelope"></i> {{ $emailProfil }}
            </p>

            @if($client && $client->created_at)
                <p style="font-size:13px;color:var(--gray);display:flex;align-items:center;justify-content:center;gap:6px;margin-bottom:14px;">
                    <i class="fa-regular fa-calendar"></i>
                    {{ session('lang','fr') === 'ar' ? 'عضو منذ' : 'Membre depuis le' }} {{ \Carbon\Carbon::parse($client->created_at)->format('d/m/Y') }}
                </p>
            @endif

            <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(12,171,168,0.12);color:var(--blue2);font-weight:700;font-size:13px;padding:6px 16px;border-radius:20px;">
                💼 {{ session('lang','fr') === 'ar' ? 'عميل' : 'Client' }}
            </span>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:22px;">
                <div style="background:var(--bg);border-radius:14px;padding:16px 8px;">
                    <div style="font-size:22px;font-weight:800;color:var(--dark);">{{ $nombreProjets }}</div>
                    <div style="font-size:12px;color:var(--gray);margin-top:4px;">{{ session('lang','fr') === 'ar' ? 'مشاريع منشورة' : 'Projets publiés' }}</div>
                </div>
                <div style="background:var(--bg);border-radius:14px;padding:16px 8px;">
                    <div style="font-size:22px;font-weight:800;color:var(--dark);">{{ $conversationsActives }}</div>
                    <div style="font-size:12px;color:var(--gray);margin-top:4px;">{{ session('lang','fr') === 'ar' ? 'محادثات نشطة' : 'Conversations actives' }}</div>
                </div>
            </div>

            <a href="{{ route('profil.show', session('utilisateur_id') ?? session('idutilisateur')) }}" style="display:block;margin-top:18px;font-size:13px;font-weight:600;color:var(--blue);text-decoration:none;">
                {{ session('lang','fr') === 'ar' ? 'عرض الملف الكامل' : 'Voir le profil complet' }} →
            </a>
        </div>

        {{-- Notifications --}}
        @php
            $nomCompletNotif = trim(session('utilisateur_nom', '') . ' ' . session('utilisateur_prenom', ''));
            $notifsRecentes = \App\Models\Message::where('destinataire', $nomCompletNotif)
                ->orderBy('idmessage', 'desc')
                ->take(5)
                ->get();
        @endphp
        <div class="sidebar-card">
            <h3>
                <i class="fa-regular fa-bell" style="color:var(--orange);margin-right:6px"></i>
                {{ session('lang','fr') === 'ar' ? 'الإشعارات' : 'Notifications' }}
            </h3>
            <p class="sc-sub" style="margin-bottom:14px">{{ session('lang','fr') === 'ar' ? 'آخر التنبيهات' : 'Dernières alertes' }}</p>
            <div class="notif-list">
                @forelse($notifsRecentes as $notif)
                <a href="{{ route('messagerie', ['id' => $notif->conversion_idconversion]) }}" class="notif-item {{ $notif->lu == 0 ? 'unread' : '' }}" style="text-decoration: none; color: inherit;">
                    <span class="notif-icon">💬</span>
                    <span>
                        {{ $notif->expediteur ?? $notif->email }}
                        {{ session('lang','fr') === 'ar' ? 'أرسل لك رسالة' : 'vous a envoyé un message' }}
                    </span>
                </a>
                @empty
                <p style="color:var(--gray); font-size:13px; padding:10px 0;">
                    {{ session('lang','fr') === 'ar' ? 'لا توجد إشعارات حالياً.' : 'Aucune notification pour le moment.' }}
                </p>
                @endforelse
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
        
    </div>
</footer>

<div id="section-freelancers" style="margin-top: 50px; margin-bottom: 50px; padding: 0 10px;">
    <div class="section-label">
        {{ session('lang','fr') === 'ar' ? 'المستقلون المتاحون' : 'Freelancers recommandés' }}
    </div>
    <div class="section-header" style="margin-bottom: 25px;">
        <h2>
            <i class="fa-solid fa-user-tie" style="color:var(--purple, #7B61FF); font-size:22px;"></i>
            {{ session('lang','fr') === 'ar' ? 'المستقلون المتاحون' : 'Freelancers Disponibles' }}
        </h2>
    </div>

<div class="freelancers-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
    @forelse($freelancersData as $freelancer)
    <div class="fl-card"
         @if(!empty($freelancer['user_id']))
             onclick="window.location.href='{{ route('profil.show', $freelancer['user_id']) }}'"
             style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 10px 25px rgba(27, 37, 89, 0.02); border: 1px solid #E9EDF7; display: flex; flex-direction: column; justify-content: space-between; min-height: 250px; cursor: pointer;"
         @else
             style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 10px 25px rgba(27, 37, 89, 0.02); border: 1px solid #E9EDF7; display: flex; flex-direction: column; justify-content: space-between; min-height: 250px;"
         @endif
    >

        <div>
            <div class="fl-head">
                <div class="fl-av" style="background: linear-gradient(135deg, var(--blue), var(--purple)); overflow: hidden;">
                    @if(!empty($freelancer['photo']))
                        <img src="{{ asset('storage/' . $freelancer['photo']) }}" alt="{{ $freelancer['name'] }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 18px;">
                    @else
                        {{ strtoupper(substr($freelancer['name'] ?? '?', 0, 1)) }}
                    @endif
                </div>
                <div class="fl-meta">
                    <h4 style="color: var(--dark); font-weight: 700; font-size: 15px; margin: 0;">{{ $freelancer['name'] }}</h4>
                    <span style="color: #8A94A6; font-size: 12px;">{{ $freelancer['category'] }}</span>
                </div>
            </div>

            <h5 style="color: var(--purple, #7B61FF); font-weight: 600; font-size: 14px; margin-bottom: 8px; margin-top: 0;">{{ $freelancer['role'] }}</h5>
            <p class="fl-desc" style="color: #64748B; font-size: 13px; line-height: 1.5; margin-bottom: 20px; height: 58px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                {{ $freelancer['desc'] }}
            </p>
        </div>

        <div class="fl-footer-row" style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #F4F7FB; padding-top: 15px; margin-top: auto; gap: 15px;">
            <div style="flex-grow: 1; min-width: 0; padding-right: 10px;">
                <span style="color: #8A94A6; font-size: 11px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ session('lang','fr') === 'ar' ? 'السعر يبدأ من' : 'À partir de' }}
                </span>
                <span class="fl-price" style="color: var(--dark); font-weight: 700; font-size: 16px; white-space: nowrap;">
                    {{ $freelancer['price'] }}
                </span>
            </div>

            @if(!empty($freelancer['service_id']))
                <a href="{{ route('messagerie.demarrer', ['service_id' => $freelancer['service_id']]) }}"
                    onclick="event.stopPropagation()"
                    class="btn-contact"
                    style="background-color: var(--purple, #7B61FF); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 120px; min-width: 120px; box-sizing: border-box; text-align: center;">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>{{ session('lang','fr') === 'ar' ? 'تواصل' : 'Contacter' }}</span>
                </a>
            @else
                <button disabled
                   style="background-color: #E9EDF7; color: #8A94A6; border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 500; cursor: not-allowed; display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 120px; min-width: 120px; box-sizing: border-box; text-align: center;">
                    <span>{{ session('lang','fr') === 'ar' ? 'غير متاح' : 'Indisponible' }}</span>
                </button>
            @endif
        </div>

    </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; color: #8A94A6; padding: 40px;">
            {{ session('lang','fr') === 'ar' ? 'لا يوجد مستقلين متاحين حالياً.' : 'Aucun freelancer disponible pour le moment.' }}
        </div>
    @endforelse
</div>

{{-- PHOTO UPLOAD MODAL --}}
<div id="photoUploadModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9998; align-items:center; justify-content:center; backdrop-filter: blur(2px);">
    <div style="background:white; border-radius:20px; padding:32px; width:360px; max-width:90vw; box-shadow:0 20px 60px rgba(5,22,26, 0.2); text-align:center; position:relative;">
        <button type="button" onclick="document.getElementById('photoUploadModal').style.display='none'"
            style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:20px; color:#6E8B95; cursor:pointer;">×</button>
        <div style="width:64px; height:64px; background:#E1F2F5; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:26px;">📷</div>
        <h3 style="font-size:17px; font-weight:700; color:var(--color-dark, #072E33); margin-bottom:6px;">
            {{ session('lang','fr') === 'ar' ? 'إضافة صورة شخصية' : 'Ajouter une photo de profil' }}
        </h3>
        <p style="font-size:13px; color:#6E8B95; margin-bottom:22px; line-height:1.5;">
            {{ session('lang','fr') === 'ar' ? 'اختر صورة واضحة لملفك الشخصي' : 'Choisissez une photo claire pour votre profil' }}
        </p>
        
        <form action="{{ route('profil.photo.update') }}" method="POST" enctype="multipart/form-data" id="laravelPhotoForm">
            @csrf
            <label for="photoInput" style="display:flex; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #E5E7EB; border-radius:14px; padding:24px; cursor:pointer; margin-bottom:18px; transition:border-color 0.2s; gap:8px;" onmouseover="this.style.borderColor='var(--color-mid, #0F929C)'" onmouseout="this.style.borderColor='#E5E7EB'">
                <i class="fa-solid fa-cloud-arrow-up" style="font-size:28px; color:var(--color-mid, #0F929C);"></i>
                <span style="font-size:13px; font-weight:600; color:var(--color-dark, #072E33);">
                    {{ session('lang','fr') === 'ar' ? 'انقر لاختيار صورة' : 'Cliquer pour choisir une image' }}
                </span>
                <span style="font-size:11px; color:#6E8B95;">JPG, PNG — max 2 MB</span>
            </label>
            <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none" onchange="handlePhotoUpload(this)">
            
            <button type="submit"
                style="width:100%; padding:12px; background:var(--color-mid, #0F929C); color:white; border:none; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; font-family:inherit; transition:0.2s;"
                onmouseover="this.style.background='#0C6A74'" onmouseout="this.style.background='var(--color-mid, #0F929C)'">
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

    /* ── Toast ── */
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.innerHTML = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    /* ── Welcome flash message ── */
    @if(session('welcome'))
        setTimeout(() => showToast("✅ {{ session('welcome') }}"), 700);
    @endif

    /* ── CONTROLE INTERACTIF DES PARAMETRES (PULL-DOWN) ── */
function toggleSettingsDropdown(event) {
    event.preventDefault();
    event.stopPropagation();
    const menu = document.getElementById('settingsDropdownMenu');
    menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
}

// Fermer le menu si l'utilisateur clique en dehors
document.addEventListener('click', function(e) {
    const menu = document.getElementById('settingsDropdownMenu');
    const container = document.querySelector('.settings-dropdown-container');
    if (menu && container && !container.contains(e.target)) {
        menu.style.display = 'none';
    }
});

// Fonctions d'affichage de la modale de sécurité
function openDeleteAccountModal() {
    document.getElementById('deleteAccountModal').style.display = 'flex';
    document.getElementById('settingsDropdownMenu').style.display = 'none';
}

function closeDeleteAccountModal() {
    document.getElementById('deleteAccountModal').style.display = 'none';
}

// Fonctions d'affichage et fermeture de la modale de photo
function closePhotoModal() {
    document.getElementById('photoUploadModal').style.display = 'none';
}

function handlePhotoUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validation stricte de la taille maximale (2 Mo)
        if (file.size > 2 * 1024 * 1024) {
            alert('{{ session('lang','fr') === 'ar' ? 'حجم الملف كبير جداً. الحد الأقصى هو 2 ميغابايت.' : 'Le fichier est trop volumineux. Le maximum est de 2 Mo.' }}');
            input.value = '';
            return;
        }

        // Changement de style visuel immédiat pour informer l'utilisateur du chargement
        const label = input.closest('form').querySelector('label');
        if (label) {
            label.style.borderColor = '#E5E7EB';
            label.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin" style="font-size:28px; color:var(--color-mid, #0F929C);"></i>
                <span style="font-size:13px; font-weight:600; color:var(--color-dark, #072E33);">
                    {{ session('lang','fr') === 'ar' ? 'جاري الرفع...' : 'Téléversement en cours...' }}
                </span>
            `;
        }

        // Soumission automatique et réelle vers le contrôleur Laravel pour écraser en BDD
        document.getElementById('laravelPhotoForm').submit();
    }
}

// Bascule d'effet Mode Sombre
function toggleDarkModeEffect(event) {
    event.stopPropagation();
    const body = document.body;
    const isDark = body.classList.toggle('dark-mode-active');
    const switchBg = document.getElementById('darkModeSwitch');
    const handle = document.getElementById('darkModeHandle');
    
    if (isDark) {
        switchBg.style.background = '#39C6E9';
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
        body.style.filter = '';
        document.querySelectorAll('img, i, .avatar').forEach(el => el.style.filter = '');
        
        // RETRAIT : On enlève le background Blue Velvet en mode clair
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