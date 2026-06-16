@php
    // 1. Récupération des données du client (idutilisateur dans votre base sql)
    $client = \App\Models\Utilisateur::find(session('utilisateur_id'));
    
    // 2. Compter ses projets réels via la table conversion (qui possède bien 'utilisateur_idutilisateur')
    $nombreProjets = \App\Models\Conversion::where('utilisateur_idutilisateur', session('utilisateur_id'))->count(); 
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
            --blue:   #52D6F4;
            --blue2:  #39C6E9;
            --dark:   #1B2559;
            --purple: #7B61FF;
            --green:  #45C486;
            --orange: #F59E0B;
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

        .nav-center {
            display: flex;
            align-items: center;
            gap: 24px;
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

        .nav-right { display: flex; align-items: center; gap: 16px; }

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

        /* nav msg icon */
        .nav-msg {
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
            text-decoration: none;
        }
        .nav-msg:hover { background: #F4F7FB; color: var(--blue2); }
        .msg-dot {
            position: absolute;
            top: 7px; right: 7px;
            width: 8px; height: 8px;
            background: var(--blue2);
            border-radius: 50%;
            border: 2px solid white;
        }

        .user-menu { position: relative; }
        .user-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #45C486, #39C6E9);
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
            background: #EAFBF3;
            color: var(--green);
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
           WELCOME HEADER – green/teal gradient for clients
        ========================================================= */
        .welcome-header {
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
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
            background: rgba(69,196,134,0.06);
        }
        .welcome-header::after {
            content: '';
            position: absolute;
            bottom: -30px; left: 25%;
            width: 150px; height: 150px;
            border-radius: 50%;
            background: rgba(82,214,244,0.05);
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
            color: rgba(69,196,134,0.9);
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

        /* Quick action CTA in header */
        .header-cta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }
        .btn-post-project {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 24px;
            background: var(--green);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            text-decoration: none;
            transition: 0.2s;
            box-shadow: 0 6px 20px rgba(69,196,134,0.35);
        }
        .btn-post-project:hover { background: #38b077; transform: translateY(-2px); }

        @media(max-width:768px) {
            .welcome-header { padding: 24px 20px 20px; }
            .welcome-header h1 { font-size: 22px; }
            .header-cta { align-items: flex-start; }
        }

        /* =========================================================
           MAIN LAYOUT
        ========================================================= */
        .dashboard-layout {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 60px;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 28px;
            align-items: start;
        }
        @media(max-width:1100px) {
            .dashboard-layout { grid-template-columns: 1fr; padding: 0 20px; }
        }

        /* =========================================================
           SECTION LABELS & HEADERS
        ========================================================= */
        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 14px;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .section-header h2 { font-size: 18px; font-weight: 700; color: var(--dark); }
        .section-header a  { font-size: 13px; color: var(--blue2); font-weight: 600; text-decoration: none; }
        .section-header a:hover { text-decoration: underline; }

        /* =========================================================
           QUICK ACTIONS CARDS (top 3)
        ========================================================= */
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
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            cursor: pointer;
            transition: box-shadow 0.25s, transform 0.25s;
            text-decoration: none;
        }
        .rec-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,0.09); transform: translateY(-3px); }
        .rec-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .rec-ic-blue   { background: #EAFBFF; color: var(--blue2); }
        .rec-ic-green  { background: #EAFBF3; color: var(--green); }
        .rec-ic-orange { background: #FFF8EC; color: var(--orange); }
        .rec-body { flex: 1; }
        .rec-eyebrow {
            font-size: 10px; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            color: var(--gray); margin-bottom: 4px;
        }
        .rec-title { font-size: 14px; font-weight: 700; color: var(--dark); margin-bottom: 4px; }
        .rec-desc  { font-size: 12px; color: var(--gray); line-height: 1.5; }

        /* =========================================================
           MESSAGES PANEL
        ========================================================= */
        .messages-panel {
            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 36px;
        }
        .msg-panel-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .msg-panel-header h2 { font-size: 16px; font-weight: 700; color: var(--dark); }
        .msg-panel-header a  { font-size: 13px; color: var(--blue2); font-weight: 600; text-decoration: none; }

        .msg-list { display: flex; flex-direction: column; }

        .msg-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 22px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: background 0.15s;
            text-decoration: none;
            color: inherit;
        }
        .msg-item:last-child { border-bottom: none; }
        .msg-item:hover { background: #F8FCFD; }
        .msg-item.unread { background: #F0FBFF; }

        .msg-av {
            width: 44px; height: 44px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 700; color: white;
            flex-shrink: 0;
        }
        .msg-body { flex: 1; min-width: 0; }
        .msg-top  { display: flex; justify-content: space-between; align-items: center; margin-bottom: 3px; }
        .msg-name { font-size: 14px; font-weight: 700; color: var(--dark); }
        .msg-time { font-size: 11px; color: var(--gray); white-space: nowrap; }
        .msg-role { font-size: 11px; color: var(--gray); margin-bottom: 3px; }
        .msg-preview {
            font-size: 13px;
            color: var(--gray);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .msg-item.unread .msg-preview { color: var(--dark); font-weight: 500; }
        .unread-dot {
            width: 9px; height: 9px;
            background: var(--blue2);
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Compose message button */
        .btn-compose {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            background: var(--blue);
            color: white;
            border: none;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: 0.2s;
        }
        .btn-compose:hover { background: #35C4E6; }

        /* =========================================================
           MY PROJECTS (client view)
        ========================================================= */
        .projects-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 36px;
        }
        .project-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: box-shadow 0.2s;
        }
        .project-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.07); }
        .project-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .project-body { flex: 1; }
        .project-title { font-size: 14px; font-weight: 700; color: var(--dark); margin-bottom: 4px; }
        .project-meta  { font-size: 12px; color: var(--gray); }
        .project-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
        }
        .status-active   { background: #EAFBF3; color: var(--green); }
        .status-pending  { background: #FFF8EC; color: var(--orange); }
        .status-done     { background: #F0F2F6; color: var(--gray); }
        .project-actions { display: flex; gap: 8px; flex-shrink: 0; }
        .btn-sm {
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: 0.2s;
            border: none;
        }
        .btn-sm-blue  { background: var(--blue); color: white; }
        .btn-sm-blue:hover { background: #35C4E6; }
        .btn-sm-ghost { background: #F4F7FB; color: var(--dark); }
        .btn-sm-ghost:hover { background: var(--border); }

        /* =========================================================
           FREELANCERS GRID
        ========================================================= */
        .freelancers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 18px;
            margin-bottom: 36px;
        }

        .fl-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 22px;
            transition: box-shadow 0.25s, transform 0.25s;
            cursor: pointer;
        }
        .fl-card:hover { box-shadow: 0 12px 35px rgba(0,0,0,0.09); transform: translateY(-5px); }

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
        .fl-rate  { font-size: 12px; color: var(--orange); font-weight: 600; }
        .fl-price { font-size: 14px; font-weight: 700; color: var(--blue2); }

        .btn-contact {
            width: 100%; margin-top: 12px; padding: 10px;
            border: none; background: var(--blue); color: white;
            border-radius: 10px; font-size: 13px; font-weight: 600;
            cursor: pointer; font-family: inherit; transition: 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 7px;
        }
        .btn-contact:hover { background: #35C4E6; }

        /* =========================================================
           CATEGORIES
        ========================================================= */
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
            border: 1px solid var(--border);
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
            border-color: var(--blue);
            box-shadow: 0 6px 20px rgba(82,214,244,0.15);
            transform: translateY(-4px);
        }
        .cat-emoji { font-size: 28px; line-height: 1; }
        .cat-card span { font-size: 12px; font-weight: 600; color: var(--dark); }

        /* =========================================================
           SIDEBAR
        ========================================================= */
        .sidebar { display: flex; flex-direction: column; gap: 20px; }

        .sidebar-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 24px;
        }
        .sidebar-card h3 { font-size: 15px; font-weight: 700; color: var(--dark); margin-bottom: 4px; }
        .sidebar-card .sc-sub { font-size: 12px; color: var(--gray); margin-bottom: 18px; }

        /* Spending summary */
        .spend-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 14px; background: #F8FCFD; border-radius: 10px; margin-bottom: 8px;
        }
        .spend-label { font-size: 13px; color: var(--dark); font-weight: 500; }
        .spend-val   { font-size: 15px; font-weight: 800; color: var(--dark); }
        .spend-val.highlight { color: var(--blue2); }

        /* Notifications card */
        .notif-list { display: flex; flex-direction: column; gap: 10px; }
        .notif-item {
            display: flex; align-items: flex-start; gap: 10px;
            padding: 10px 12px;
            background: #F8FCFD; border-radius: 10px;
            font-size: 12.5px; color: var(--dark); line-height: 1.5;
            cursor: pointer; transition: background 0.15s;
        }
        .notif-item:hover { background: #EAFBFF; }
        .notif-item.unread { border-left: 3px solid var(--blue2); }
        .notif-icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }

        /* Stats mini */
        .stats-mini { display: flex; flex-direction: column; gap: 12px; }
        .stat-mini-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 14px; background: #F8FCFD; border-radius: 12px;
        }
        .sml { display: flex; align-items: center; gap: 10px; }
        .sml i { font-size: 16px; }
        .sml-label { font-size: 13px; font-weight: 500; color: var(--dark); }
        .sml-val { font-size: 16px; font-weight: 800; color: var(--dark); }

        /* Post project CTA sidebar */
        .cta-card {
            background: linear-gradient(135deg, #0f3d2e 0%, #1a6b4a 100%);
            border-radius: 18px;
            padding: 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-card::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(69,196,134,0.15);
        }
        .cta-card h4 { font-size: 16px; font-weight: 700; color: white; margin-bottom: 8px; position: relative; }
        .cta-card p  { font-size: 12px; color: rgba(255,255,255,0.7); margin-bottom: 18px; position: relative; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 11px 22px; background: var(--green);
            color: white; border: none; border-radius: 10px;
            font-size: 13px; font-weight: 600; cursor: pointer;
            font-family: inherit; transition: 0.2s; position: relative;
            text-decoration: none;
        }
        .btn-cta:hover { background: #38b077; }

        /* =========================================================
           MODAL – Post a project
        ========================================================= */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 500;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal-box {
            background: white;
            border-radius: 20px;
            padding: 36px 32px;
            width: 100%;
            max-width: 520px;
            margin: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        .modal-box h3 { font-size: 20px; font-weight: 700; color: var(--dark); margin-bottom: 20px; }
        .modal-close {
            float: right; margin-top: -4px;
            background: none; border: none;
            font-size: 20px; color: var(--gray);
            cursor: pointer; transition: color 0.2s;
        }
        .modal-close:hover { color: var(--dark); }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 7px; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%; padding: 12px 16px;
            border: 1.5px solid #E5E7EB; border-radius: 10px;
            font-size: 14px; font-family: inherit; color: var(--dark);
            outline: none; transition: border-color 0.2s; background: white;
            resize: vertical;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus { border-color: var(--blue); }
        .btn-submit {
            width: 100%; padding: 14px;
            background: var(--green); border: none; border-radius: 12px;
            color: white; font-size: 15px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: 0.2s;
            margin-top: 6px;
        }
        .btn-submit:hover { background: #38b077; }

        /* =========================================================
           FOOTER
        ========================================================= */
        .footer {
            background: var(--dark);
            color: white;
            padding: 30px 60px 20px;
            margin-top: 40px;
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
        .footer-copy { font-size: 13px; color: #6B7280; }
        .footer-made { font-size: 13px; color: #6B7280; }
        @media(max-width:600px) { .footer { padding: 24px 20px; } }

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
        [dir="rtl"] .stat-mini-row { flex-direction: row-reverse; }
        [dir="rtl"] .msg-top      { flex-direction: row-reverse; }
        [dir="rtl"] .msg-item     { flex-direction: row-reverse; }
        [dir="rtl"] .project-card { flex-direction: row-reverse; }
        [dir="rtl"] .toast        { right: auto; left: 28px; }
        [dir="rtl"] .user-dropdown { right: auto; left: 0; }
        [dir="rtl"] .user-dropdown a,
        [dir="rtl"] .user-dropdown button.dd-btn { text-align: right; flex-direction: row-reverse; }
        [dir="rtl"] .header-cta   { align-items: flex-start; }
        [dir="rtl"] .notif-item.unread { border-left: none; border-right: 3px solid var(--blue2); }
        [dir="rtl"] .modal-close  { float: left; }
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
        {{-- Essaye de récupérer la dernière conversation pour passer son ID --}}
@php
    $derniereConv = \App\Models\Conversion::where('utilisateur_idutilisateur', session('utilisateur_id'))
        ->orWhereHas('service.prestataire', function($q) {
            $q->where('utilisateur_idutilisateur', session('utilisateur_id'));
        })
        ->latest('date_creation') // ou la colonne de date que tu as
        ->first();
@endphp

<a href="{{ $derniereConv ? route('messagerie', ['id' => $derniereConv->idconversion]) : '#' }}" 
   style="text-decoration: none; display: flex; align-items: center; gap: 10px; padding: 12px; color: var(--dark); font-weight: 500;">
    <i class="fa-solid fa-comments" style="color: var(--blue2); font-size: 20px;"></i>
    <span>{{ session('lang','fr') === 'ar' ? 'الرسائل' : 'Ma Messagerie' }}</span>
</a>

        {{-- Notification bell --}}
        <button class="nav-bell" title="{{ session('lang','fr') === 'ar' ? 'الإشعارات' : 'Notifications' }}">
            <i class="fa-regular fa-bell"></i>
            <span class="bell-dot"></span>
        </button>

        {{-- User avatar + dropdown --}}
        <div class="user-menu">
            <div class="user-avatar" onclick="toggleDropdown()"
                 title="{{ session('utilisateur_nom', '') }}">
                {{ strtoupper(substr(session('utilisateur_nom', 'C'), 0, 1)) }}
            </div>
            <div class="user-dropdown" id="userDropdown">
                <div class="dd-header">
                    <div class="dd-name">{{ session('utilisateur_nom', '') }}</div>
                    <span class="dd-role">💼 {{ session('lang','fr') === 'ar' ? 'عميل' : 'Client' }}</span>
                    <div class="dd-email">{{ session('utilisateur_email', '') }}</div>
                </div>
<a href="{{ route('messagerie.index') }}" title="{{ session('lang','fr') === 'ar' ? 'الرسائل' : 'Messages' }}" style="text-decoration: none; display: flex; align-items: center; gap: 8px; color: inherit; cursor: pointer; outline: none; margin-left: 0px; margin-right: 12px;">
    <i class="fa-solid fa-envelope"></i>
    <span>{{ session('lang','fr') === 'ar' ? 'الرسائل' : 'Messagerie' }}</span>
</a>
               <div class="settings-dropdown-container" style="position: relative; display: inline-block; z-index: 9999;">
    <a href="javascript:void(0)" onclick="toggleSettingsDropdown(event)" style="text-decoration: none; display: flex; align-items: center; gap: 8px; color: inherit; cursor: pointer; position: relative; z-index: 10001; outline: none;">
        <i class="fa-solid fa-gear"></i>
        {{ session('lang','fr') === 'ar' ? 'الإعدادات' : 'Paramètres' }}
    </a>
    
    <div id="settingsDropdownMenu" style="display: none; position: absolute; right: 0; top: calc(100% + 12px); width: 260px; background: #ffffff; border-radius: 14px; box-shadow: 0 10px 25px rgba(27, 37, 89, 0.15); border: 1px solid rgba(229, 231, 235, 0.8); z-index: 10000; overflow: hidden; padding: 12px; text-align: left; font-family: 'Poppins', sans-serif;">
        
        <div style="padding-bottom: 10px; border-bottom: 1px solid #E5E7EB; margin-bottom: 8px;">
            <div style="font-size: 11px; text-transform: uppercase; color: #8A94A6; font-weight: 700; margin-bottom: 4px;">
                {{ session('lang','fr') === 'ar' ? 'حساب العميل' : 'Mon Espace Client' }}
           <!-- EN-TÊTE : PROFIL DU CLIENT D'APRÈS LA BASE -->
<div style="padding-bottom: 10px; border-bottom: 1px solid #E5E7EB; margin-bottom: 8px;">
    <div style="font-size: 11px; text-transform: uppercase; color: #8A94A6; font-weight: 700; margin-bottom: 4px;">
        {{ session('lang','fr') === 'ar' ? 'حساب العميل' : 'Espace Client' }}
    </div>
    <div style="font-size: 14px; font-weight: 700; color: #1B2559;">
        {{ $client->prenom ?? 'Client' }} {{ $client->nom ?? 'EtudServ' }}
    </div>
    <div style="font-size: 12px; color: #6B7280; word-break: break-all; margin-top: 2px;">
        {{ $client->email ?? 'Non défini' }}
    </div>
    <div style="font-size: 11px; color: #A3AED0; margin-top: 5px;">
        <i class="fa-regular fa-calendar-days"></i> {{ session('lang','fr') === 'ar' ? 'انضم في: ' : 'Inscrit le : ' }} 
        {{ $client && $client->date_inscription ? \Carbon\Carbon::parse($client->date_inscription)->format('d/m/Y') : date('d/m/Y') }}
    </div>
    <div style="display: flex; align-items: center; gap: 6px; background: #F4F7FB; padding: 6px 10px; border-radius: 6px; margin-top: 8px; font-size: 12px; font-weight: 600; color: #1B2559;">
        <i class="fa-solid fa-folder-open" style="color: #39C6E9;"></i>
        <span>{{ $nombreProjets }} {{ session('lang','fr') === 'ar' ? 'المشاريع' : 'projet(s) créé(s)' }}</span>
    </div>
</div>

        
        <div onclick="toggleDarkModeEffect(event)" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 8px; color: #1B2559; cursor: pointer; font-size: 13px; font-weight: 600; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background='rgba(57, 198, 233, 0.08)'" onmouseout="this.style.background='transparent'">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-moon" style="color: #7B61FF; font-size: 14px;"></i>
                <span>{{ session('lang','fr') === 'ar' ? 'الوضع الداكن' : 'Mode Sombre' }}</span>
            </div>
            <div id="darkModeSwitch" style="width: 32px; height: 18px; background: #E5E7EB; border-radius: 20px; position: relative; transition: background 0.3s;">
                <div id="darkModeHandle" style="width: 12px; height: 12px; background: white; border-radius: 50%; position: absolute; top: 3px; left: 3px; transition: left 0.3s;"></div>
            </div>
        </div>
        
        <hr style="border: 0; border-top: 1px solid rgba(229, 231, 235, 0.6); margin: 6px 0;">
        
        <div onclick="openDeleteAccountModal()" style="display: flex; align-items: center; gap: 12px; padding: 10px 8px; color: #EF4444; cursor: pointer; font-size: 13px; font-weight: 600; border-radius: 8px; transition: background 0.2s;" onmouseover="this.style.background='rgba(239, 68, 68, 0.08)'" onmouseout="this.style.background='transparent'">
            <i class="fa-solid fa-trash-can" style="font-size: 14px;"></i>
            <span>{{ session('lang','fr') === 'ar' ? 'حذف الحساب' : 'Supprimer le compte' }}</span>
        </div>
    </div>
</div>
</div>

<div id="deleteAccountModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(27, 37, 89, 0.4); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(3px);">
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
        <span class="footer-made">
            {{ session('lang','fr') === 'ar' ? 'صُنع بـ ❤️ في موريتانيا' : 'Fait avec ❤️ en Mauritanie' }}
        </span>
    </div>
</footer>

<div id="section-freelancers" style="margin-top: 50px; margin-bottom: 50px; padding: 0 30px;">
    <h3 style="color: var(--dark); font-weight: 700; font-size: 22px; margin-bottom: 25px;">
        {{ session('lang','fr') === 'ar' ? 'المستقلون المتاحون' : '🚀 Freelancers Disponibles' }}
    </h3>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
        @forelse($freelancersData as $freelancer)
            <div style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 10px 25px rgba(27, 37, 89, 0.02); border: 1px solid #E9EDF7; display: flex; flex-direction: column; justify-content: space-between; min-height: 240px;">
                
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 48px; height: 48px; border-radius: 50%; background-color: {{ $freelancer['color'] }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
                            {{ strtoupper(substr($freelancer['name'], 0, 1)) }}
                        </div>
                        <div>
                            <h4 style="color: var(--dark); font-weight: 700; font-size: 15px; margin: 0;">{{ $freelancer['name'] }}</h4>
                            <span style="color: #8A94A6; font-size: 12px;">{{ $freelancer['category'] }}</span>
                        </div>
                    </div>

                    <h5 style="color: var(--purple); font-weight: 600; font-size: 14px; margin-bottom: 8px;">{{ $freelancer['role'] }}</h5>
                    <p style="color: #64748B; font-size: 13px; line-height: 1.5; margin-bottom: 20px; height: 58px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                        {{ $freelancer['desc'] }}
                    </p>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #F4F7FB; padding-top: 15px;">
                    <div>
                        <span style="color: #8A94A6; font-size: 11px; display: block;">{{ session('lang','fr') === 'ar' ? 'السعر يبدأ من' : 'À partir de' }}</span>
                        <span style="color: var(--dark); font-weight: 700; font-size: 16px;">{{ $freelancer['price'] }} MRU</span>
                    </div>

                    @if(isset($freelancer['service_id']) && $freelancer['service_id'])
                        <a href="{{ route('messagerie.demarrer', ['service_id' => $freelancer['service_id']]) }}" 
                           style="background-color: var(--purple); color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-paper-plane"></i>
                            {{ session('lang','fr') === 'ar' ? 'تواصل' : 'Contacter' }}
                        </a>
                    @else
                        <button disabled style="background-color: #E9EDF7; color: #8A94A6; border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 500; cursor: not-allowed;">
                            {{ session('lang','fr') === 'ar' ? 'غير متاح' : 'Indisponible' }}
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

// Bascule d'effet Mode Sombre
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
</script>

</body>
</html>
