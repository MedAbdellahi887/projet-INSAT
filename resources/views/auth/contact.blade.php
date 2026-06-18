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

/* ================= WRAPPER & BULLES ================= */
.contact-wrapper {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 60px 20px;
    position: relative;
    overflow: hidden;
}

.contact-wrapper::before {
    content: '';
    position: absolute;
    top: -120px;
    right: -120px;
    width: 420px;
    height: 420px;
    background: radial-gradient(circle, rgba(12,171,168,0.18) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

.contact-wrapper::after {
    content: '';
    position: absolute;
    bottom: -100px;
    left: -100px;
    width: 380px;
    height: 380px;
    background: radial-gradient(circle, rgba(109,165,192,0.20) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
}

/* ================= CONTACT CARD ================= */
.contact-box {
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
.contact-box::before {
    content: '';
    position: absolute;
    top: 0; left: 40px; right: 40px;
    height: 3px;
    background: linear-gradient(90deg, #0CABA8, #6DA5C0);
    border-radius: 0 0 4px 4px;
}

.contact-box h2 {
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 6px;
    color: #05161A;
    letter-spacing: -0.3px;
    text-align: center;
}

.contact-box p.subtitle {
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

.form-group input,
.form-group textarea {
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

.form-group textarea {
    height: 150px;
    resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #0CABA8;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(12,171,168,0.12);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #6DA5C0;
    font-size: 13.5px;
}

/* Apparence grisée pour les champs désactivés */
.form-group input:disabled,
.form-group textarea:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background: rgba(109,165,192,0.1) !important;
    border-color: transparent !important;
}

/* ================= ERREURS & ALERTES ================= */
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
    text-align: center;
}

/* Alerte pour utilisateur non connecté */
.auth-alert-box {
    background: rgba(239, 68, 68, 0.05);
    border: 1.5px dashed rgba(239, 68, 68, 0.4);
    padding: 20px;
    border-radius: 14px;
    text-align: center;
    margin-top: 20px;
}

.auth-alert-box p {
    color: #EF4444;
    font-size: 13.5px;
    font-weight: 600;
    margin-bottom: 14px;
}

/* ================= BOUTONS ================= */
.btn-send {
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

.btn-send:hover {
    background: linear-gradient(135deg, #0A9996 0%, #072E33 100%);
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(12,171,168,0.40);
}

.btn-send:active { transform: translateY(0); }

.btn-login-redirect {
    display: inline-block;
    background: linear-gradient(135deg, #0CABA8 0%, #0A9996 100%);
    color: white;
    text-decoration: none;
    padding: 12px 26px;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(12,171,168,0.25);
}

.btn-login-redirect:hover {
    background: linear-gradient(135deg, #0A9996 0%, #072E33 100%);
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(12,171,168,0.35);
}

/* ================= RESPONSIVE ================= */
@media (max-width: 600px) {
    .navbar { padding: 18px 24px; }
    .contact-box { padding: 38px 24px; }
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
