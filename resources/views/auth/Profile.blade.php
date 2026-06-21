{{-- resources/views/profile.blade.php --}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - EtudServ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
/* ================= GOOGLE FONT ================= */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

/* ===== PALETTE TEAL MUTUALISÉE ===== */
:root {
    --color-deepest:  #05161A; /* Noir teal profond */
    --color-dark:     #072E33; /* Vert sombre navbar */
    --color-mid:      #0C7075; /* Teal moyen */
    --color-accent:   #0CABA8; /* Teal vif */
    --color-light:    #6DA5C0; /* Bleu-gris clair */
    --color-slate:    #294D61; /* Bleu ardoise */
    --bg-main:       #EDF6F9; /* Fond page */
    --bg-card:       #F5FBFC; /* Surface cartes */
    --text-dark:     #05161A; /* Texte principal */
    --text-muted:    #3D6A7A; /* Texte secondaire */
    --border-color:  #C5DDE5; /* Bordures douces */
    --warning:       #f59e0b; /* Étoiles / Avis */
    --badge-bg:      #E0F4F4; /* Fond badge */
}

body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-main);
    color: var(--text-dark);
    min-height: 100vh;
}

/* ================= NAVBAR ================= */
.navbar {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 80px;
    background: var(--color-dark);
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 15px rgba(0,0,0,0.06);
}

.logo { font-size: 28px; font-weight: 700; letter-spacing: -0.5px; }
.logo-blue { color: var(--color-light); }
.logo-dark { color: #ffffff; font-weight: 500; }

.navbar a {
    color: #ffffff;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.3s ease;
}

.navbar a:hover {
    color: var(--color-accent);
}

/* ================= CONTAINER ================= */
.container {
    max-width: 850px;
    margin: 40px auto;
    padding: 0 20px;
}

/* ================= PROFILE HEADER ================= */
.profile-header {
    background: var(--bg-card);
    border-radius: 25px;
    padding: 35px;
    display: flex;
    align-items: center;
    gap: 30px;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    margin-bottom: 25px;
    position: relative;
}

.avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--color-accent), var(--color-slate));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 8px 20px rgba(12,171,168,0.35);
}

.profile-info {
    flex: 1;
}

.profile-info h1 {
    font-size: 26px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 8px;
    letter-spacing: -0.5px;
}

