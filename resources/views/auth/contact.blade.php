<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>EtudServ – Contact</title>
    <style>
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

        .contact-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
        }

        .contact-box {
            background: white;
            border-radius: 24px;
            padding: 50px 45px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
        }

        .contact-box h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #1B2559;
        }

        .contact-box p.subtitle {
            font-size: 15px;
            color: #6B7280;
            margin-bottom: 35px;
        }

        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
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

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus { border-color: #52D6F4; }

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

        /* Style de la boîte d'alerte rouge pointillée */
        .auth-alert-box {
            background: #FEF2F2;
            border: 1.5px dashed #FCA5A5;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            margin-top: 15px;
        }

        .auth-alert-box p {
            color: #DC2626;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 14px;
        }

        .btn-login-redirect {
            display: inline-block;
            background: #39C6E9;
            color: white;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(57, 198, 233, 0.25);
        }

        .btn-login-redirect:hover {
            background: #28B3D4;
            transform: translateY(-1px);
        }

        .btn-send {
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

        .btn-send:hover {
            background: #35C4E6;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <header class="navbar">
        <div class="logo">
            <span class="logo-blue">Etud</span><span class="logo-dark">Serv</span>
        </div>
    </header>

    <div class="contact-wrapper">
        <div class="contact-box">
            <h2>{{ $destinataire ? 'Contacter ' . $destinataire : 'Contactez-nous' }}</h2>
            <p class="subtitle">Envoyez-nous votre message</p>

            {{-- Affichage du message de succès si la session réussit --}}
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            {{-- Vérification de la session utilisateur pour l'affichage correct --}}
            @if(session()->has('utilisateur_id'))
                
                {{-- FORMULAIRE ACTIF : Si l'utilisateur est connecté --}}
                <form action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="destinataire" value="{{ $destinataire }}">

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Votre email" value="{{ old('email') }}" required>
                        @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" placeholder="Votre message" required>{{ old('message') }}</textarea>
                        @error('message') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn-send">Envoyer</button>
                </form>

            @else

                {{-- FORMULAIRE BLOQUÉ : Si l'utilisateur est un simple visiteur --}}
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" placeholder="Votre nom" disabled style="background: #F3F4F6; cursor: not-allowed;">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" placeholder="Votre email" disabled style="background: #F3F4F6; cursor: not-allowed;">
                </div>

                <div class="form-group">
                    <label>Message</label>
                    <textarea placeholder="Votre message" disabled style="background: #F3F4F6; cursor: not-allowed; height: 150px; resize: none;"></textarea>
                </div>

                {{-- Boîte d'alerte rouge pointillée --}}
                <div class="auth-alert-box">
                    <p>⚠️ Vous devez vous connecter pour pouvoir envoyer un message.</p>
                    <a href="{{ route('login') }}" class="btn-login-redirect">Se connecter</a>
                </div>

            @endif
        </div>
    </div>
</body>
</html>