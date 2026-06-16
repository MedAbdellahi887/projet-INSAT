<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - EtudServ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #F4F7FB;
            color: #1B2559;
            min-height: 100vh;
        }

        .member-since {
                    background: #f8f9ff;
                    border-radius: 12px;
                    padding: 14px 16px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 8px;
                    border: 1px solid #e8ecf8;
                    width: 300px;
                    }

        .member-since .label {
                        font-size: 11px;
                        color: #aaa;
                        margin-bottom: 4px;
                        font-weight: 500;
                        }

        .member-since .date {
                        font-size: 13px;
                        color: #555;
                        }

        .member-since .duration {
                    font-size: 15px;
                    font-weight: 700;
                    color: #3b5bdb;
                }

        .navbar {
            background: white;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .logo { font-size: 26px; font-weight: 700; }
        .logo-blue { color: #39C6E9; }
        .logo-dark { color: #1B2559; font-weight: 500; }

        .navbar a {
            color: #1B2559;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .profile-header {
            background: white;
            border-radius: 20px;
            padding: 40px;
            display: flex;
            align-items: center;
            gap: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #7B61FF;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .profile-info h1 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .profile-info p {
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .badge {
            display: inline-block;
            background: #EAFBFF;
            color: #39C6E9;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .card h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card h2 i { color: #45D1EC; }

        .stats-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .stat-box {
            flex: 1;
            min-width: 140px;
            background: #F8FAFC;
            border-radius: 14px;
            padding: 18px;
            text-align: center;
        }

        .stat-box .num {
            font-size: 28px;
            font-weight: 800;
            color: #1B2559;
        }

        .stat-box .label {
            font-size: 13px;
            color: #6B7280;
            margin-top: 4px;
        }

        .avis-item {
            border-bottom: 1px solid #F1F5F9;
            padding: 16px 0;
        }

        .avis-item:last-child { border-bottom: none; }

        .avis-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .avis-name { font-weight: 700; font-size: 14px; }
        .avis-date { font-size: 12px; color: #9CA3AF; }

        .stars { color: #F59E0B; font-size: 14px; margin-bottom: 6px; }

        .avis-comment { color: #4B5563; font-size: 14px; }

        .empty-text {
            text-align: center;
            color: #9CA3AF;
            padding: 20px 0;
            font-size: 14px;
        }

        .bio-text {
            color: #4B5563;
            font-size: 15px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <header class="navbar">
        <div class="logo">
            <span class="logo-blue">Etud</span><span class="logo-dark">Serv</span>
        </div>
        <a href="{{ url()->previous() }}"><i class="fa-solid fa-arrow-left"></i> Retour</a>
    </header>
     
    <div class="container">

        <div class="profile-header">
            <div class="avatar">{{ strtoupper(substr($utilisateur->prenom ?? $utilisateur->nom, 0, 1)) }}</div>
            <div class="profile-info">
                <h1>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h1>
                <p><i class="fa-regular fa-envelope"></i> {{ $utilisateur->email }}</p>
                <p><i class="fa-regular fa-calendar"></i> Membre depuis le {{ \Carbon\Carbon::parse($utilisateur->date_inscription)->format('d/m/Y') }}</p>
                @if($prestataireProfil)
                    <span class="badge">{{ $prestataireProfil->type_prestataire }} — {{ $prestataireProfil->niveau }}</span>
                @endif
            </div>
        </div>
        
        @if($prestataireProfil)
        <div class="card">
            <h2><i class="fa-solid fa-user-pen"></i> Biographie</h2>
            <p class="bio-text">{{ $prestataireProfil->bio ?? 'Aucune biographie renseignée.' }}</p>
        </div>

        <div class="card">
            <h2><i class="fa-solid fa-chart-line"></i> Statistiques</h2>
            <div class="stats-row">
                <div class="stat-box">
                    <div class="num">{{ $projetsRealises }}</div>
                    <div class="label">Projets réalisés</div>
                </div>
                <div class="stat-box">
                    <div class="num">{{ number_format($prestataireProfil->note_moyenne ?? 0, 1) }} / 5</div>
                    <div class="label">Note moyenne</div>
                </div>
                <div class="stat-box">
                    <div class="num">{{ $avisRecus->count() }}</div>
                    <div class="label">Avis reçus</div>
                </div>
            </div>
        </div>

        <div class="card">
            <h2><i class="fa-solid fa-star"></i> Avis reçus</h2>
            @forelse($avisRecus as $avis)
                <div class="avis-item">
                    <div class="avis-top">
                        <span class="avis-name">{{ $avis->utilisateur->prenom ?? '' }} {{ $avis->utilisateur->nom ?? 'Utilisateur' }}</span>
                        <span class="avis-date">{{ \Carbon\Carbon::parse($avis->date_avis)->format('d/m/Y') }}</span>
                    </div>
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $avis->note)
                                <i class="fa-solid fa-star"></i>
                            @else
                                <i class="fa-regular fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    @if($avis->commentaire)
                        <div class="avis-comment">{{ $avis->commentaire }}</div>
                    @endif
                </div>
            @empty
                <p class="empty-text">Aucun avis reçu pour le moment.</p>
            @endforelse
        </div>
        @else
        <div class="card">
            <p class="empty-text">Cet utilisateur n'a pas encore de profil prestataire.</p>
        </div>
        @endif

    </div>

</body>
</html>