.profile-info p {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.profile-info p i {
    color: var(--color-accent);
    width: 16px;
}

.badge {
    display: inline-block;
    background: var(--badge-bg);
    color: var(--color-accent);
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 10px;
    border: 1px solid rgba(12, 171, 168, 0.15);
}

.badge-client {
    background: #FFF7ED;
    color: var(--warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

/* ================= MEMBER SINCE EXTRA CONTAINER ================= */
.member-since {
    background: var(--bg-main);
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border: 1px solid var(--border-color);
    width: 100%;
    max-width: 320px;
    margin-top: 12px;
}

.member-since .label {
    font-size: 11px;
    color: var(--text-muted);
    margin-bottom: 2px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.member-since .date {
    font-size: 13px;
    color: var(--text-dark);
    font-weight: 500;
}

.member-since .duration {
    font-size: 14px;
    font-weight: 700;
    color: var(--color-accent);
    background: white;
    padding: 4px 10px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

/* ================= CARDS GENERAL STYLING ================= */
.card {
    background: var(--bg-card);
    border-radius: 25px;
    padding: 35px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    margin-bottom: 25px;
}

.card h2 {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card h2 i { color: var(--color-accent); }

.bio-text {
    color: var(--text-muted);
    font-size: 15px;
    line-height: 1.7;
}

/* ================= STATS ROW ================= */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
}

.stat-box {
    background: var(--bg-main);
    border-radius: 18px;
    padding: 20px;
    text-align: center;
    border: 1px solid var(--border-color);
    transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-4px);
    border-color: var(--color-accent);
    box-shadow: 0 10px 20px rgba(0,0,0,0.04);
}

.stat-box .num {
    font-size: 28px;
    font-weight: 800;
    color: var(--text-dark);
}

.stat-box .label {
    font-size: 13px;
    color: var(--text-muted);
    margin-top: 4px;
    font-weight: 500;
}

/* ================= AVIS / REVIEWS ================= */
.avis-item {
    border-bottom: 1px solid var(--border-color);
    padding: 20px 0;
}

.avis-item:first-child { padding-top: 0; }
.avis-item:last-child { border-bottom: none; padding-bottom: 0; }

.avis-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.avis-name { font-weight: 700; font-size: 15px; color: var(--text-dark); }
.avis-date { font-size: 12px; color: var(--text-muted); font-weight: 500; }

.stars { color: var(--warning); font-size: 13px; margin-bottom: 8px; }

.avis-comment { color: var(--text-muted); font-size: 14px; line-height: 1.6; }

.empty-text {
    text-align: center;
    color: var(--text-muted);
    padding: 30px 0;
    font-size: 14px;
}

/* ================= PROJETS PUBLIÉS (CLIENT) ================= */
.projet-item {
    border-bottom: 1px solid var(--border-color);
    padding: 18px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.projet-item:first-child { padding-top: 0; }
.projet-item:last-child { border-bottom: none; padding-bottom: 0; }

.projet-titre { font-weight: 700; font-size: 14px; color: var(--text-dark); margin-bottom: 4px; }
.projet-cat { font-size: 12px; color: var(--text-muted); }
.projet-budget {
    background: #F0FDF4;
    color: #16A34A;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    flex-shrink: 0;
}

/* ================= BOUTON MESSAGERIE (CLIENT) ================= */
.btn-message {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(90deg, var(--color-accent), var(--color-slate));
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 8px 20px rgba(12,171,168,0.25);
}
.btn-message:hover { transform: translateY(-2px); }

/* RESPONSIVE DESIGN */
@media (max-width: 768px) {
    .navbar { padding: 20px 30px; }
    .profile-header { flex-direction: column; text-align: center; padding: 30px 20px; }
    .profile-info p { justify-content: center; }
    .member-since { margin: 15px auto 0 auto; }
    .projet-item { flex-direction: column; align-items: flex-start; gap: 8px; }
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
            <div class="avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
                @if($utilisateur && $utilisateur->photo)
                    <img src="{{ asset('storage/' . $utilisateur->photo) }}" alt="Photo de {{ $utilisateur->prenom }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr($utilisateur->prenom ?? $utilisateur->nom, 0, 1)) }}
                @endif
</div>
            <div class="profile-info">
                <h1>{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h1>
                <p><i class="fa-regular fa-envelope"></i> {{ $utilisateur->email }}</p>
                <p><i class="fa-regular fa-calendar"></i> Membre depuis le {{ \Carbon\Carbon::parse($utilisateur->date_inscription)->format('d/m/Y') }}</p>
                @if($prestataireProfil)
                    <span class="badge">{{ $prestataireProfil->type_prestataire }} — {{ $prestataireProfil->niveau }}</span>
                @else
                    <span class="badge badge-client">💼 Client</span>
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

        {{-- ===== STATISTIQUES CLIENT ===== --}}
        @php $projets = session('projets', []); @endphp
        <div class="card">
            <h2><i class="fa-solid fa-chart-line"></i> Statistiques</h2>
            <div class="stats-row">
                <div class="stat-box">
                    <div class="num">{{ count($projets) }}</div>
                    <div class="label">Projets publiés</div>
                </div>
                <div class="stat-box">
                    <div class="num">
                        {{ \App\Models\Conversion::where('utilisateur_idutilisateur', $utilisateur->idutilisateur)->count() }}
                    </div>
                    <div class="label">Conversations actives</div>
                </div>
            </div>
        </div>

        {{-- ===== PROJETS PUBLIÉS (CLIENT) ===== --}}
        <div class="card">
            <h2><i class="fa-solid fa-briefcase"></i> Projets publiés</h2>
            @forelse($projets as $projet)
                <div class="projet-item">
                    <div>
                        <div class="projet-titre">{{ $projet['titre'] }}</div>
                        <div class="projet-cat">{{ $projet['categorie'] ?? 'Non catégorisé' }}</div>
                    </div>
                    @if(isset($projet['budget']))
                        <span class="projet-budget">{{ number_format($projet['budget'], 0, ',', ' ') }} MRU</span>
                    @endif
                </div>
            @empty
                <p class="empty-text">Aucun projet publié pour le moment.</p>
            @endforelse
        </div>

        {{-- ===== MESSAGERIE (CLIENT) ===== --}}
        <div class="card">
            <h2><i class="fa-solid fa-comments"></i> Messagerie</h2>
            <a href="{{ route('messagerie.index') }}" class="btn-message">
                <i class="fa-solid fa-paper-plane"></i> Accéder à la messagerie
            </a>
        </div>

        <div class="card">
            <p class="empty-text">Cet utilisateur n'a pas encore de profil prestataire.</p>
        </div>
        @endif

    </div>

</body>
</html>