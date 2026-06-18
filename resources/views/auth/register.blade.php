<!DOCTYPE html>
<html lang="fr">
 
<head>
    <meta charset="utf-8">
    <title>EtudServ – Inscription</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
 
        * { margin: 0; padding: 0; box-sizing: border-box; }
 
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #EDF6F9 0%, #d4eef5 40%, #b8e0ec 100%);
            color: #05161A;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
 
        /* ================= NAVBAR ================= */
        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 80px;
            background: linear-gradient(90deg, #05161A 0%, #072E33 100%);
            box-shadow: 0 4px 24px rgba(5,22,26,0.25);
        }
 
        .logo { font-size: 30px; font-weight: 700; letter-spacing: -0.5px; }
        .logo-blue { color: #6DA5C0; }
        .logo-dark { color: #EDF6F9; font-weight: 400; }
 
        /* ================= WRAPPER ================= */
        .register-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px 20px;
            position: relative;
        }
 
        /* Cercles décoratifs en arrière-plan */
        .register-wrapper::before {
            content: '';
            position: fixed;
            top: -120px;
            right: -120px;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(12,171,168,0.18) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
 
        .register-wrapper::after {
            content: '';
            position: fixed;
            bottom: -100px;
            left: -100px;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(109,165,192,0.20) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
 
        /* ================= CARD ================= */
        .register-box {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1.5px solid rgba(109,165,192,0.25);
            border-radius: 28px;
            padding: 52px 48px;
            width: 100%;
            max-width: 530px;
            box-shadow:
                0 8px 32px rgba(7,46,51,0.10),
                0 2px 8px rgba(12,171,168,0.08);
            position: relative;
            z-index: 1;
        }
 
        /* Accent teal en haut de la card */
        .register-box::before {
            content: '';
            position: absolute;
            top: 0; left: 40px; right: 40px;
            height: 3px;
            background: linear-gradient(90deg, #0CABA8, #6DA5C0);
            border-radius: 0 0 4px 4px;
        }
 
        .register-box h2 {
            font-size: 27px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #05161A;
            letter-spacing: -0.3px;
        }
 
        .register-box p.subtitle {
            font-size: 14.5px;
            color: #294D61;
            margin-bottom: 32px;
            font-weight: 400;
        }
 
        /* ================= FORM ================= */
        .form-row {
            display: flex;
            gap: 14px;
        }
 
        .form-group {
            margin-bottom: 16px;
            flex: 1;
        }
 
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #072E33;
            margin-bottom: 7px;
            letter-spacing: 0.2px;
            text-transform: uppercase;
        }
 
        .password-container {
            position: relative;
            width: 100%;
        }
 
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 13px 18px;
            border: 1.5px solid rgba(109,165,192,0.45);
            border-radius: 12px;
            font-size: 14.5px;
            font-family: 'Poppins', sans-serif;
            color: #05161A;
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
            background: rgba(237,246,249,0.70);
        }
 
        .password-container input {
            padding-right: 50px;
        }
 
        .form-group input:focus,
        .form-group select:focus {
            border-color: #0CABA8;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(12,171,168,0.12);
        }
 
        .form-group input::placeholder {
            color: #6DA5C0;
            font-size: 13.5px;
        }
 
        /* Toggle password */
        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6DA5C0;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }
 
        .toggle-password:hover { color: #0CABA8; }
        .toggle-password svg { width: 20px; height: 20px; }
        .toggle-password .eye-off { display: none; }
 
        /* Select custom arrow */
        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%230CABA8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 15px;
            padding-right: 44px;
            cursor: pointer;
        }
 
        .form-group select option { background: #ffffff; color: #05161A; }
 
        /* Divider sections */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 4px 0 16px;
        }
 
        .section-divider span {
            font-size: 11px;
            font-weight: 600;
            color: #6DA5C0;
            letter-spacing: 1px;
            text-transform: uppercase;
            white-space: nowrap;
        }
 
        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(109,165,192,0.25);
        }
 
        /* Errors */
        .error-msg {
            color: #EF4444;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
 
        .alert-success {
            background: linear-gradient(135deg, #ECFDF5, #d1fae5);
            color: #065F46;
            border: 1px solid #A7F3D0;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13.5px;
            margin-bottom: 20px;
        }
 
        /* ================= BUTTON ================= */
        .btn-register {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #0CABA8 0%, #0A9996 100%);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(12,171,168,0.35);
            margin-top: 10px;
            letter-spacing: 0.3px;
        }
 
        .btn-register:hover {
            background: linear-gradient(135deg, #0A9996 0%, #072E33 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(12,171,168,0.40);
        }
 
        .btn-register:active {
            transform: translateY(0);
        }
 
        /* ================= FOOTER ================= */
        .register-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 13.5px;
            color: #294D61;
        }
 
        .register-footer a {
            color: #0CABA8;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
 
        .register-footer a:hover {
            color: #072E33;
            text-decoration: underline;
        }
 
        /* ================= RESPONSIVE ================= */
        @media (max-width: 600px) {
            .navbar { padding: 18px 24px; }
            .register-box { padding: 38px 24px; }
            .form-row { flex-direction: column; gap: 0; }
        }
    </style>
</head>
 
<body>
 
    {{-- ================= NAVBAR ================= --}}
    <header class="navbar">
        <div class="logo">
            <span class="logo-blue">Etud</span><span class="logo-dark">Serv</span>
        </div>
    </header>
 
    {{-- ================= REGISTER FORM ================= --}}
    <div class="register-wrapper">
        <div class="register-box">
 
            <h2>Créer un compte</h2>
            <p class="subtitle">Rejoignez la communauté EtudServ</p>
 
            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
 
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
 
                {{-- Nom + Prénom --}}
                <div class="section-divider"><span>Identité</span></div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom"
                            placeholder="Votre nom"
                            value="{{ old('nom') }}" required>
                        @error('nom')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>
 
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom"
                            placeholder="Votre prénom"
                            value="{{ old('prenom') }}" required>
                        @error('prenom')
                            <span class="error-msg">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
 
                {{-- Email --}}
                <div class="section-divider"><span>Connexion</span></div>
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email"
                        placeholder="exemple@email.com"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
 
                {{-- Mot de passe --}}
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password"
                            placeholder="Minimum 8 caractères" autocomplete="new-password" required>
                        <button type="button" class="toggle-password" data-target="password">
                            <svg class="eye-on" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.4M9.9 4.24a9.122 9.122 0 012.1-.24c4.478 0 8.268 2.943 9.542 7a10.028 10.028 0 01-4.012 5.34M12 9a3 3 0 00-3 3M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
 
                {{-- Confirmation mot de passe --}}
                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <div class="password-container">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Répétez le mot de passe" autocomplete="new-password" required>
                        <button type="button" class="toggle-password" data-target="password_confirmation">
                            <svg class="eye-on" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.4M9.9 4.24a9.122 9.122 0 012.1-.24c4.478 0 8.268 2.943 9.542 7a10.028 10.028 0 01-4.012 5.34M12 9a3 3 0 00-3 3M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>
 
                {{-- Contact --}}
                <div class="section-divider"><span>Contact</span></div>
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="text" id="telephone" name="telephone"
                        placeholder="Votre numéro de téléphone"
                        value="{{ old('telephone') }}">
                    @error('telephone')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
 
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse"
                        placeholder="Votre adresse (optionnel)"
                        value="{{ old('adresse') }}">
                </div>
 
                {{-- Rôle --}}
                <div class="section-divider"><span>Profil</span></div>
                <div class="form-group">
                    <label for="role">Je suis…</label>
                    <select id="role" name="role" required>
                        <option value="">Choisir un rôle</option>
                        <option value="client"     {{ old('role') == 'client'     ? 'selected' : '' }}>Client</option>
                        <option value="freelancer" {{ old('role') == 'freelancer' ? 'selected' : '' }}>Freelancer</option>
                    </select>
                    @error('role')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
 
                <button type="submit" class="btn-register">
                    Créer mon compte
                </button>
 
            </form>
 
            <div class="register-footer">
                Déjà un compte ?
                <a href="{{ route('login') }}">Se connecter</a>
            </div>
 
        </div>
    </div>
 
    {{-- ================= JAVASCRIPT ================= --}}
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const eyeOnIcon = this.querySelector('.eye-on');
                const eyeOffIcon = this.querySelector('.eye-off');
 
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOnIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'block';
                } else {
                    passwordInput.type = 'password';
                    eyeOnIcon.style.display = 'block';
                    eyeOffIcon.style.display = 'none';
                }
            });
        });
    </script>
 
</body>
</html>
