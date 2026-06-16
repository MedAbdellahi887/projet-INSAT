<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EtudServ - Vérification OTP</title>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
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
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
        }
        h2 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; font-size: 14px; margin-bottom: 30px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #444; font-weight: 500; }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            text-align: center;
            letter-spacing: 4px;
        }
        .btn-verify {
            width: 100%;
            padding: 14px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn-verify:hover { background-color: #0056b3; }
        .error-message { color: #dc3545; font-size: 13px; margin-top: 5px; display: block; text-align: left; }
        
        /* Nouveaux styles pour le renvoi de code */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .resend-container {
            margin-top: 25px;
            border-top: 1px solid #eee;
            padding-top: 15px;
            font-size: 14px;
            color: #666;
        }
        .btn-resend {
            background: none;
            border: none;
            color: #007bff;
            cursor: pointer;
            font-weight: bold;
            text-decoration: underline;
            padding: 0;
            font-size: 14px;
        }
        .btn-resend:disabled {
            color: #aaa;
            cursor: not-allowed;
            text-decoration: none;
        }
        .logo-text{
            font-size:50px;
            font-weight:bold;
            display: inline-block;
            animation: glisseBas 2s ease-in-out infinite alternate;
        }
        /* Définition du mouvement */
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
            <span class="logo-text"><span style="color: #0bbeff;">Etud</span>Serv</span>
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