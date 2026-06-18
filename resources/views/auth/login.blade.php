<!DOCTYPE html>
<html lang="fr">
 
<head>
    <meta charset="utf-8">
    <title>EtudServ – Connexion</title>
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
 
        .logo { font-size: 30px; font-weight: 700; letter-spacing: -0.5px;}
        .logo-blue { color: #6DA5C0; }
        .logo-dark { color: #EDF6F9; font-weight: 400; }
        .logo a{text-decoration: none;}
 
        /* ================= WRAPPER ================= */
        .login-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            position: relative;
        }
 
        .login-wrapper::before {
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
 
        .login-wrapper::after {
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
        .login-box {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1.5px solid rgba(109,165,192,0.25);
            border-radius: 28px;
            padding: 52px 48px;
            width: 100%;
            max-width: 460px;
            box-shadow:
                0 8px 32px rgba(7,46,51,0.10),
                0 2px 8px rgba(12,171,168,0.08);
            position: relative;
            z-index: 1;
        }
 
        /* Accent teal en haut de la card */
        .login-box::before {
            content: '';
            position: absolute;
            top: 0; left: 40px; right: 40px;
            height: 3px;
            background: linear-gradient(90deg, #0CABA8, #6DA5C0);
            border-radius: 0 0 4px 4px;
        }
 
        .login-box h2 {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #05161A;
            letter-spacing: -0.3px;
            text-align: center;
        }
 
        .login-box p.subtitle {
            font-size: 14.5px;
            color: #294D61;
            margin-bottom: 32px;
            font-weight: 400;
            text-align: center;
        }
 
        /* ================= FORM ================= */
        .form-group {
            margin-bottom: 18px;
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
 
        .form-group input {
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
 
        .form-group input:focus {
            border-color: #0CABA8;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(12,171,168,0.12);
        }
 
        .form-group input::placeholder {
            color: #6DA5C0;
            font-size: 13.5px;
        }
 
        /* ================= PASSWORD TOGGLE ================= */
        .password-container {
            position: relative;
            width: 100%;
        }
 
        .password-container input {
            padding-right: 50px;
        }
 
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
 
        /* ================= ERREURS ================= */
        .form-group .error-msg {
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
        .btn-login {
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
 
.btn-login:hover {
    background: linear-gradient(135deg, #0A9996 0%, #072E33 100%);
    box-shadow: 0 12px 28px rgba(12,171,168,0.40);
    
    /* On applique l'animation avec une durée (ex: 0.6s) */
    animation: traceingite 0.6s ease-in-out forwards;
}

/* Définition de l'animation "traceingite" (Effet d'impulsion et de balayage lumineux) */
@keyframes traceingite {
    0% {
        transform: scale(1);
        filter: brightness(1);
    }
    50% {
        transform: scale(1.03);
        /* Crée un éclat lumineux temporaire au milieu de l'action */
        filter: brightness(1.25); 
        box-shadow: 0 14px 32px rgba(12,171,168,0.60);
    }
    100% {
        transform: scale(1.02);
        filter: brightness(1.1);
    }
}

 
        .btn-login:active { transform: translateY(0); }
 
        /* ================= FOOTER ================= */
        .login-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 13.5px;
            color: #294D61;
        }
 
        .login-footer a {
            color: #0CABA8;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
 
        .login-footer a:hover {
            color: #072E33;
            text-decoration: underline;
        }
 
        /* ================= SUPPRIMER COMPTE ================= */
        .delete-section {
            text-align: center;
            margin-top: 14px;
        }
 
        .delete-section a {
            font-size: 17,5px;
            font-weight: 400;
            color:#0A9996;
            text-decoration: none;
            opacity: 0.7;
            transition: opacity 0.2s, color 0.2s;
        }
 
        .delete-section a:hover {
            color:#072E33;
            opacity: 1;
            text-decoration: underline;
        }
 
        /* ================= RESPONSIVE ================= */
        @media (max-width: 600px) {
            .navbar { padding: 18px 24px; }
            .login-box { padding: 38px 24px; }
        }
    </style>
</head>
 
<body>
 
    {{-- ================= NAVBAR ================= --}}
    <header class="navbar">
        <div class="logo">
            <a href="{{ route('home') }} "><span class="logo-blue">Etud</span><span class="logo-dark">Serv</span></a>
        </div>
    </header>
 
    {{-- ================= LOGIN FORM ================= --}}
    <div class="login-wrapper">
        <div class="login-box">
 
            <h2>Bon retour </h2>
            <p class="subtitle">Connectez-vous à votre compte EtudServ</p>
 
            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
 
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
 
                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="exemple@email.com"
                        value="{{ old('email') }}"
                        required
                    >
                    @error('email')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
 
                {{-- Mot de passe --}}
                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <div class="password-container">
                        <input
                            type="password"
                            id="mot_de_passe"
                            name="mot_de_passe"
                            placeholder="Votre mot de passe"
                            required
                        >
                        <button type="button" class="toggle-password" data-target="mot_de_passe">
                            <svg class="eye-on" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.4M9.9 4.24a9.122 9.122 0 012.1-.24c4.478 0 8.268 2.943 9.542 7a10.028 10.028 0 01-4.012 5.34M12 9a3 3 0 00-3 3M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('mot_de_passe')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
 
                <button type="submit" class="btn-login">
                    Se connecter
                </button>
 
            </form>
 
            <div class="login-footer">
                Pas encore de compte ?
                <a href="{{ route('register') }}">Rejoindre EtudServ</a>
            </div>
 
            <div class="delete-section">
                <a href="#" onclick="event.preventDefault(); confimerSuppression();">
                    Supprimer mon compte
                </a>
                <form id="delete-user-form" action="{{ route('users.delete') }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="email" id="delete-email-input">
                </form>
            </div>
 
        </div>
    </div>
 
    {{-- ================= JAVASCRIPT ================= --}}
    <script>
        function confimerSuppression() {
            var emailSaisi = document.getElementById('email').value;
 
            if (!emailSaisi) {
                alert("Veuillez d'abord saisir votre adresse email dans le champ de connexion pour pouvoir supprimer votre compte.");
                return;
            }
 
            if (confirm("Êtes-vous absolument sûr de vouloir supprimer le compte lié à l'adresse " + emailSaisi + " ? Cette action est irréversible.")) {
                document.getElementById('delete-email-input').value = emailSaisi;
                document.getElementById('delete-user-form').submit();
            }
        }
 
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const eyeOn  = this.querySelector('.eye-on');
                const eyeOff = this.querySelector('.eye-off');
 
                if (input.type === 'password') {
                    input.type = 'text';
                    eyeOn.style.display  = 'none';
                    eyeOff.style.display = 'block';
                } else {
                    input.type = 'password';
                    eyeOn.style.display  = 'block';
                    eyeOff.style.display = 'none';
                }
            });
        });
    </script>
 
</body>
</html>
 
