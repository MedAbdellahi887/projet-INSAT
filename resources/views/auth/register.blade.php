{{-- resources/views/auth/register.blade.php --}}

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>EtudServ – Inscription</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght=300;400;500;600;700;800&display=swap');

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
        .logo-blue { color: #39C6E9; }
        .logo-dark { color: #1B2559; font-weight: 500; }

        /* ================= WRAPPER ================= */
        .register-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
        }

        .register-box {
            background: white;
            border-radius: 24px;
            padding: 50px 45px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
        }

        .register-box h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1B2559;
        }

        .register-box p.subtitle {
            font-size: 15px;
            color: #6B7280;
            margin-bottom: 35px;
        }

        /* ================= FORM ================= */
        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 18px;
            flex: 1;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        /* Conteneur pour positionner l'icône de l'œil */
        .password-container {
            position: relative;
            width: 100%;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            color: #1B2559;
            outline: none;
            transition: border-color 0.3s;
            background: white;
        }

        /* Espace à droite pour éviter que le texte passe sous l'œil */
        .password-container input {
            padding-right: 50px;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #52D6F4;
        }

        /* Bouton de l'œil */
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

        .toggle-password:hover {
            color: #39C6E9;
        }

        .toggle-password svg {
            width: 22px;
            height: 22px;
        }

        /* Masquer par défaut l'œil barré */
        .toggle-password .eye-off {
            display: none;
        }

        /* Style spécifique pour personnaliser la flèche du select */
        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%231B2559' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
            background-repeat: no-repeat;
            background-position: right 18px center;
            background-size: 16px;
            padding-right: 45px;
        }

        .error-msg {
            color: #EF4444;
            font-size: 13px;
            margin-top: 6px;
            display: block;
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
        .btn-register {
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

        .btn-register:hover {
            background: #35C4E6;
            transform: translateY(-2px);
        }

        .register-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #6B7280;
        }

        .register-footer a {
            color: #39C6E9;
            font-weight: 600;
            text-decoration: none;
        }

        .register-footer a:hover { text-decoration: underline; }
        
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

                {{-- Confirmation du mot de passe --}}
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

                {{-- Téléphone --}}
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="text" id="telephone" name="telephone"
                        placeholder="Votre numéro de téléphone"
                        value="{{ old('telephone') }}">
                    @error('telephone')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Adresse --}}
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse"
                        placeholder="Votre adresse (optionnel)"
                        value="{{ old('adresse') }}">
                </div>

                {{-- Rôle --}}
                <div class="form-group">
                    <label for="role">Je suis...</label>
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