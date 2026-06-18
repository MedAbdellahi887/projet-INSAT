<!DOCTYPE html>
<html lang="{{ session('lang', 'fr') }}" dir="{{ session('lang', 'fr') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EtudServ – Messagerie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

/* ======================================================
   VARIABLES & THÈME PROJET (LOGIN)
====================================================== */
:root {
    --c-deepest:     #05161A;
    --c-dark:        #072E33;
    --c-mid:         #0CABA8;
    --c-sky:         #6DA5C0;
    --c-slate-blue:  #294D61;
    --c-bg-input:    rgba(237, 246, 249, 0.70);
    
    --sidebar-w:   320px;
    --radius-lg:   28px;
    --radius-md:   14px;
    --radius-sm:   12px;
    --t:           0.25s cubic-bezier(.4,0,.2,1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #EDF6F9 0%, #d4eef5 40%, #b8e0ec 100%);
    color: var(--c-deepest);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    position: relative;
    overflow: hidden;
}

/* Bulles d'arrière-plan décoratives du projet */
body::before {
    content: '';
    position: fixed;
    top: -120px;
    right: -120px;
    width: 420px;
    height: 420px;
    background: radial-gradient(circle, rgba(12,171,168,0.18) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}

body::after {
    content: '';
    position: fixed;
    bottom: -100px;
    left: -100px;
    width: 380px;
    height: 380px;
    background: radial-gradient(circle, rgba(109,165,192,0.20) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}

/* ======================================================
   MAIN CARD (EFFET VERRE DÉPOLI / GLASSMORPHISM)
====================================================== */
.msg-card {
    width: 100%;
    max-width: 1180px;
    height: calc(100vh - 48px);
    max-height: 860px;
    display: flex;
    background: rgba(255, 255, 255, 0.82);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1.5px solid rgba(109, 165, 192, 0.25);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow:
        0 8px 32px rgba(7, 46, 51, 0.10),
        0 2px 8px rgba(12, 171, 168, 0.08);
    position: relative;
    z-index: 1;
}

/* Accent teal en haut de la card principale */
.msg-card::before {
    content: '';
    position: absolute;
    top: 0; left: 40px; right: 40px;
    height: 3px;
    background: linear-gradient(90deg, var(--c-mid), var(--c-sky));
    border-radius: 0 0 4px 4px;
    z-index: 10;
}

/* ======================================================
   SIDEBAR
====================================================== */
.msg-sidebar {
    width: var(--sidebar-w);
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    background: rgba(255, 255, 255, 0.45);
    border-right: 1.5px solid rgba(109, 165, 192, 0.2);
}

.sidebar-hd {
    padding: 26px 24px 22px;
    background: linear-gradient(90deg, var(--c-deepest) 0%, var(--c-dark) 100%);
    box-shadow: 0 4px 24px rgba(5,22,26,0.15);
}

.sidebar-hd-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.sidebar-hd-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.4px;
    text-transform: uppercase;
    color: var(--c-sky);
}

.btn-dashboard-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.25);
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 500;
    text-decoration: none;
    transition: all var(--t);
}

