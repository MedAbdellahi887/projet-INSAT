<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EtudServ - Vérification OTP</title>
    
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #EDF6F9 0%, #d4eef5 40%, #b8e0ec 100%);
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}
.register-wrapper {
    width: 100%;
    max-width: 450px;
    padding: 20px;
}
.register-box {
    background: rgba(255, 255, 255, 0.82);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(7,46,51,0.10);
    text-align: center;
    border: 1.5px solid rgba(109,165,192,0.25);
}
h2 { color: #05161A; margin-bottom: 10px; }
.subtitle { color: #294D61; font-size: 14px; margin-bottom: 30px; }
.form-group { text-align: left; margin-bottom: 20px; }
label { display: block; margin-bottom: 8px; color: #072E33; font-weight: 500; }
input[type="text"] {
    width: 100%;
    padding: 12px;
    border: 1.5px solid rgba(109,165,192,0.45);
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 16px;
    text-align: center;
    letter-spacing: 4px;
    background: rgba(237,246,249,0.70);
    color: #05161A;
}
input[type="text"]:focus {
    border-color: #0CABA8;
    outline: none;
    box-shadow: 0 0 0 3px rgba(12,171,168,0.12);
}
.btn-verify {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #0CABA8 0%, #0A9996 100%);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s ease;
    box-shadow: 0 8px 24px rgba(12,171,168,0.35);
}
.btn-verify:hover { background: linear-gradient(135deg, #0A9996 0%, #072E33 100%); }
.error-message { color: #EF4444; font-size: 13px; margin-top: 5px; display: block; text-align: left; }

/* Nouveaux styles pour le renvoi de code */
.success-message {
    background: linear-gradient(135deg, #ECFDF5, #d1fae5);
    color: #065F46;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
    border: 1px solid #A7F3D0;
}
.resend-container {
    margin-top: 25px;
    border-top: 1px solid rgba(109,165,192,0.25);
    padding-top: 15px;
    font-size: 14px;
    color: #294D61;
}
.btn-resend {
    background: none;
    border: none;
    color: #0CABA8;
    cursor: pointer;
    font-weight: bold;
    text-decoration: underline;
    padding: 0;
    font-size: 14px;
}
.btn-resend:hover { color: #072E33; }
.btn-resend:disabled {
    color: #6DA5C0;
    cursor: not-allowed;
    text-decoration: none;
    opacity: 0.6;
}
.logo-text{
    font-size:50px;
    font-weight:bold;
    display: inline-block;
    animation: glisseBas 2s ease-in-out infinite alternate;
}

/* Définition du mouvement de l'animation */
@keyframes glisseBas {
    0% {
        transform: translateY(0); /* Position de départ */
    }
    100% {
        transform: translateY(30px); /* Descend de 30 pixels */
    }
}
    </style>
</head>
<body>

    <header class="navbar">
        <div class="logo">
            <span class="logo-text"><span style="color: #6DA5C0;">Etud</span>Serv</span>
        </div>
    </header>

    <div class="register-wrapper">
        <div class="register-box">
            <h2>Vérification du compte</h2>
            <p class="subtitle">Un code de vérification a été envoyé à l'adresse : <br><strong>{{ session('email') }}</strong></p>

            @if(session('success_resend'))
                <div class="success-message">
                    {{ session('success_resend') }}
                </div>
            @endif

            <form action="{{ session('activating') ? route('otp.activate') : route('otp.verify') }}" method="POST">
                @csrf
                
                <input type="hidden" name="email" value="{{ session('email') }}">

                <div class="form-group">
                    <label for="code">Code de vérification (6 chiffres)</label>
                    <input type="text" id="code" name="code" placeholder="000000" maxlength="6" required autocomplete="off">
                    
                    @error('code')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-verify">Vérifier mon compte</button>
            </form>

            <div class="resend-container">
                <p>Vous n'avez pas reçu le code ?</p>
                <form action="{{ route('otp.resend') }}" method="POST" id="resendForm">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">
                    <button type="submit" id="resendBtn" class="btn-resend">Renvoyer un code</button>
                </form>
                <span id="countdown" style="display: none; margin-top: 5px; font-weight: 500;"></span>
            </div>
            
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const resendBtn = document.getElementById('resendBtn');
        const countdownEl = document.getElementById('countdown');
        let timeLeft = 60; 
        let timer;

        function startTimer() {
            resendBtn.style.display = 'none';
            countdownEl.style.display = 'inline-block';
            countdownEl.innerText = `Veuillez patienter ${timeLeft}s avant un nouveau renvoi`;
            
            timer = setInterval(() => {
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    resendBtn.style.display = 'inline-block';
                    countdownEl.style.display = 'none';
                    timeLeft = 60;
                } else {
                    countdownEl.innerText = `Veuillez patienter ${timeLeft}s avant un nouveau renvoi`;
                }
            }, 1000);
        }

        // On utilise l'éco-système Blade pour imprimer directement 'true' ou 'false' en JS
        // Les guillemets ou accolades évitent de briser la syntaxe JS aux yeux de VS Code
        const hasResent = "{{ session('success_resend') ? 'true' : 'false' }}";
        
        if (hasResent) {
            startTimer();
        }

        document.getElementById('resendForm').addEventListener('submit', function() {
            resendBtn.disabled = true;
        });
    });
</script>

</body>
</html>
