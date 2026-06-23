{{-- resources/views/auth/login.blade.php --}}

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>EtudServ – Connexion</title>
    <style>
        /* ================= GOOGLE FONT ================= */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #F8FCFD;
            color: #1B2559;
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
            padding: 25px 80px;
            background: white;
        }

        .logo { font-size: 30px; font-weight: 700; }
        .logo-blue  { color: #39C6E9; }
        .logo-dark  { color: #1B2559; font-weight: 500; }

        /* ================= LOGIN WRAPPER ================= */
        .login-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
        }

        .login-box {
            background: white;
            border-radius: 24px;
            padding: 50px 45px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
        }

        .login-box h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1B2559;
        }

        .login-box p.subtitle {
            font-size: 15px;
            color: #6B7280;
            margin-bottom: 35px;
        }

        /* ================= FORM ================= */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: #1B2559;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #52D6F4;
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
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #A3AED0;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }

        .toggle-password:hover { color: #39C6E9; }

        .toggle-password svg { width: 22px; height: 22px; }

        .toggle-password .eye-off { display: none; }

        /* ================= ERREURS ================= */
        .form-group .error-msg {
            color: #EF4444;
            font-size: 13px;
            margin-top: 6px;
        }

        .alert-success {
            background: #ECFDF5;
            color: #065F46;
            border: 1px solid #A7F3D0;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* ================= BUTTON ================= */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: #52D6F4;
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 17px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(82, 214, 244, 0.35);
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #35C4E6;
            transform: translateY(-2px);
        }

        /* ================= LINK ================= */
        .login-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #6B7280;
        }

        .login-footer a {
            color: #39C6E9;
            font-weight: 600;
            text-decoration: none;
        }

        .login-footer a:hover { text-decoration: underline; }
        .div1{
            text-align: center;
        }
        .div1:hover {
            text-decoration:underline #35C4E6;
            transform:scale(1.2);
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

    {{-- ================= LOGIN FORM ================= --}}
    <div class="login-wrapper">
        <div class="login-box">

            <h2>Bon retour </h2>
            <p class="subtitle">Connectez-vous à votre compte EtudServ</p>

            {{-- Message de succès (ex: après déconnexion) --}}
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
            <div class="div1">
                <a href="#" 
                    style="text-decoration: none; font-weight: 400;color:#39C6E9;" 
                    onclick="event.preventDefault(); confimerSuppression();">
                    Supprimer mon compte
                </a>
           
                <form id="delete-user-form" action="{{ route('users.delete') }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="email" id="delete-email-input">
                </form>    
            
            </div>
    </div>
<script>

function confimerSuppression() {
    // 1. On récupère ce que l'utilisateur a écrit dans le champ email de connexion
    var emailSaisi = document.getElementById('email').value;

    // 2. Sécurité : Si le champ est vide, on l'avertit
    if (!emailSaisi) {
        alert("Veuillez d'abord saisir votre adresse email dans le champ de connexion pour pouvoir supprimer votre compte.");
        return;
    }

    // 3. Demande de confirmation
    if (confirm("Êtes-vous absolument sûr de vouloir supprimer le compte lié à l'adresse " + emailSaisi + " ? Cette action est irréversible.")) {
        
        // 4. On injecte l'email dans le formulaire caché avant de l'envoyer
        document.getElementById('delete-email-input').value = emailSaisi;
        
        // 5. On valide le formulaire
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
