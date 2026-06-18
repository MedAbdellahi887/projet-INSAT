<?php
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EtudServ</title>
    <!-- ✅ FIX: Font Awesome loaded -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        /* ===== PALETTE TEAL (extraite de la photo) ===== */
        :root {
            --color-deepest:  #05161A; /* Noir teal profond */
            --color-dark:     #072E33; /* Vert sombre */
            --color-mid:      #0C7075; /* Teal moyen */
            --color-accent:   #0CABA8; /* Teal vif (CTA) */
            --color-light:    #6DA5C0; /* Bleu-gris clair */
            --color-slate:    #294D61; /* Bleu ardoise */
            --color-bg:       #EDF6F9; /* Fond page */
            --color-surface:  #F5FBFC; /* Surface cartes */
            --color-muted:    #3D6A7A; /* Texte secondaire */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background:#EDF6F9;
            color: #05161A;
        }

        /* ================= PAGE SYSTEM ================= */
        .page { display: none; }
        .page.active { display: block; }

        /* ================= NAVBAR ================= */
        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 80px;
            background: #072E33;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 15px rgba(0,0,0,0.06);
        }

        .logo { font-size: 28px; font-weight: 700; cursor: pointer; }
        .logo-blue { color: #6DA5C0; }
        .logo-dark { color: #05161A; font-weight: 500; }

        .nav-links { display: flex; gap: 40px; align-items: center; }

        .nav-links a {
            text-decoration: none;
            color: #05161A;
            font-size: 16px;
            font-weight: 500;
            transition: 0.3s;
            cursor: pointer;
        }
        .nav-links a:hover { color: #0CABA8; }

        .join-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 40px;
            background: #0CABA8;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(12,171,168,0.35);
        }
        .join-btn:hover { transform: translateY(-3px); background: #0A9996; }

        @media(max-width:900px) {
            .navbar { flex-direction: column; gap: 20px; padding: 20px; }
            .nav-links { gap: 20px; flex-wrap: wrap; justify-content: center; }
        }

        /* ================= VIDEO HERO ================= */
        .video-hero {
            position: relative;
            width: 100%;
            height: 85vh;
            min-height: 500px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-hero video {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            object-fit: cover;
            z-index: 0;
        }

        /* Dark overlay so text is readable */
        .video-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(5, 22, 26, 0.55) 0%,
                rgba(5, 22, 26, 0.70) 100%
            );
            z-index: 1;
        }

        .video-hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            padding: 0 30px;
            max-width: 850px;
        }

        .video-hero-content .badge {
            background: rgba(255,255,255,0.15);
            color: white;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.25);
            margin-bottom: 25px;
        }

        .video-hero-content h1 {
            font-size: 64px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }

        .video-hero-content h1 span { color: #0CABA8; }

        .video-hero-content p {
            font-size: 20px;
            line-height: 1.7;
            color: rgba(255,255,255,0.85);
            margin-bottom: 35px;
        }

        .video-hero-btns {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .vbtn-primary {
            padding: 16px 36px;
            background: #0CABA8;
            color: white;
            border: none;
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Poppins', sans-serif;
            box-shadow: 0 8px 25px rgba(12,171,168,0.4);
        }
        .vbtn-primary:hover { background: #0A9996; transform: translateY(-3px); }

        .vbtn-secondary {
            padding: 16px 36px;
            background: rgba(255,255,255,0.15);
            color: white;
            border: 2px solid rgba(255,255,255,0.5);
            border-radius: 40px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Poppins', sans-serif;
            backdrop-filter: blur(8px);
        }
        .vbtn-secondary:hover { background: rgba(255,255,255,0.25); transform: translateY(-3px); }

        /* Scroll indicator */
        .scroll-down {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            color: rgba(255,255,255,0.6);
            font-size: 13px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            animation: bounce 2s infinite;
        }
        .scroll-down i { font-size: 20px; }

        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(8px); }
        }

        @media(max-width: 768px) {
            .video-hero { height: 70vh; }
            .video-hero-content h1 { font-size: 36px; }
            .video-hero-content p { font-size: 16px; }
        }

        /* ================= HERO ================= */
        .hero {
            width: 100%;
            min-height: 90vh;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 80px;
            gap: 60px;
            position: relative;
            overflow: hidden;
        }

        /* ✅ FIX: Background video */
        .hero-video-bg {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            z-index: 0;
            opacity: 0.08;
        }

        .hero-content { flex: 1; position: relative; z-index: 1; }

        .badge {
            display: inline-block;
            padding: 10px 18px;
            background: #E0F4F4;
            color: #0CABA8;
            border-radius: 30px;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .hero-content h1 {
            font-size: 64px;
            line-height: 1.1;
            font-weight: 800;
            color: #05161A;
            margin-bottom: 25px;
        }
        .hero-content h1 span { color: #0CABA8; }

        .hero-content p {
            font-size: 18px;
            line-height: 1.7;
            color: #294D61;
            margin-bottom: 35px;
            max-width: 600px;
        }
        /* ===== PAGE LOADER ===== */
#pageLoader {
    position: fixed; inset: 0; z-index: 9999;
    background: #EDF6F9;
    display: flex; align-items: center; justify-content: center;
    transition: opacity 0.5s ease, visibility 0.5s ease;
}
#pageLoader.hidden { opacity: 0; visibility: hidden; pointer-events: none; }

.loader-inner { text-align: center; }
.loader-logo {
    font-size: 48px; font-weight: 800; margin-bottom: 30px;
    animation: logoPulse 1.2s ease-in-out infinite alternate;
}
.loader-blue { color: #0CABA8; }
.loader-dark { color: #05161A; }
@keyframes logoPulse {
    from { transform: scale(1); opacity: 0.8; }
    to   { transform: scale(1.06); opacity: 1; }
}
.loader-bar-wrap {
    width: 200px; height: 4px; background: #C5E8E8;
    border-radius: 99px; margin: 0 auto 18px; overflow: hidden;
}
.loader-bar {
    height: 100%; width: 0%;
    background: linear-gradient(90deg, #0CABA8, #294D61);
    border-radius: 99px;
    animation: loadBar 1.6s ease forwards;
}
@keyframes loadBar { to { width: 100%; } }
.loader-text { font-size: 14px; color: #3D6A7A; font-weight: 500; }

/* ===== HAMBURGER MOBILE ===== */
.hamburger {
    display: none; flex-direction: column; gap: 5px;
    background: none; border: none; cursor: pointer; padding: 6px;
}
.hamburger span {
    display: block; width: 26px; height: 3px;
    background: #05161A; border-radius: 3px; transition: 0.3s;
}
.hamburger.open span:nth-child(1) { transform: translateY(8px) rotate(45deg); }
.hamburger.open span:nth-child(2) { opacity: 0; }
.hamburger.open span:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }

@media(max-width: 900px) {
    .navbar { padding: 18px 24px; flex-direction: row; position: relative; }
    .hamburger { display: flex; }
    .nav-links {
        display: none; flex-direction: column; gap: 0;
        position: absolute; top: 100%; left: 0; right: 0;
        background: #EDF6F9; box-shadow: 0 10px 30px rgba(0,0,0,0.10);
        padding: 10px 0 20px; z-index: 200;
        border-top: 1px solid #F0F4F8;
    }
    .nav-links.open { display: flex; }
    .nav-links a { padding: 14px 28px; font-size: 16px; border-bottom: 1px solid #F5F7FA; }
    .nav-links .join-btn { margin: 14px 28px 0; width: calc(100% - 56px); }
}

@media(max-width: 600px) {
    .hero { padding: 40px 20px; min-height: auto; }
    .hero-content h1 { font-size: 34px; }
    .hero-content p { font-size: 15px; }
    .hero-image { display: none; }
    .search-box { flex-direction: column; padding: 14px; }
    .search-box select, .search-box input, .search-box button { width: 100%; }
    .video-hero { height: 60vh; }
    .video-hero-content h1 { font-size: 28px; }
    .video-hero-content p { font-size: 14px; }
    .vbtn-primary, .vbtn-secondary { padding: 13px 22px; font-size: 14px; }
    .services { padding: 50px 18px; }
    .services-header h2 { font-size: 28px; }
    .section { padding: 50px 18px; }
    .section h1 { font-size: 28px; }
    .freelancers-page { padding: 30px 16px; }
    .filter-btn { padding: 8px 14px; font-size: 12px; }
    .about-page { padding: 40px 18px; }
    .contact-page { padding: 40px 18px; }
    .footer { padding: 40px 18px 20px; }
    .footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
    .modal-box { padding: 28px 20px; }
}

/* ===== SCROLL ANIMATIONS ===== */
.scroll-section {
    opacity: 0; transform: translateY(40px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}
.scroll-section.visible { opacity: 1; transform: translateY(0); }

.scroll-section .service-card,
.scroll-section .card {
    opacity: 0; transform: translateY(30px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.scroll-section.visible .service-card,
.scroll-section.visible .card { opacity: 1; transform: translateY(0); }
.scroll-section.visible .service-card:nth-child(1), .scroll-section.visible .card:nth-child(1) { transition-delay: 0.05s; }
.scroll-section.visible .service-card:nth-child(2), .scroll-section.visible .card:nth-child(2) { transition-delay: 0.12s; }
.scroll-section.visible .service-card:nth-child(3), .scroll-section.visible .card:nth-child(3) { transition-delay: 0.19s; }
.scroll-section.visible .service-card:nth-child(4), .scroll-section.visible .card:nth-child(4) { transition-delay: 0.26s; }
.scroll-section.visible .service-card:nth-child(5) { transition-delay: 0.33s; }
.scroll-section.visible .service-card:nth-child(6) { transition-delay: 0.40s; }




        .hero-features { display: flex; gap: 30px; flex-wrap: wrap; margin-bottom: 45px; }
        .feature { color: #294D61; font-size: 15px; font-weight: 500; }

        .search-box {
            width: 100%;
            max-width: 680px;
            background: white;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.08);
            gap: 12px;
        }
        .search-box select, .search-box input {
            border: none; outline: none;
            background: transparent;
            font-size: 15px; color: #05161A; padding: 10px;
            font-family: 'Poppins', sans-serif;
        }
        .search-box input { flex: 1; }
        .search-box button {
            border: none; background: #0CABA8; color: white;
            padding: 13px 25px; border-radius: 12px;
            font-size: 15px; font-weight: 600; cursor: pointer; transition: 0.3s;
            white-space: nowrap;
        }
        .search-box button:hover { background: #0A9996; }

        /* ✅ FIX: Hero image section with animated circle + glass cards */
        .hero-image {
            flex: 1;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 550px;
            z-index: 1;
        }

        .main-circle {
            width: 340px; height: 340px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0CABA8, #294D61);
            box-shadow: 0 0 80px rgba(12,171,168,0.45);
            animation: float 4s ease-in-out infinite;
        }

        .glass-card {
            position: absolute;
            background: rgba(237,246,249,0.85);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.6);
            padding: 16px 22px;
            border-radius: 18px;
            font-size: 15px;
            font-weight: 600;
            color: #05161A;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            white-space: nowrap;
        }
        .card-1 { top: 70px; left: 20px; }
        .card-2 { top: 100px; right: 10px; }
        .card-3 { bottom: 70px; left: 80px; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @media(max-width:1100px) {
            .hero { flex-direction: column; text-align: center; padding: 50px 30px; }
            .hero-content h1 { font-size: 46px; }
            .hero-features { justify-content: center; }
            .search-box { flex-direction: column; }
            .search-box button { width: 100%; }
            .hero-image { margin-top: 40px; min-height: 400px; }
            .main-circle { width: 260px; height: 260px; }
        }

        /* ================= SERVICES ================= */
        .services { width: 100%; padding: 100px 80px; background: #EDF6F9; }

        .services-header { text-align: center; margin-bottom: 70px; }
        .services-header h2 { font-size: 52px; font-weight: 800; color: #05161A; margin-bottom: 15px; }
        .services-header p { font-size: 20px; color: #3D6A7A; }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .service-card {
            background: #F5FBFC;
            padding: 40px;
            border-radius: 30px;
            transition: 0.4s;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.03);
            cursor: pointer;
        }
        .service-card:hover { transform: translateY(-10px); box-shadow: 0 25px 45px rgba(0,0,0,0.10); }

        .service-icon {
            width: 75px; height: 75px;
            border-radius: 22px;
            display: flex; justify-content: center; align-items: center;
            font-size: 30px; margin-bottom: 28px; font-weight: 700;
        }
        .blue { background: #E0F4F4; color: #0CABA8; }
        .purple { background: #F2EEFF; color: #3A6B7A; }
        .green { background: #E0F4F4; color: #45C486; }
        .pink { background: #FFF0F4; color: #FF7AA8; }
        .red { background: #FFF1F1; color: #FF6B6B; }
        .cyan { background: #E0F4F4; color: #0A9996; }

        .service-card h3 { font-size: 22px; font-weight: 700; color: #05161A; margin-bottom: 10px; }
        .service-card p { font-size: 15px; line-height: 1.6; color: #3D6A7A; }

        @media(max-width:900px) {
            .services { padding: 70px 25px; }
            .services-header h2 { font-size: 36px; }
        }

        /* ================= WHY SECTION ================= */
        .section {
            width: 90%; max-width: 1200px;
            margin: auto; padding: 80px 0; text-align: center;
        }
        .section h1 { font-size: 44px; color: #05161A; margin-bottom: 20px; font-weight: 700; }
        .section p.description {
            max-width: 800px; margin: 0 auto 40px;
            font-size: 18px; line-height: 1.8; color: #8a8a8a;
        }
        .btn {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 14px 30px; border-radius: 50px; background: #EDF6F9;
            color: #7a7a7a; text-decoration: none; font-size: 16px;
            font-weight: 600; box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: 0.3s; margin-bottom: 60px;
        }
        .btn:hover { transform: translateY(-3px); background: #E0F4F4; }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }
        .card {
            background: #EDF6F9; padding: 35px 25px;
            border-radius: 25px; text-align: left;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05); transition: 0.3s;
        }
        .card:hover { transform: translateY(-8px); }
        .icon {
            width: 60px; height: 60px; border-radius: 16px;
            background: #E0F4F4; display: flex;
            align-items: center; justify-content: center;
            margin-bottom: 22px; color: #0CABA8; font-size: 24px;
        }
        .card h3 { font-size: 20px; margin-bottom: 12px; color: #05161A; }
        .card p { color: #888; line-height: 1.7; font-size: 15px; }

        /* ================= FREELANCERS PAGE ================= */
        .freelancers-page { padding: 60px 80px; }
        .freelancers-page h2 { font-size: 42px; font-weight: 800; color: #05161A; margin-bottom: 10px; }
        .freelancers-page > p { color: #3D6A7A; font-size: 16px; margin-bottom: 40px; }

        .filter-bar {
            display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 40px;
        }
        .filter-btn {
            padding: 10px 22px; border: 2px solid #C5DDE5;
            border-radius: 30px; background: white;
            font-size: 14px; font-weight: 500; cursor: pointer;
            transition: 0.3s; font-family: 'Poppins', sans-serif; color: #05161A;
        }
        .filter-btn:hover, .filter-btn.active { background: #0CABA8; color: white; border-color: #0CABA8; }

        .freelancers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        .freelancer-card {
            background: #F5FBFC; border-radius: 25px; padding: 30px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06); transition: 0.3s;
            border: 1px solid rgba(0,0,0,0.04);
        }
        .freelancer-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.10); }

        .fl-header { display: flex; align-items: center; gap: 15px; margin-bottom: 18px; }
        .fl-avatar {
            width: 55px; height: 55px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 700; color: white; flex-shrink: 0;
        }
        .fl-info h4 { font-size: 16px; font-weight: 700; color: #05161A; }
        .fl-info span { font-size: 13px; color: #3D6A7A; }

        .fl-badge {
            display: inline-block; padding: 4px 12px;
            border-radius: 20px; font-size: 12px; font-weight: 600;
            margin-bottom: 12px;
        }
        .badge-student { background: #E0F4F4; color: #0CABA8; }
        .badge-pro { background: #F2EEFF; color: #294D61; }

        .fl-title { font-size: 15px; font-weight: 600; color: #05161A; margin-bottom: 8px; }
        .fl-desc { font-size: 13px; color: #3D6A7A; line-height: 1.6; margin-bottom: 18px; }

        .fl-footer { display: flex; justify-content: space-between; align-items: center; }
        .fl-rating { font-size: 13px; color: #F59E0B; font-weight: 600; }
        .fl-price { font-size: 15px; font-weight: 700; color: #0CABA8; }

        .fl-tags { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 15px; }
        .fl-tag {
            padding: 4px 10px; background: #DCF0F2;
            border-radius: 8px; font-size: 11px; color: #294D61; font-weight: 500;
        }

        .contact-btn {
            width: 100%; margin-top: 15px; padding: 12px;
            border: none; background: #0CABA8; color: white;
            border-radius: 12px; font-size: 14px; font-weight: 600;
            cursor: pointer; transition: 0.3s; font-family: 'Poppins', sans-serif;
        }
        .contact-btn:hover { background: #0A9996; }

        @media(max-width:768px) {
            .freelancers-page { padding: 40px 20px; }
            .freelancers-page h2 { font-size: 30px; }
        }

        /* ================= ABOUT PAGE ================= */
        .about-page { padding: 80px; max-width: 1100px; margin: auto; }
        .about-page h2 { font-size: 48px; font-weight: 800; color: #05161A; margin-bottom: 20px; }
        .about-page p { font-size: 17px; color: #294D61; line-height: 1.8; margin-bottom: 30px; max-width: 750px; }
        .team-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px; margin-top: 50px; }
        .team-card {
            background: #F5FBFC; border-radius: 20px; padding: 30px 20px;
            text-align: center; box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }
        .team-avatar {
            width: 70px; height: 70px; border-radius: 50%;
            margin: 0 auto 15px; display: flex; align-items: center;
            justify-content: center; font-size: 28px; font-weight: 700; color: white;
        }
        .team-card h4 { font-size: 16px; font-weight: 700; color: #05161A; margin-bottom: 5px; }
        .team-card span { font-size: 13px; color: #3D6A7A; }
        @media(max-width:768px) { .about-page { padding: 40px 20px; } .about-page h2 { font-size: 32px; } }

        /* ================= CONTACT PAGE ================= */
        .contact-page { padding: 80px; max-width: 700px; margin: auto; }
        .contact-page h2 { font-size: 42px; font-weight: 800; color: #05161A; margin-bottom: 10px; }
        .contact-page > p { color: #3D6A7A; margin-bottom: 40px; }
        .contact-form { display: flex; flex-direction: column; gap: 18px; }
        .contact-form input, .contact-form textarea, .contact-form select {
            padding: 15px 18px; border: 2px solid #C5DDE5; border-radius: 14px;
            font-size: 15px; outline: none; font-family: 'Poppins', sans-serif;
            color: #05161A; background: white; transition: 0.3s;
        }
        .contact-form input:focus, .contact-form textarea:focus { border-color: #0CABA8; }
        .contact-form textarea { resize: vertical; min-height: 140px; }
        .contact-form button {
            padding: 16px; border: none; background: #0CABA8; color: white;
            border-radius: 14px; font-size: 16px; font-weight: 600;
            cursor: pointer; transition: 0.3s; font-family: 'Poppins', sans-serif;
        }
        .contact-form button:hover { background: #0A9996; transform: translateY(-2px); }
        .contact-success {
            display: none; text-align: center; padding: 30px;
            background: #E0F4F4; border-radius: 18px; color: #0CABA8;
            font-size: 18px; font-weight: 600; margin-top: 20px;
        }
        @media(max-width:768px) { .contact-page { padding: 40px 20px; } .contact-page h2 { font-size: 30px; } }

        /* ================= FOOTER ================= */
        .footer {
            background: #05161A; color: white;
            padding: 70px 80px 30px;
        }
        .footer-grid {
            display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 50px; margin-bottom: 50px;
        }
        .footer-logo { font-size: 24px; font-weight: 700; margin-bottom: 15px; }
        .footer-logo span { color: #0CABA8; }
        .footer-desc { font-size: 14px; color: #9CA3AF; line-height: 1.8; margin-bottom: 25px; }
        .footer-socials { display: flex; gap: 12px; }
        .social-btn {
            width: 38px; height: 38px; border-radius: 10px;
            background: rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 16px; cursor: pointer;
            transition: 0.3s; text-decoration: none;
        }
        .social-btn:hover { background: #0CABA8; }
        .footer-col h4 { font-size: 15px; font-weight: 600; margin-bottom: 20px; color: white; }
        .footer-col a {
            display: block; color: #9CA3AF; text-decoration: none;
            font-size: 14px; margin-bottom: 10px; transition: 0.3s; cursor: pointer;
        }
        .footer-col a:hover { color: #0CABA8; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-top: 25px; display: flex;
            justify-content: space-between; align-items: center;
            font-size: 13px; color: #294D61; flex-wrap: wrap; gap: 10px;
        }
        @media(max-width:900px) {
            .footer { padding: 50px 25px 25px; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 35px; }
        }
        @media(max-width:550px) {
            .footer-grid { grid-template-columns: 1fr; }
        }

        /* ================= MODAL ================= */
        .modal {
            position: fixed; top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            display: none; justify-content: center; align-items: center;
            z-index: 999;
        }
        .modal-box {
            background: #EDF6F9; width: 90%; max-width: 420px;
            padding: 40px; border-radius: 25px;
            display: flex; flex-direction: column; gap: 16px;
            position: relative;
        }
        .modal-box h2 { text-align: center; color: #05161A; margin-bottom: 5px; font-size: 24px; }
        .modal-subtitle { text-align: center; color: #3D6A7A; font-size: 14px; margin-top: -10px; }
        .modal-box input, .modal-box select {
            padding: 14px 16px; border: 2px solid #C5DDE5;
            background: #F5FBFC; border-radius: 12px;
            font-size: 15px; outline: none;
            transition: 0.3s; font-family: 'Poppins', sans-serif; color: #05161A;
        }
        .modal-box input:focus, .modal-box select:focus { border-color: #0CABA8; }
        .modal-box button {
            padding: 15px; border: none; background: #0CABA8;
            color: white; border-radius: 12px; font-size: 16px;
            cursor: pointer; font-family: 'Poppins', sans-serif; font-weight: 600;
            transition: 0.3s;
        }
        .modal-box button:hover { background: #0A9996; }
        .close {
            position: absolute; top: 15px; right: 20px;
            font-size: 28px; cursor: pointer; color: #9CA3AF;
        }
        .close:hover { color: #05161A; }
        .modal-switch { text-align: center; font-size: 14px; color: #294D61; }
        .modal-switch a { color: #0CABA8; cursor: pointer; font-weight: 600; text-decoration: none; }
        .modal-error {
            background: #FFF1F1; color: #EF4444; border-radius: 10px;
            padding: 10px 14px; font-size: 14px; display: none;
        }

        /* ================= TOAST ================= */
        .toast {
            position: fixed; bottom: 30px; right: 30px;
            background: #05161A; color: white; padding: 14px 22px;
            border-radius: 14px; font-size: 14px; font-weight: 500;
            transform: translateY(80px); opacity: 0;
            transition: 0.4s; z-index: 9999;
        }
        .toast.show { transform: translateY(0); opacity: 1; }

        /* ================= SEARCH RESULTS ================= */
        .search-results-banner {
            background: #E0F4F4; padding: 15px 80px;
            display: none; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #B2E4E4;
        }
        .search-results-banner span { color: #0CABA8; font-weight: 600; font-size: 15px; }
        .search-results-banner button {
            background: none; border: none; color: #3D6A7A;
            cursor: pointer; font-size: 14px; font-family: 'Poppins', sans-serif;
        }
        /* Conteneur principal qui englobe tout */
.hero-visual-container {
    position: relative;
    width: 100%;
    max-width: 500px;
    height: 450px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
}

/* Le Blob organique et fluide */
.fluid-blob {
    position: absolute;
    width: 320px;
    height: 320px;
    /* Dégradé de couleurs fidèle à votre charte (Bleu aqua, Violet, Bleu nuit) */
    background: linear-gradient(135deg, #0CABA8 0%, #0C7075 50%, #05161A 100%);
    
    /* Forme de base asymétrique */
    border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%;
    
    /* Effet d'ombrage et de lueur douce en arrière-plan */
    box-shadow: 0 20px 40px rgba(12, 112, 117, 0.3);
    filter: blur(2px); /* Adoucit légèrement les bords pour l'effet premium */
    
    /* Déclenchement de l'animation */
    animation: blob-movement 8s ease-in-out infinite alternate;
}

/* Styles communs pour les badges de statistiques */
.badge {
    position: absolute;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px); /* Effet verre dépoli (Glassmorphism) */
    padding: 14px 22px;
    border-radius: 16px;
    font-weight: 600;
    color: #05161A;
    font-size: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.6);
    z-index: 2;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Positionnement précis des 3 badges autour du blob */
.badge-top {
    background:#0A9996;
    top: 38%;
    right: -5%;
}

.badge-middle {
    background:#0A9996;
    top: 38%;
    left: -2%;
}

.badge-bottom {
    background:#0A9996;
    bottom: 15%;
    right: 24%;
}

/* Effet de survol sur les badges (ils flottent légèrement) */
.badge:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

/* ================= FL SEARCH BAR ================= */
.fl-search-wrap { margin-bottom: 28px; }

.fl-search-box {
    display: flex;
    align-items: center;
    gap: 12px;
    background: white;
    border: 2px solid #C5DDE5;
    border-radius: 16px;
    padding: 12px 18px;
    max-width: 540px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    transition: border-color 0.3s, box-shadow 0.3s;
}
.fl-search-box:focus-within {
    border-color: #0CABA8;
    box-shadow: 0 4px 20px rgba(82,214,244,0.18);
}
.fl-search-box .fl-search-icon { color: #3D6A7A; font-size: 16px; }

.fl-search-box input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 15px;
    color: #05161A;
    font-family: 'Poppins', sans-serif;
    background: transparent;
}
.fl-search-box input::placeholder { color: #B0BAC9; }

.fl-search-clear {
    background: none;
    border: none;
    cursor: pointer;
    color: #3D6A7A;
    font-size: 15px;
    display: none;
    align-items: center;
    padding: 2px;
    transition: color 0.2s;
}
.fl-search-clear:hover { color: #EF4444; }

.fl-no-results {
    color: #3D6A7A;
    font-size: 15px;
    grid-column: 1 / -1;
    padding: 20px 0;
}

/* L'ANIMATION CSS : Modifie la forme du border-radius en continu */
@keyframes blob-movement {
    0% {
        border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%;
        transform: rotate(0deg) scale(1);
    }
    50% {
        border-radius: 70% 30% 52% 48% / 60% 40% 60% 40%;
        transform: rotate(90deg) scale(1.05); /* Légère rotation et grossissement */
    }
    100% {
        border-radius: 30% 70% 40% 60% / 40% 60% 30% 70%;
        transform: rotate(180deg) scale(0.98);
    }
}
    </style>
</head>

<body>

<header class="navbar" id="mainNav">
    <div class="logo" onclick="showPage('home')">
        <span class="logo-blue">Etud</span><span class="logo-dark">Serv</span>
    </div>
    <nav class="nav-links" id="navLinks">
        <a onclick="showPage('home'); closeMenu()">Accueil</a>
        <a onclick="showPage('freelancers'); closeMenu()">Freelancers</a>
        <a onclick="showPage('about'); closeMenu()">Qui sommes-nous ?</a>
        <a onclick="showPage('contact'); closeMenu()">Contact</a>
        <a href="{{ route('login') }}">Se connecter</a>
        <a class="join-btn" href="{{ route('register') }}">Rejoindre</a>
    </nav>
    <!-- Hamburger visible uniquement sur mobile -->
    <button class="hamburger" id="hamburger" onclick="toggleMenu()">
        <span></span><span></span><span></span>
    </button>
</header>

    <!-- ================= SEARCH RESULTS BANNER ================= -->
    <div class="search-results-banner" id="searchBanner">
        <span id="searchBannerText"></span>
        <button onclick="clearSearch()">✕ Effacer la recherche</button>
    </div>

    <!-- ========================================================= -->
    <!-- PAGE: HOME                                                 -->
    <!-- ========================================================= -->
    <div id="pageLoader">
        <div class="loader-inner">
            <div class="loader-logo">
                <span class="loader-blue">Etud</span><span class="loader-dark">Serv</span>
            </div>
            <div class="loader-bar-wrap"><div class="loader-bar"></div></div>
            <p class="loader-text">Chargement en cours...</p>
        </div>
    </div>


     
    <div class="page active" id="page-home">

        <!-- ================= VIDEO HERO ================= -->
        <section class="video-hero">
           
           <video  src ="https://acquisition-ui-assets.static-upwork.com/brontes/canopy/hero-video-lg.mp4" autoplay="autoplay" muted="muted" loop="loop"
            playsinline="" class="card-uma-hero-img card-uma-hero-video" data-v-6786a5bc="">
           <source src="hero-video-lg.mp4" type="video/mp4">
            </video>
        
            <div class="video-hero-overlay"></div>
            <div class="video-hero-content">
                <h1>Trouvez le talent parfait<br>pour votre <span>projet</span></h1>
                <p>Connectez-vous avec les meilleurs étudiants et freelances mauritaniens pour réaliser des projets de qualité.</p>
                <div class="video-hero-btns">
                    <button class="vbtn-primary" onclick="showPage('freelancers')">
                        <i class="fa-solid fa-magnifying-glass"></i> Explorer les talents
                    </button>
                </div>
            </div>
            <div class="scroll-down">
                <span>Découvrir</span>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </section>

        <!-- HERO -->
        <section class="hero">
           
            <video class="hero-video-bg" autoplay muted loop playsinline>
                <source src="https://acquisition-ui-assets.static-upwork.com/brontes/canopy/hero-video-lg.mp4" type="video/mp4">
            </video>

            <div class="hero-content">
                <h1>Transformez vos idées en <span>réalité</span> avec EtudServ</h1>
                <p>Connectez-vous avec les meilleurs étudiants et freelances pour réaliser des projets de qualité.</p>
                <div class="hero-features">
                    <div class="feature">✓ Les meilleurs talents</div>
                    <div class="feature">✓ Travail de qualité</div>
                    <div class="feature">✓ Paiement sécurisé</div>
                </div>
                <!-- ✅ FIX: Search bar works -->
                <div class="search-box">
                    <select id="searchCategory">
                        <option value="">Skills</option>
                        <option>Développement Web</option>
                        <option>Design Graphique</option>
                        <option>Marketing Digital</option>
                        <option>Traduction</option>
                        <option>Montage Vidéo</option>
                        <option>Assistance Virtuelle</option>
                    </select>
                    <input type="text" id="searchInput" placeholder="Rechercher un service..." onkeydown="if(event.key==='Enter') doSearch()">
                    <button onclick="doSearch()">🔍 Rechercher</button>
                </div>
            </div>

           <div class="hero-visual-container">
                    <div class="fluid-blob"></div>

                    <div class="badge badge-bottom">
                        <span>🎓 200+ Étudiants vérifiés</span>
                    </div>
    
                    <div class="badge badge-middle">
                        <span>⭐ 4.9/5 Satisfaction</span>
                    </div>

                    <div class="badge badge-top">
                        <span>🚀 Projet lancé en 24h</span>
                    </div>
            </div>
        </section>

        <!-- SERVICES -->

        <section class="services scroll-section">   
            <div class="services-header">
                <h2>Services Populaires</h2>
                <p>Découvrez les services les plus demandés sur notre plateforme</p>
            </div>
            <div class="services-grid">
                <div class="service-card" onclick="filterAndGo('Développement Web')">
                    <div class="service-icon blue">&lt;/&gt;</div>
                    <h3>Développement Web</h3>
                    <p>Sites web et applications sur mesure</p>
                </div>
                <div class="service-card" onclick="filterAndGo('Design Graphique')">
                    <div class="service-icon purple">🎨</div>
                    <h3>Design Graphique</h3>
                    <p>Logos, branding et créations visuelles</p>
                </div>
                <div class="service-card" onclick="filterAndGo('Marketing Digital')">
                    <div class="service-icon green">↗</div>
                    <h3>Marketing Digital</h3>
                    <p>Stratégies et campagnes marketing</p>
                </div>
                <div class="service-card" onclick="filterAndGo('Traduction')">
                    <div class="service-icon pink">文</div>
                    <h3>Traduction</h3>
                    <p>Services de traduction professionnelle</p>
                </div>
                <div class="service-card" onclick="filterAndGo('Montage Vidéo')">
                    <div class="service-icon red">🎥</div>
                    <h3>Montage Vidéo</h3>
                    <p>Édition et production vidéo</p>
                </div>
                <div class="service-card" onclick="filterAndGo('Assistance Virtuelle')">
                    <div class="service-icon cyan">🎧</div>
                    <h3>Assistance Virtuelle</h3>
                    <p>Support administratif et technique</p>
                </div>
            </div>
        </section>

        <!-- WHY ETUDSERV -->
        <section class="section scroll-section">
            <h1>Pourquoi choisir EtudServ ?</h1>
            <p class="description">
                EtudServ propose deux espaces distincts : un espace étudiant gratuit avec vérification académique,
                et un espace freelancer professionnel destiné aux prestataires de services numériques.
            </p>
            <a href="#" class="btn" onclick="showPage('about'); return false;">
                En savoir plus <i class="fa-solid fa-arrow-right"></i>
            </a>
            <div class="cards">
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-graduation-cap"></i></div>
                    <h3>Espace Étudiant Gratuit</h3>
                    <p>Un espace dédié aux étudiants avec vérification académique pour garantir l'authenticité.</p>
                </div>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-briefcase"></i></div>
                    <h3>Espace Freelancer Pro</h3>
                    <p>Pour les professionnels du numérique offrant des services de qualité premium.</p>
                </div>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-shield"></i></div>
                    <h3>Paiement Sécurisé</h3>
                    <p>Transactions protégées et paiements garantis pour votre tranquillité d'esprit.</p>
                </div>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-bolt"></i></div>
                    <h3>Rapidité & Efficacité</h3>
                    <p>Trouvez le talent parfait et démarrez votre projet en quelques minutes.</p>
                </div>
            </div>
        </section>

    </div><!-- end page-home -->

    <!-- ========================================================= -->
    <!-- PAGE: FREELANCERS                                          -->
    <!-- ========================================================= -->
    <div class="page" id="page-freelancers">
        <div class="freelancers-page">
            <h2>Nos Freelancers & Étudiants</h2>
            <p>Trouvez le talent parfait pour votre projet</p>
            <!-- ===== BARRE DE RECHERCHE FREELANCERS ===== -->
            <div class="fl-search-wrap">
    <div class="fl-search-box">
        <select id="flSearchType" style="
            border: none;
            outline: none;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #05161A;
            background: #DCF0F2;
            padding: 6px 10px;
            border-radius: 10px;
            cursor: pointer;
        ">
            <option value="freelancer">👤 Freelancer</option>
            <option value="service">💼 Service</option>
        </select>
        <i class="fa-solid fa-magnifying-glass fl-search-icon"></i>
        <input
            type="text"
            id="flSearchInput"
            placeholder="Rechercher..."
            oninput="searchFreelancers(this.value)"
        >
        <button class="fl-search-clear" id="flSearchClear" onclick="clearFlSearch()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>

            <!-- Filter buttons -->
            <div class="filter-bar">
                <button class="filter-btn active" onclick="filterFreelancers('Tous', this)">Tous</button>
                <button class="filter-btn" onclick="filterFreelancers('Développement Web', this)">Développement Web</button>
                <button class="filter-btn" onclick="filterFreelancers('Design Graphique', this)">Design Graphique</button>
                <button class="filter-btn" onclick="filterFreelancers('Marketing Digital', this)">Marketing Digital</button>
                <button class="filter-btn" onclick="filterFreelancers('Traduction', this)">Traduction</button>
                <button class="filter-btn" onclick="filterFreelancers('Montage Vidéo', this)">Montage Vidéo</button>
                <button class="filter-btn" onclick="filterFreelancers('Assistance Virtuelle', this)">Assistance Virtuelle</button>
            </div>

            <div class="freelancers-grid" id="freelancersGrid"></div>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- PAGE: ABOUT                                                -->
    <!-- ========================================================= -->
    <div class="page" id="page-about">
        <div class="about-page">
            <h2>Qui sommes-nous ?</h2>
            <p>
                EtudServ est une plateforme freelance mauritanienne fondée par des étudiants  de SupNum passionnés de technologie.
                Notre mission est de connecter les talents locaux avec des clients à la recherche de services numériques de qualité.
            </p>
            <p>
                Nous croyons que chaque étudiant mauritanien mérite une opportunité de monétiser ses compétences,
                tout en offrant aux entreprises et particuliers un accès facile à des freelances vérifiés et compétents.
            </p>
            <p>
                Notre plateforme propose deux espaces : un espace <strong>Étudiant</strong> (gratuit, avec vérification académique)
                et un espace <strong>Freelancer Pro</strong> pour les professionnels confirmés.
            </p>

            <h3 style="font-size:28px;color:#05161A;margin-top:50px;margin-bottom:10px;">Notre équipe fondatrice</h3>
            <p style="margin-bottom:0;">Des étudiants mauritaniens déterminés à transformer le marché du travail numérique.</p>

            <div class="team-grid">
                <div class="team-card">
                    <div class="team-avatar" style="background:linear-gradient(135deg,#0CABA8,#294D61)">A</div>
                    <h4>Abdou</h4>
                    <span>Co-fondateur & Dev</span>
                </div>
                <div class="team-card">
                    <div class="team-avatar" style="background:linear-gradient(135deg,#45C486,#0CABA8)">B</div>
                    <h4>Boubecrine</h4>
                    <span>Co-fondateur & Design</span>
                </div>
                   <div class="team-card">
                    <div class="team-avatar" style="background:linear-gradient(135deg,#633c4a,#294D61)">T</div>
                    <h4>Taleb</h4>
                    <span>Co-fondateur & Dev</span>
                </div>
                <div class="team-card">
                    <div class="team-avatar" style="background:linear-gradient(135deg,#130509,#294D61)">Y</div>
                    <h4>Youssef</h4>
                    <span>Co-fondateur & Dev</span>
                </div>
                   <div class="team-card">
                    <div class="team-avatar" style="background:linear-gradient(135deg,#ed1d66,#294D61)">M</div>
                    <h4>Marieme</h4>
                    <span>Backend & Sécurité</span>
                </div>
                   <div class="team-card">
                    <div class="team-avatar" style="background:linear-gradient(135deg,#12479d,#294D61)">A</div>
                    <h4>Ahmed </h4>
                    <span>Backend & Sécurité</span>
                </div>
                <div class="team-card">
                    <div class="team-avatar" style="background:linear-gradient(135deg,#F59E0B,#FF6B6B)">MA</div>
                    <h4>Med Abdellahi</h4>
                    <span>Backend & Sécurité</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- PAGE: CONTACT                                              -->
    <!-- ========================================================= -->
    <div class="page" id="page-contact">
        <div class="contact-page">
            <h2>Contactez-nous</h2>
            <p>Une question ? Une suggestion ? On vous répond rapidement.</p>
            <div class="contact-form">
                <input type="text" id="contactName" placeholder="Votre nom complet">
                <input type="email" id="contactEmail" placeholder="Votre email">
                <select id="contactSubject">
                    <option value="">Sujet</option>
                    <option>Problème technique</option>
                    <option>Signaler un compte</option>
                    <option>Proposition de partenariat</option>
                    <option>Autre</option>
                </select>
                <textarea id="contactMsg" placeholder="Votre message..."></textarea>
                <button onclick="sendContact()">Envoyer le message <i class="fa-solid fa-paper-plane"></i></button>
            </div>
            <div class="contact-success" id="contactSuccess">
                <i class="fa-solid fa-circle-check" style="font-size:40px;display:block;margin-bottom:12px;"></i>
                Message envoyé avec succès ! Nous vous répondrons sous 24h.
            </div>
        </div>
    </div>

    <!-- ================= FOOTER (shown on all pages) ================= -->
    <footer class="footer">
        <div class="footer-grid">
            <div>
                <div class="footer-logo"><span>Etud</span>Serv</div>
                <p class="footer-desc">La plateforme freelance mauritanienne qui connecte étudiants et entreprises pour des projets numériques de qualité.</p>
                <div class="footer-socials">
                    <a class="social-btn" href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a class="social-btn" href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a class="social-btn" href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a class="social-btn" href="#"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Plateforme</h4>
                <a onclick="showPage('home')">Accueil</a>
                <a onclick="showPage('freelancers')">Freelancers</a>
                <a onclick="openRegister()">Devenir freelancer</a>
            </div>
            <div class="footer-col">
                <h4>À propos</h4>
                <a onclick="showPage('about')">Notre équipe</a>
                <a onclick="showPage('contact')">Contact</a>
                <a href="#">Blog</a>
            </div>
            <div class="footer-col">
                <h4>Légal</h4>
                <a href="#">Conditions d'utilisation</a>
                <a href="#">Politique de confidentialité</a>
                <a href="#">Cookies</a>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© 2026 EtudServ. Tous droits réservés.</span>
            <span>Made IN Mauritanie</span>
        </div>
    </footer>

    <!-- ================= LOGIN MODAL ================= -->
    <div class="modal" id="loginModal">
        <div class="modal-box">
            <span class="close" onclick="closeLogin()">×</span>
            <h2>Se connecter</h2>
            <p class="modal-subtitle">Content de vous revoir !</p>
            <div class="modal-error" id="loginError">Email ou mot de passe incorrect.</div>
            <input type="email" id="loginEmail" placeholder="Email">
            <input type="password" id="loginPass" placeholder="Mot de passe">
            <button onclick="doLogin()">Connexion</button>
            <p class="modal-switch">Pas encore de compte ? <a onclick="closeLogin(); openRegister()">S'inscrire</a></p>
        </div>
    </div>

    <!-- ================= REGISTER MODAL ================= -->
    <div class="modal" id="registerModal">
        <div class="modal-box">
            <span class="close" onclick="closeRegister()">×</span>
            <h2>Rejoindre EtudServ</h2>
            <p class="modal-subtitle">Créez votre compte gratuit</p>
            <div class="modal-error" id="registerError">Veuillez remplir tous les champs.</div>
            <input type="text" id="regName" placeholder="Nom complet">
            <input type="email" id="regEmail" placeholder="Email">
            <input type="password" id="regPass" placeholder="Mot de passe">
            <!-- ✅ FIX: Added "Étudiant" option -->
           <select id="regType" onchange="toggleStudentVerif(this.value)">
           <option value="">Je suis...</option>
           <option value="etudiant">Étudiant</option>
           <option value="freelancer">Freelancer Pro</option>
           <option value="client">Client</option>
            </select>
            <!-- Vérification étudiant — caché par défaut -->
<div id="studentVerif" style="display:none;">
    <div id="uploadBox" onclick="document.getElementById('studentFile').click()"
        style="
            border: 2px dashed #0CABA8;
            border-radius: 14px;
            padding: 22px 16px;
            text-align: center;
            cursor: pointer;
            background: #E0F4F4;
            transition: 0.3s;
        "
        onmouseenter="this.style.background='#d6f5ff'"
        onmouseleave="this.style.background='#E0F4F4'"
    >
        <i class="fa-solid fa-cloud-arrow-up" style="font-size:32px;color:#0CABA8;margin-bottom:10px;display:block;"></i>
        <p style="font-size:14px;font-weight:600;color:#05161A;margin-bottom:5px;">
            Justificatif étudiant
        </p>
        <p style="font-size:12px;color:#3D6A7A;line-height:1.5;">
            Carte étudiante, certificat de scolarité<br>ou relevé d'inscription
        </p>
        <p style="font-size:11px;color:#B0BEC5;margin-top:8px;">PDF, JPG, PNG </p>
        <input type="file" id="studentFile" accept=".pdf,.jpg,.jpeg,.png"
            style="display:none" onchange="showFileName(this)">
    </div>

    <!-- Aperçu du fichier choisi -->
    <div id="filePreview" style="
        display:none;
        margin-top:10px;
        background:white;
        border:1px solid #C5DDE5;
        border-radius:10px;
        padding:10px 14px;
        align-items:center;
        gap:10px;
        font-size:13px;
        color:#05161A;
    ">
        <i class="fa-solid fa-file-circle-check" style="color:#45C486;font-size:18px;"></i>
        <span id="fileNameText" style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></span>
        <i class="fa-solid fa-xmark" style="color:#3D6A7A;cursor:pointer;" onclick="removeFile()"></i>
    </div>
</div> 
            <button onclick="doRegister()">Créer mon compte</button>
            <p class="modal-switch">Déjà un compte ? <a onclick="closeRegister(); openLogin()">Se connecter</a></p>
        </div>
    </div>

    <!-- ================= TOAST ================= -->
    <div class="toast" id="toast"></div>

    <!-- ================= JAVASCRIPT ================= -->
    <script>

        /* ---- FREELANCER DATA ---- */
        const freelancers = @json($freelancersData);

        let currentFilter = 'Tous';

        function renderFreelancers(filter) {
            const grid = document.getElementById('freelancersGrid');
            const list = filter === 'Tous' ? freelancers : freelancers.filter(f => f.category === filter);
            grid.innerHTML = list.map(f => `
                <div class="freelancer-card">
                    <div class="fl-header">
                        <div class="fl-avatar" style="background:${f.color}">${f.name[0]}</div>
                        <div class="fl-info">
                            <h4>${f.name}</h4>
                            <span>${f.role}</span>
                        </div>
                    </div>
                    <span class="fl-badge ${f.type === 'etudiant' ? 'badge-student' : 'badge-pro'}">
                        ${f.type === 'etudiant' ? '🎓 Étudiant vérifié' : '⚡ Freelancer Pro'}
                    </span>
                    <div class="fl-tags">${f.tags.map(t => `<span class="fl-tag">${t}</span>`).join('')}</div>
                    <p class="fl-desc">${f.desc}</p>
                    <div class="fl-footer">
                        <span class="fl-rating">${f.rating}</span>
                        <span class="fl-price">${f.price}</span>
                    </div>
                    ${f.membre_depuis ? `<div style="color:#B0B8C9;font-size:11px;margin-bottom:10px;"><i class="fa-regular fa-calendar"></i> Membre depuis ${f.membre_depuis}</div>` : ''}
                    ${f.service_id
                        ? `<button class="contact-btn" onclick="contacterPrestataire(${f.service_id})"><i class="fa-solid fa-envelope"></i> Contacter</button>`
                        : `<button class="contact-btn" disabled style="opacity:0.5;cursor:not-allowed;"><i class="fa-solid fa-envelope"></i> Indisponible</button>`
                    }
                </div>
            `).join('');

            if (list.length === 0) {
                grid.innerHTML = `<p style="color:#3D6A7A;font-size:16px;grid-column:1/-1;">Aucun freelancer trouvé pour cette catégorie.</p>`;
            }
        }

        function filterFreelancers(cat, btn) {
            currentFilter = cat;
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');
            renderFreelancers(cat);
        }

        function contactFreelancer(name) {
            window.location.href = '/contact?to=' + encodeURIComponent(name);
        }
        const estConnecte = @json(session('utilisateur_id') ? true : false);

        function contacterPrestataire(serviceId) {
            if (!estConnecte) {
                window.location.href = '/login';
                return;
            }
            window.location.href = '/messagerie/demarrer/' + serviceId;
        }

        function filterAndGo(category) {
            showPage('freelancers');
            const btn = [...document.querySelectorAll('.filter-btn')].find(b => b.textContent.trim() === category);
            filterFreelancers(category, btn);
        }

        /* ---- PAGE NAVIGATION ---- */
        function showPage(id) {
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            document.getElementById('page-' + id).classList.add('active');
            if (id === 'freelancers') renderFreelancers(currentFilter);
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(initScrollObserver, 100);
        }
        

        /* ---- SEARCH ---- */
        function doSearch() {
            const query = document.getElementById('searchInput').value.trim();
            const category = document.getElementById('searchCategory').value;
            if (!query && !category) { showToast('Entrez un mot-clé ou choisissez une catégorie.'); return; }
            const text = [category, query].filter(Boolean).join(' — ');
            document.getElementById('searchBannerText').textContent = `Résultats pour : "${text}"`;
            document.getElementById('searchBanner').style.display = 'flex';
            filterAndGo(category || 'Tous');
        }

        function clearSearch() {
            document.getElementById('searchBanner').style.display = 'none';
            document.getElementById('searchInput').value = '';
            document.getElementById('searchCategory').value = '';
        }

        /* ---- MODALS ---- */
        function openLogin() { document.getElementById('loginModal').style.display = 'flex'; }
        function closeLogin() { document.getElementById('loginModal').style.display = 'none'; document.getElementById('loginError').style.display = 'none'; }
        function openRegister() { document.getElementById('registerModal').style.display = 'flex'; }
        function closeRegister() { document.getElementById('registerModal').style.display = 'none'; document.getElementById('registerError').style.display = 'none'; }

        // Close modal on outside click
        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) {
                closeLogin(); closeRegister();
            }
        };

        /* ---- FORM VALIDATION ---- */
        function doLogin() {
            const email = document.getElementById('loginEmail').value.trim();
            const pass = document.getElementById('loginPass').value.trim();
            const err = document.getElementById('loginError');
            if (!email || !pass) { err.style.display = 'block'; err.textContent = 'Veuillez remplir tous les champs.'; return; }
            if (!email.includes('@')) { err.style.display = 'block'; err.textContent = 'Email invalide.'; return; }
            // Simulate login success
            closeLogin();
            showToast('✅ Connexion réussie ! Bienvenue sur EtudServ.');
        }
// ── LOADER ──
window.addEventListener('load', () => {
    setTimeout(() => {
        document.getElementById('pageLoader').classList.add('hidden');
    }, 1700);
});

// ── HAMBURGER ──
function toggleMenu() {
    document.getElementById('navLinks').classList.toggle('open');
    document.getElementById('hamburger').classList.toggle('open');
}
function closeMenu() {
    document.getElementById('navLinks').classList.remove('open');
    document.getElementById('hamburger').classList.remove('open');
}
document.addEventListener('click', (e) => {
    const nav = document.getElementById('navLinks');
    const btn = document.getElementById('hamburger');
    if (!nav.contains(e.target) && !btn.contains(e.target)) {
        nav.classList.remove('open');
        btn.classList.remove('open');
    }
});

// ── SCROLL ANIMATIONS ──
const scrollObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('visible');
    });
}, { threshold: 0.12 });

function initScrollObserver() {
    document.querySelectorAll('.scroll-section').forEach(el => scrollObserver.observe(el));
}
initScrollObserver();



        function doRegister() {
            const name = document.getElementById('regName').value.trim();
            const email = document.getElementById('regEmail').value.trim();
            const pass = document.getElementById('regPass').value.trim();
            const type = document.getElementById('regType').value;
            const err = document.getElementById('registerError');
            if (!name || !email || !pass || !type) { err.style.display = 'block'; err.textContent = 'Veuillez remplir tous les champs.'; return; }
            if (!email.includes('@')) { err.style.display = 'block'; err.textContent = 'Email invalide.'; return; }
            if (pass.length < 6) { err.style.display = 'block'; err.textContent = 'Le mot de passe doit faire au moins 6 caractères.'; return; }
            closeRegister();
            showToast(`🎉 Compte créé avec succès ! Bienvenue ${name} !`);
            if (type === 'etudiant') {
    const file = document.getElementById('studentFile');
    if (!file || !file.files || !file.files[0]) {
        err.style.display = 'block';
        err.textContent = '📎 Veuillez joindre votre justificatif étudiant.';
        document.getElementById('uploadBox').style.borderColor = '#EF4444';
        document.getElementById('uploadBox').style.background = '#FFF1F1';
        return;
    }
}
        }

        /* ---- CONTACT FORM ---- */
        function sendContact() {
            const name = document.getElementById('contactName').value.trim();
            const email = document.getElementById('contactEmail').value.trim();
            const msg = document.getElementById('contactMsg').value.trim();
            if (!name || !email || !msg) { showToast('⚠️ Veuillez remplir tous les champs obligatoires.'); return; }
            document.querySelector('.contact-form').style.display = 'none';
            document.getElementById('contactSuccess').style.display = 'block';
        }

        /* ---- TOAST ---- */
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3500);
        }

        /* ---- INIT ---- */
        renderFreelancers('Tous');

        function toggleStudentVerif(val) {
    const box = document.getElementById('studentVerif');
    box.style.display = val === 'etudiant' ? 'block' : 'none';
    if (val !== 'etudiant') removeFile();
}

function showFileName(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (file.size > 5 * 1024 * 1024) {
        alert('⚠️ Fichier trop lourd. Maximum 5 Mo.');
        input.value = '';
        return;
    }
    document.getElementById('fileNameText').textContent = file.name;
    document.getElementById('filePreview').style.display = 'flex';
    document.getElementById('uploadBox').style.borderColor = '#45C486';
    document.getElementById('uploadBox').style.background = '#E0F4F4';
}

function removeFile() {
    document.getElementById('studentFile').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('uploadBox').style.borderColor = '#0CABA8';
    document.getElementById('uploadBox').style.background = '#E0F4F4';
}

    /* ---- RECHERCHE FREELANCERS — connectée à la BDD ---- */
    const FL_COLORS = ['#0CABA8','#294D61','#45C486','#F59E0B','#FF7AA8','#FF6B6B','#0A9996','#3A6B7A'];

    function searchFreelancers(q) {
    // 1. On regarde si l'utilisateur a choisi "Freelancer" ou "Service" dans la liste déroulante
    const type = document.getElementById('flSearchType').value;
    const grid = document.getElementById('freelancersGrid');
    if (!grid) return;

    // Si la barre de recherche est vide, on réaffiche tous les freelancers par défaut
    if (!q || q.trim() === '') {
        renderFreelancers('Tous'); 
        return;
    }

    // 2. On transforme ce que l'utilisateur écrit en minuscules (pour ignorer les Majuscules/Minuscules)
    const searchText = q.toLowerCase().trim();

    // 3. On filtre instantanément notre tableau de freelancers
    const filtered = freelancers.filter(f => {
        if (type === 'freelancer') {
            // Recherche par Nom, Prénom ou Rôle dès la première lettre
            return f.name.toLowerCase().includes(searchText) || 
                   f.role.toLowerCase().includes(searchText);
        } else {
            // Recherche par Services ou Compétences (Skills / Tags) dès la première lettre
            return f.desc.toLowerCase().includes(searchText) || 
                   f.tags.some(tag => tag.toLowerCase().includes(searchText));
        }
    });

    // 4. On reconstruit la grille des freelancers avec uniquement les résultats trouvés
    grid.innerHTML = filtered.map(f => `
        <div class="freelancer-card">
            <div class="fl-header">
                <div class="fl-avatar" style="background:${f.color}">${f.name[0].toUpperCase()}</div>
                <div class="fl-info">
                    <h4>${f.name}</h4>
                    <span>${f.role}</span>
                </div>
            </div>
            <span class="fl-badge ${f.type === 'etudiant' ? 'badge-student' : 'badge-pro'}">
                ${f.type === 'etudiant' ? '🎓 Étudiant vérifié' : '⚡ Freelancer Pro'}
            </span>
            <div class="fl-tags">${f.tags.map(t => `<span class="fl-tag">${t}</span>`).join('')}</div>
            <p class="fl-desc">${f.desc}</p>
            <div class="fl-footer">
                <span class="fl-rating">${f.rating}</span>
                <span class="fl-price">${f.price}</span>
            </div>
            <button class="contact-btn" onclick="contactFreelancer('${f.name.replace(/'/g, "\\'")}')">
                <i class="fa-solid fa-envelope"></i> Contacter
            </button>
        </div>
    `).join('');

    // Si aucune correspondance n'est trouvée (lettre introuvable)
    if (filtered.length === 0) {
        grid.innerHTML = `<p style="grid-column:1/-1; text-align:center; color:#3D6A7A; margin-top:20px; font-family:'Poppins',sans-serif;">Aucun résultat correspondant à "${q}"</p>`;
    }
}

// Fonction pour vider la recherche quand on clique sur le bouton (X)
function clearFlSearch() {
    document.getElementById('flSearchInput').value = '';
    renderFreelancers('Tous'); // Réaffiche tout le monde
}

    function renderSearchResults(data, q, type) {
        const grid = document.getElementById('freelancersGrid');

        if (data.total === 0) {
            grid.innerHTML = `
                <p class="fl-no-results">
                    <i class="fa-solid fa-face-frown" style="margin-right:8px;"></i>
                    Aucun ${type === 'freelancer' ? 'freelancer' : 'service'} trouvé pour "<strong>${q}</strong>".
                </p>`;
            return;
        }

        // ── Freelancers ──
        if (type === 'freelancer') {
            grid.innerHTML = data.results.map((f, i) => `
                <div class="freelancer-card">
                    <div class="fl-header">
                        <div class="fl-avatar" style="background:${FL_COLORS[i % FL_COLORS.length]}">
                            ${f.nom.charAt(0).toUpperCase()}
                        </div>
                        <div class="fl-info">
                            <h4>${f.nom}</h4>
                            <span>${f.detail}</span>
                        </div>
                    </div>
                    <span class="fl-badge badge-pro">⚡ Freelancer</span>
                    <p class="fl-desc">${f.email}</p>
                    <button class="contact-btn" onclick="contactFreelancer('${f.nom.replace(/'/g,"\\'")}')">
                        <i class="fa-solid fa-envelope"></i> Contacter
                    </button>
                </div>
            `).join('');
        }

        // ── Skills ──
if (type === 'skill') {
    grid.innerHTML = data.results.map((s, i) => `
        <div class="freelancer-card">
            <div class="fl-header">
                <div class="fl-avatar" style="background:${FL_COLORS[i % FL_COLORS.length]}">
                    ${s.nom.charAt(0).toUpperCase()}
                </div>
                <div class="fl-info">
                    <h4>${s.nom}</h4>
                    <span>${s.detail}</span>
                </div>
            </div>
            <span class="fl-badge badge-student">🏷️ Skill</span>
            <button class="contact-btn" onclick="filterAndGo('${s.nom.replace(/'/g,"\\'")}')">
                <i class="fa-solid fa-arrow-right"></i> Voir les services
            </button>
        </div>
    `).join('');
}
    }

    function clearFlSearch() {
        document.getElementById('flSearchInput').value = '';
        searchFreelancers('');
    }

    // Relancer la recherche si on change le type
    document.getElementById('flSearchType').addEventListener('change', () => {
        searchFreelancers(document.getElementById('flSearchInput').value);
    });

    </script>
</body>
</html>
