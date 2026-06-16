<!DOCTYPE html>
<html lang="{{ session('lang', 'fr') }}" dir="{{ session('lang', 'fr') === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devenir Prestataire – EtudServ</title>
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
           PAGE HEADER
        ========================================================= */
        .page-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 50%, #3a3a5c 100%);
            padding: 40px 60px 36px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .page-header-inner {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .page-eyebrow {
            font-size: 12px;
            font-weight: 600;
            color: rgba(82,214,244,0.85);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .page-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .page-header h1 span { color: #52D6F4; }
        .page-header .page-sub {
            font-size: 14px;
            color: rgba(255,255,255,0.55);
            line-height: 1.6;
        }
        .page-badges {
            display: flex;
            gap: 10px;
            margin-top: 16px;
            flex-wrap: wrap;
        }
        .p-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .p-badge-blue   { background: rgba(82,214,244,0.12); color: #52D6F4; border: 1px solid rgba(82,214,244,0.25); }
        .p-badge-purple { background: rgba(123,97,255,0.15); color: #B49EFF; border: 1px solid rgba(123,97,255,0.25); }
        .p-badge-green  { background: rgba(69,196,134,0.15); color: #6EFFC0; border: 1px solid rgba(69,196,134,0.25); }

        @media(max-width:768px) { .page-header { padding: 28px 20px 24px; } }

        /* =========================================================
           FORM LAYOUT
        ========================================================= */
        .form-wrap {
            max-width: 800px;
            margin: 36px auto;
            padding: 0 20px 60px;
        }

        /* Error banner */
        .error-banner {
            background: #FEE2E2;
            color: #991B1B;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 13px;
            border: 1px solid #FECACA;
        }

        /* Section cards */
        .form-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 28px 32px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(27,37,89,0.04);
        }

        .form-card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 14px;
            border-bottom: 2px solid var(--border);
        }
        .form-card-title::before {
            content: '';
            display: inline-block;
            width: 4px; height: 18px;
            background: linear-gradient(180deg, var(--blue), var(--purple));
            border-radius: 4px;
        }
        .form-card-title i {
            font-size: 15px;
            color: var(--blue2);
        }

        /* Grid rows */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-row.single { grid-template-columns: 1fr; }
        @media(max-width:600px) { .form-row { grid-template-columns: 1fr; } }

        /* Form groups */
        .form-group { margin-bottom: 20px; }
        .form-group:last-child { margin-bottom: 0; }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            color: var(--dark);
            background-color: #F8FAFC;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            box-sizing: border-box;
            appearance: none;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: var(--blue2);
            background-color: var(--white);
            box-shadow: 0 0 0 4px rgba(57,198,233,0.12);
            outline: none;
        }
        .form-textarea {
            resize: vertical;
            min-height: 110px;
            line-height: 1.6;
        }

        /* Select wrapper with arrow */
        .select-wrap {
            position: relative;
        }
        .select-wrap::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            pointer-events: none;
            font-size: 13px;
        }

        /* Niveau pills (dynamic buttons) */
        .niveau-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .niveau-pill input[type="radio"] { display: none; }
        .niveau-pill label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: 1.5px solid var(--border);
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            color: var(--gray);
            background: #F8FAFC;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
        }
        .niveau-pill label:hover {
            border-color: var(--blue2);
            color: var(--blue2);
            background: #EAFBFF;
        }
        .niveau-pill input[type="radio"]:checked + label {
            border-color: var(--blue2);
            color: var(--blue2);
            background: #EAFBFF;
            box-shadow: 0 0 0 3px rgba(57,198,233,0.15);
        }
        .niveau-pill.purple input[type="radio"]:checked + label {
            border-color: var(--purple);
            color: var(--purple);
            background: #F2EEFF;
            box-shadow: 0 0 0 3px rgba(123,97,255,0.12);
        }
        .niveau-pill.green input[type="radio"]:checked + label {
            border-color: var(--green);
            color: var(--green);
            background: #EAFBF3;
            box-shadow: 0 0 0 3px rgba(69,196,134,0.12);
        }
        .niveau-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
        }
        .dot-blue   { background: var(--blue2); }
        .dot-purple { background: var(--purple); }
        .dot-green  { background: var(--green); }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 16px;
            border: none;
            background: linear-gradient(90deg, var(--blue2), var(--purple));
            color: white;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            letter-spacing: 0.5px;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 8px 24px rgba(57,198,233,0.25);
            margin-top: 8px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(57,198,233,0.35);
            opacity: 0.93;
        }
        .btn-submit:active { transform: translateY(0); }

        /* =========================================================
           FOOTER
        ========================================================= */
        .footer {
            background: var(--white);
            border-top: 1px solid var(--border);
            padding: 20px 60px;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .footer-logo { font-size: 18px; font-weight: 700; }
        .footer-logo span { color: var(--blue2); }
        .footer-copy, .footer-made { font-size: 12px; color: var(--gray); }

        /* =========================================================
           RTL SUPPORT
        ========================================================= */
        [dir="rtl"] .select-wrap::after { right: auto; left: 14px; }
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
                <a href="{{ route('profil.show', session('utilisateur_id')) }}">
                    <i class="fa-regular fa-user"></i>
                    {{ session('lang','fr') === 'ar' ? 'ملفي الشخصي' : 'Mon profil' }}
                </a>
                <a href="#">
                    <i class="fa-regular fa-bookmark"></i>
                    {{ session('lang','fr') === 'ar' ? 'المفضلة' : 'Favoris' }}
                </a>
                <a href="#">
                    <i class="fa-solid fa-gear"></i>
                    {{ session('lang','fr') === 'ar' ? 'الإعدادات' : 'Paramètres' }}
                </a>
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

{{-- ===================== PAGE HEADER ===================== --}}
<section class="page-header">
    <div class="page-header-inner">
        <p class="page-eyebrow">
            <i class="fa-solid fa-bolt"></i>
            {{ session('lang','fr') === 'ar' ? 'انضم كمستقل' : 'Devenir Prestataire' }}
        </p>
        <h1>Devenir <span>Prestataire</span></h1>
        <p class="page-sub">Configurez vos options pour commencer à recevoir des missions</p>
        <div class="page-badges">
            <span class="p-badge p-badge-blue"><i class="fa-solid fa-shield-halved"></i> Profil vérifié</span>
            <span class="p-badge p-badge-purple"><i class="fa-solid fa-star"></i> Missions illimitées</span>
            <span class="p-badge p-badge-green"><i class="fa-solid fa-bolt"></i> Paiement sécurisé</span>
        </div>
    </div>
</section>

{{-- ===================== FORM ===================== --}}
<div class="form-wrap">

    @if ($errors->any())
        <div class="error-banner">
            @foreach ($errors->all() as $error)
                <div>⚠️ {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('devenir.prestataire') }}" method="POST">
        @csrf

        {{-- ── Section 1 : Votre profil ── --}}
        <div class="form-card">
            <div class="form-card-title">
                <i class="fa-solid fa-user-tie"></i>
                Votre profil
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Type de Prestataire</label>
                    <div class="select-wrap">
                        <select name="type_prestataire" class="form-select" required>
                            <option value="" selected disabled>Choisissez un type</option>
                            <option value="Développement Web">Développement Web</option>
                            <option value="Design Graphique">Design Graphique</option>
                            <option value="Marketing Digital">Marketing Digital</option>
                            <option value="Traduction">Traduction</option>
                            <option value="Rédaction">Rédaction</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Niveau du freelance</label>
                    <div class="niveau-group">
                        <div class="niveau-pill">
                            <input type="radio" name="niveau" id="niv-junior" value="basic" checked>
                            <label for="niv-junior">
                                <span class="niveau-dot dot-blue"></span> Junior
                            </label>
                        </div>
                        <div class="niveau-pill purple">
                            <input type="radio" name="niveau" id="niv-pro" value="standard">
                            <label for="niv-pro">
                                <span class="niveau-dot dot-purple"></span> Pro
                            </label>
                        </div>
                        <div class="niveau-pill green">
                            <input type="radio" name="niveau" id="niv-premium" value="premium">
                            <label for="niv-premium">
                                <span class="niveau-dot dot-green"></span> Premium
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Votre Biographie</label>
                <textarea name="bio" class="form-textarea"
                    placeholder="Décrivez vos compétences techniques et ce que vous proposez aux étudiants..."
                    required></textarea>
            </div>
        </div>

        {{-- ── Section 2 : Votre premier service ── --}}
        <div class="form-card">
            <div class="form-card-title">
                <i class="fa-solid fa-briefcase"></i>
                Votre premier service
            </div>

            <div class="form-group">
                <label class="form-label">Titre du service</label>
                <input type="text" name="titre" class="form-input"
                    placeholder="Ex: Développement de site web" required>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea"
                    placeholder="Décrivez votre service en détail..." required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Prix de base (MRU)</label>
                    <input type="number" name="prix_base" class="form-input"
                        min="0" placeholder="Ex: 5000" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Compétence principale</label>
                    <div class="select-wrap">
                        <select name="skills_idskills" class="form-select" required>
                            <option value="">Choisissez une compétence</option>
                            <option value="1">Développement Web</option>
                            <option value="2">Design Graphique</option>
                            <option value="3">Marketing Digital</option>
                            <option value="4">Traduction</option>
                            <option value="5">Montage Vidéo</option>
                            <option value="6">Assistance Virtuelle</option>
                            <option value="7">React</option>
                            <option value="8">Node.js</option>
                            <option value="9">MongoDB</option>
                            <option value="10">Figma</option>
                            <option value="11">Adobe XD</option>
                            <option value="12">Illustrator</option>
                            <option value="13">Français</option>
                            <option value="14">Arabe</option>
                            <option value="15">Anglais</option>
                            <option value="16">SEO</option>
                            <option value="17">Google Ads</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Submit ── --}}
        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-rocket"></i>
            Créer mon profil prestataire
        </button>

    </form>
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
</script>

</body>
</html>