.btn-dashboard-link:hover {
    background: #fff;
    color: var(--c-deepest);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.sidebar-hd-title {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.3px;
}

.conv-list {
    flex: 1;
    overflow-y: auto;
    padding: 12px 8px;
}

.conv-item {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 12px 16px;
    cursor: pointer;
    border-radius: var(--radius-sm);
    text-decoration: none;
    color: var(--c-deepest);
    margin-bottom: 6px;
    transition: all var(--t);
}

.conv-item:hover  { 
    background: rgba(109, 165, 192, 0.15); 
    color: var(--c-deepest); 
}

.conv-item.active {
    background: rgba(12, 171, 168, 0.12);
    border-left: 4px solid var(--c-mid);
    font-weight: 500;
}

.conv-av {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.av-purple { background: linear-gradient(135deg, var(--c-slate-blue), #456d85); }
.av-blue   { background: linear-gradient(135deg, var(--c-dark), var(--c-sky)); }
.av-green  { background: linear-gradient(135deg, var(--c-mid), #3ce0dd); }
.av-rose   { background: linear-gradient(135deg, var(--c-sky), #9bc2d5); }

.conv-meta { flex: 1; min-width: 0; }
.conv-name { font-weight: 600; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.conv-role { font-size: 11px; color: var(--c-slate-blue); margin-top: 2px; opacity: 0.8; }
.conv-empty { padding: 36px 24px; text-align: center; color: var(--c-slate-blue); font-size: 13px; opacity: 0.7; }
.conv-empty i { font-size: 36px; display: block; margin-bottom: 12px; opacity: .4; }

/* ======================================================
   CHAT AREA
====================================================== */
.msg-chat {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: rgba(255, 255, 255, 0.35);
    min-width: 0;
}

.chat-hd {
    padding: 20px 28px;
    background: rgba(255, 255, 255, 0.65);
    border-bottom: 1px solid rgba(109, 165, 192, 0.2);
    display: flex;
    align-items: center;
    gap: 14px;
}

.chat-hd-av {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 700;
    color: #fff;
}

.chat-hd-info { flex: 1; }
.chat-hd-name { font-weight: 700; font-size: 16px; color: var(--c-deepest); }
.chat-hd-status { font-size: 12px; color: var(--c-slate-blue); margin-top: 2px; }

.status-dot {
    display: inline-block;
    width: 8px; height: 8px;
    border-radius: 50%;
    margin-right: 5px;
}

.chat-msgs {
    flex: 1;
    overflow-y: auto;
    padding: 28px 32px;
    display: flex;
    flex-direction: column;
}

.bw { display: flex; flex-direction: column; max-width: 66%; margin-bottom: 14px; }
.bw.sent  { align-items: flex-end;  margin-left: auto; }
.bw.recv  { align-items: flex-start; margin-right: auto; }

.bubble {
    padding: 13px 18px;
    border-radius: 16px;
    font-size: 14.5px;
    line-height: 1.5;
}

.bubble.sent {
    background: linear-gradient(135deg, var(--c-mid) 0%, #0A9996 100%);
    color: #fff;
    border-bottom-right-radius: 4px;
    box-shadow: 0 4px 16px rgba(12,171,168,0.25);
}

.bubble.recv {
    background: #ffffff;
    color: var(--c-deepest);
    border-bottom-left-radius: 4px;
    border: 1px solid rgba(109,165,192,0.3);
    box-shadow: 0 2px 8px rgba(7,46,51,0.04);
}

.bubble.is-rapport {
    background: linear-gradient(135deg, #f0fdfa, #ccfbf1) !important;
    color: var(--c-dark) !important;
    border: 2px dashed var(--c-mid) !important;
    box-shadow: 0 4px 12px rgba(12,171,168,0.15) !important;
}

.rapport-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--c-dark);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 6px;
    margin-bottom: 6px;
}

.bubble-time { font-size: 11px; color: var(--c-slate-blue); opacity: 0.7; margin-top: 5px; padding: 0 3px; }

.chat-empty {
    flex: 1; display: flex; flex-direction: column; align-items: center;
    justify-content: center; gap: 12px; color: var(--c-slate-blue); font-size: 14px;
}
.chat-empty-icon {
    width: 64px; height: 64px; background: rgba(109,165,192,0.2);
    border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;
}

/* ======================================================
   EVALUATION CÔTÉ CLIENT
====================================================== */
.eval-wrap {
    padding: 20px 28px;
    background: rgba(255, 255, 255, 0.6);
    border-top: 1px solid rgba(109, 165, 192, 0.2);
}

.eval-wrap h6 {
    font-size: 12px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1px; color: var(--c-dark); margin-bottom: 10px;
}

.star-rating { display: flex; flex-direction: row-reverse; gap: 4px; margin-bottom: 12px; justify-content: flex-end;}
.star-rating input { display: none; }
.star-rating label { font-size: 28px; color: rgba(109,165,192,0.4); cursor: pointer; transition: all 0.15s; }
.star-rating label:hover { transform: scale(1.15); }
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label { color: #F59E0B; }

.eval-textarea {
    width: 100%; border: 1.5px solid rgba(109,165,192,0.45); border-radius: 12px;
    padding: 12px 16px; font-size: 14px; background: var(--c-bg-input);
    color: var(--c-deepest); resize: none; outline: none; transition: all var(--t); margin-bottom: 12px;
}
.eval-textarea:focus { border-color: var(--c-mid); background: #fff; box-shadow: 0 0 0 3px rgba(12,171,168,0.12); }

.btn-eval {
    background: linear-gradient(135deg, var(--c-mid) 0%, #0A9996 100%);
    color: #fff; border: none; padding: 10px 24px; border-radius: 12px;
    font-size: 14px; font-weight: 600; cursor: pointer; transition: all var(--t);
    box-shadow: 0 4px 12px rgba(12,171,168,0.2);
}
.btn-eval:hover { background: linear-gradient(135deg, #0A9996 0%, var(--c-dark) 100%); transform: translateY(-1px); }

.eval-done { display: flex; align-items: center; gap: 8px; color: #0C9E72; font-weight: 600; font-size: 14px; }

/* ======================================================
   INPUT ZONE DE SAISIE
====================================================== */
.chat-input-bar {
    padding: 16px 24px;
    background: rgba(255, 255, 255, 0.65);
    border-top: 1px solid rgba(109, 165, 192, 0.2);
}

.input-row {
    display: flex; align-items: center; gap: 10px;
    background: var(--c-bg-input);
    border: 1.5px solid rgba(109,165,192,0.45);
    border-radius: 14px; padding: 6px 8px 6px 18px;
    transition: all var(--t);
}

.input-row:focus-within {
    border-color: var(--c-mid);
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(12,171,168,0.12);
}

.input-row input[type="text"] {
    flex: 1; border: none; background: transparent; outline: none;
    font-size: 14.5px; color: var(--c-deepest); font-family: 'Poppins', sans-serif;
}
.input-row input[type="text"]::placeholder { color: var(--c-sky); }

.btn-send {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; border-radius: 12px; border: none;
    font-size: 14px; font-weight: 600; cursor: pointer;
    transition: all var(--t); white-space: nowrap; font-family: 'Poppins', sans-serif;
}
.btn-send:hover {
    transform: translateY(-2px);
}

.btn-send-msg {
    background: linear-gradient(135deg, var(--c-mid) 0%, #0A9996 100%);
    color: #fff;
    box-shadow: 0 6px 18px rgba(12,171,168,0.3);
}
.btn-send-msg:hover {
    background: linear-gradient(135deg, #0A9996 0%, var(--c-dark) 100%);
    box-shadow: 0 8px 22px rgba(12,171,168,0.4);
}

.btn-send-rapport {
    background: linear-gradient(135deg, var(--c-sky) 0%, #528ba6 100%);
    color: #fff;
    box-shadow: 0 6px 18px rgba(109,165,192,0.3);
}
.btn-send-rapport:hover {
    background: linear-gradient(135deg, #528ba6 0%, var(--c-slate-blue) 100%);
    box-shadow: 0 8px 22px rgba(109,165,192,0.4);
}

/* Aucun message sélectionné */
.no-conv {
    flex: 1; display: flex; flex-direction: column; align-items: center;
    justify-content: center; gap: 14px; color: var(--c-slate-blue); text-align: center;
}
.no-conv-icon {
    width: 80px; height: 80px; background: rgba(12, 171, 168, 0.1);
    border-radius: 24px; display: flex; align-items: center; justify-content: center;
    font-size: 32px; color: var(--c-mid); border: 1.5px solid rgba(12, 171, 168, 0.2);
}
.no-conv h5 { font-weight: 700; font-size: 18px; color: var(--c-dark); margin: 0; }
.no-conv p { font-size: 14px; margin: 0; opacity: 0.8; }

/* Custom Scrollbars */
.conv-list::-webkit-scrollbar, .chat-msgs::-webkit-scrollbar { width: 5px; }
.conv-list::-webkit-scrollbar-thumb, .chat-msgs::-webkit-scrollbar-thumb { background: rgba(109,165,192,0.4); border-radius: 10px; }
* { scrollbar-width: thin; scrollbar-color: rgba(109,165,192,0.4) transparent; }
    </style>
</head>
<body>

<div class="msg-card">

    {{-- ============================================================
         SIDEBAR
    ============================================================ --}}
    <aside class="msg-sidebar">

        <div class="sidebar-hd">
            <div class="sidebar-hd-top">
                <div class="sidebar-hd-label">EtudServ</div>
                
                @if(session('utilisateur_role') === 'freelancer')
                    <a href="{{ url('/dashboard') }}" class="btn-dashboard-link">
                        <i class="fa-solid fa-chart-line"></i> Espace Freelancer
                    </a>
                @elseif(session('utilisateur_role') === 'client')
                    <a href="{{ url('/client-dashboard') }}" class="btn-dashboard-link">
                        <i class="fa-solid fa-briefcase"></i> Espace Client
                    </a>
                @endif
            </div>
            
            <div class="sidebar-hd-title">
                <i class="fa-solid fa-comments" style="font-size:18px; margin-right:10px; opacity:.75;"></i>Messagerie
            </div>
        </div>

        <div class="conv-list">
            @forelse($conversations as $conv)
                @php
                    $estClient       = ($conv->utilisateur_idutilisateur == $utilisateurConnecte->idutilisateur);
                    $interlocuteur   = $estClient ? ($conv->service->prestataire->utilisateur ?? null) : $conv->utilisateur;
                    $nomInterlocuteur = $interlocuteur ? ($interlocuteur->prenom . ' ' . $interlocuteur->nom) : 'Utilisateur';
                    $initiale         = mb_strtoupper(mb_substr($nomInterlocuteur, 0, 1));
                    $_avClasses       = ['av-purple','av-blue','av-green','av-rose'];
                    $avatarClass      = $_avClasses[abs(crc32($nomInterlocuteur)) % 4];
                @endphp
                <a href="{{ route('messagerie', ['id' => $conv->idconversion]) }}"
                   class="conv-item {{ (isset($conversationActive) && $conversationActive->idconversion == $conv->idconversion) ? 'active' : '' }}">
                    <div class="conv-av {{ $avatarClass }}">{{ $initiale }}</div>
                    <div class="conv-meta">
                        <div class="conv-name">{{ $nomInterlocuteur }}</div>
                        <div class="conv-role">{{ $estClient ? 'Prestataire' : 'Client' }}</div>
                    </div>
                </a>
            @empty
                <div class="conv-empty">
                    <i class="fa-regular fa-comment-dots"></i>
                    Aucune discussion pour le moment.
                </div>
            @endforelse
        </div>

    </aside>

    {{-- ============================================================
         CHAT AREA
    ============================================================ --}}
    <main class="msg-chat">

        @if(isset($conversationActive))
            @php
                $estClientActif      = ($conversationActive->utilisateur_idutilisateur == $utilisateurConnecte->idutilisateur);
                $estPrestataireActif = !$estClientActif;

                $hdInterlocuteur = $estClientActif
                    ? ($conversationActive->service->prestataire->utilisateur ?? null)
                    : $conversationActive->utilisateur;
                $hdNom      = $hdInterlocuteur ? ($hdInterlocuteur->prenom . ' ' . $hdInterlocuteur->nom) : 'Utilisateur';
                $hdInitiale = mb_strtoupper(mb_substr($hdNom, 0, 1));
                $_hdClasses  = ['av-purple','av-blue','av-green','av-rose'];
                $hdAvClass   = $_hdClasses[abs(crc32($hdNom)) % 4];
            @endphp

            @if($conversationActive)
                @php
                    $nomInterlocuteur = $interlocuteur ? ($interlocuteur->prenom . ' ' . $interlocuteur->nom) : 'Utilisateur';
                    
                    $enLigne = false;
                    if ($interlocuteur && $interlocuteur->derniere_activite) {
                        $derniereActivite = \Carbon\Carbon::parse($interlocuteur->derniere_activite);
                        $enLigne = $derniereActivite->greaterThanOrEqualTo(now()->subMinutes(5));
                    }
                @endphp

                <div class="chat-hd">
                    <div class="chat-hd-av av-purple">
                        {{ strtoupper(substr($nomInterlocuteur, 0, 1)) }}
                    </div>
                    <div class="chat-hd-info">
                        <div class="chat-hd-name">{{ $nomInterlocuteur }}</div>
                        <div class="chat-hd-status">
                            @if($enLigne)
                                <span class="status-dot" style="background: var(--c-green);"></span> En ligne
                            @else
                                <span class="status-dot" style="background: #94a3b8;"></span> 
                                <span style="color: #64748b;">
                                    @if($interlocuteur && $interlocuteur->derniere_activite)
                                        Dernière connexion : {{ \Carbon\Carbon::parse($interlocuteur->derniere_activite)->diffForHumans() }}
                                    @else
                                        Déconnecté
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Messages --}}
            <div class="chat-msgs" id="chatBox">
                @forelse($messages as $msg)
                    @php $monMessage = ($msg->utilisateur_idutilisateur == $utilisateurConnecte->idutilisateur); @endphp
                    <div class="bw {{ $monMessage ? 'sent' : 'recv' }}">
                        <div class="bubble {{ $monMessage ? 'sent' : 'recv' }} {{ $msg->type === 'rapport' ? 'is-rapport' : '' }}">
                            @if($msg->type === 'rapport')
                                <div><span class="rapport-badge">
                                    <i class="fa-solid fa-file-lines"></i> Rapport
                                </span></div>
                            @endif
                            <div>{{ $msg->contenu }}</div>
                        </div>
                        <span class="bubble-time">{{ \Carbon\Carbon::parse($msg->date_envoi)->format('H:i') }}</span>
                    </div>
                @empty
                    <div class="chat-empty">
                        <div class="chat-empty-icon"><i class="fa-regular fa-comment-dots"></i></div>
                        <span>Commencez la discussion !</span>
                    </div>
                @endforelse
            </div>

            {{-- ÉVALUATION (côté client uniquement) --}}
            @if($estClientActif)
                <div class="eval-wrap">
                    @if($conversationActive->statut === 'termine')
                        <div class="eval-done">
                            <i class="fa-solid fa-circle-check"></i>
                            Vous avez évalué ce prestataire. Projet terminé.
                        </div>
                    @else
                        <h6>Évaluer ce prestataire</h6>
                        <form action="{{ route('avis.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $conversationActive->idconversion }}">

                            <div class="star-rating">
                                <input type="radio" id="star5" name="note" value="5"><label for="star5">★</label>
                                <input type="radio" id="star4" name="note" value="4"><label for="star4">★</label>
                                <input type="radio" id="star3" name="note" value="3"><label for="star3">★</label>
                                <input type="radio" id="star2" name="note" value="2"><label for="star2">★</label>
                                <input type="radio" id="star1" name="note" value="1"><label for="star1">★</label>
                            </div>

                            <textarea name="commentaire" class="eval-textarea" rows="2"
                                      placeholder="Votre commentaire (optionnel)..."></textarea>

                            <button type="submit" class="btn-eval">
                                <i class="fa-solid fa-paper-plane" style="margin-right:6px;"></i>Envoyer mon évaluation
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Input bar --}}
            <div class="chat-input-bar">
                <form action="{{ route('messagerie.envoyer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{ $conversationActive->idconversion }}">
                    <div class="input-row">
                        <input type="text" name="contenu" placeholder="Écrivez votre message..." required autocomplete="off">
                        <button class="btn-send btn-send-msg" type="submit" name="type" value="texte">
                            <i class="fa-solid fa-paper-plane"></i> Envoyer
                        </button>
                        @if($estPrestataireActif)
                            <button class="btn-send btn-send-rapport" type="submit" name="type" value="rapport">
                                <i class="fa-solid fa-file-lines"></i> Rapport
                            </button>
                        @endif
                    </div>
                </form>
            </div>

        @else
            <div class="no-conv">
                <div class="no-conv-icon"><i class="fa-regular fa-comments"></i></div>
                <h5>Aucune conversation sélectionnée</h5>
                <p>Choisissez une discussion dans la liste.</p>
            </div>
        @endif

    </main>

</div>

<script>
    var chatBox = document.getElementById("chatBox");
    if (chatBox) { chatBox.scrollTop = chatBox.scrollHeight; }
</script>
</body>
</html>
