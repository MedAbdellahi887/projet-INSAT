<!DOCTYPE html>
<html lang="{{ session('lang', 'fr') }}" dir="{{ session('lang', 'fr') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EtudServ – Messagerie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap');

        :root {
            --blue:   #52D6F4;
            --blue2:  #39C6E9;
            --dark:   #1B2559;
            --purple: #7B61FF;
            --green:  #45C486;
            --orange: #F59E0B;
            --bg:     #F4F7FB;
            --white:  #ffffff;
        }

        body { background-color: var(--bg); font-family: 'Poppins', sans-serif; color: var(--dark); }
        .messaging-container { height: 80vh; background: var(--white); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(27, 37, 89, 0.05); }
        .sidebar { border-right: 1px solid #E9EDF7; height: 100%; overflow-y: auto; background: var(--white); }
        .chat-area { height: 100%; display: flex; flex-direction: column; background: #FAFCFF; }
        .conversation-item { cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid #F4F7FB; }
        .conversation-item.active { background-color: #EBF3FF; border-left: 4px solid var(--purple); }
        .messages-box { flex: 1; overflow-y: auto; padding: 24px; }
        .msg { max-width: 65%; padding: 12px 18px; border-radius: 16px; margin-bottom: 14px; font-size: 0.95rem; line-height: 1.5; }
        .msg-sent { background-color: var(--purple); color: white; margin-left: auto; border-bottom-right-radius: 2px; }
        .msg-received { background-color: var(--white); color: var(--dark); margin-right: auto; border-bottom-left-radius: 2px; border: 1px solid #E9EDF7; }
        .msg-rapport { border: 2px dashed var(--green) !important; }
        .rapport-badge {
            display: inline-block;
            background: var(--green);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 8px;
            margin-bottom: 6px;
        }
        .msg-time { font-size: 0.75rem; opacity: 0.7; display: block; margin-top: 6px; text-align: right; }
        .btn-send-message { background-color: var(--purple); color: white; border: none; padding: 10px 24px; border-radius: 8px; }
        .btn-rapport { background-color: var(--green); color: white; border: none; padding: 10px 20px; border-radius: 8px; }

        /* ===== Évaluation (étoiles) ===== */
        .eval-box {
            border-top: 1px solid #E9EDF7;
            padding: 16px 24px;
            background: #FAFCFF;
        }
        .eval-box h6 { font-weight: 700; margin-bottom: 10px; font-size: 14px; }
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            gap: 4px;
            margin-bottom: 10px;
            width: fit-content;
        }
        .star-rating input { display: none; }
        .star-rating label {
            font-size: 26px;
            color: #D1D5DB;
            cursor: pointer;
            transition: color 0.15s;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: var(--orange);
        }
        .eval-done {
            color: var(--green);
            font-weight: 600;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="row g-0 messaging-container">

        <div class="col-md-4 sidebar">
            <div class="list-group list-group-flush">
                @forelse($conversations as $conv)
                    @php
                        $estClient = ($conv->utilisateur_idutilisateur == $utilisateurConnecte->idutilisateur);
                        $interlocuteur = $estClient ? ($conv->service->prestataire->utilisateur ?? null) : $conv->utilisateur;
                        $nomInterlocuteur = $interlocuteur ? ($interlocuteur->prenom . ' ' . $interlocuteur->nom) : 'Utilisateur';
                    @endphp
                    <a href="{{ route('messagerie', ['id' => $conv->idconversion]) }}"
                       class="list-group-item list-group-item-action conversation-item p-3 {{ (isset($conversationActive) && $conversationActive->idconversion == $conv->idconversion) ? 'active' : '' }}">
                        <span class="fw-semibold">{{ $nomInterlocuteur }}</span>
                    </a>
                @empty
                    <p class="p-3">Aucune discussion.</p>
                @endforelse
            </div>
        </div>

        <div class="col-md-8 chat-area">
            @if(isset($conversationActive))
                @php
                    $estClientActif = ($conversationActive->utilisateur_idutilisateur == $utilisateurConnecte->idutilisateur);
                    $estPrestataireActif = !$estClientActif;
                @endphp

                <div class="messages-box" id="chatBox">
                    @forelse($messages as $msg)
                        @php $monMessage = ($msg->utilisateur_idutilisateur == $utilisateurConnecte->idutilisateur); @endphp
                        <div class="msg {{ $monMessage ? 'msg-sent' : 'msg-received' }} {{ $msg->type === 'rapport' ? 'msg-rapport' : '' }}">
                            @if($msg->type === 'rapport')
                                <span class="rapport-badge"><i class="fa-solid fa-file-lines"></i> Rapport</span><br>
                            @endif
                            <div>{{ $msg->contenu }}</div>
                            @if($msg->fichier)
                                <a href="{{ asset('storage/' . $msg->fichier) }}" target="_blank" style="display:inline-block; margin-top:6px; font-size:13px; color:inherit; text-decoration:underline;">
                                    <i class="fa-solid fa-paperclip"></i> Voir le fichier joint
                                </a>
                            @endif
                            <span class="msg-time">{{ \Carbon\Carbon::parse($msg->date_envoi)->format('H:i') }}</span>
                        </div>
                    @empty
                        <p class="text-center mt-5">Commencez la discussion !</p>
                    @endforelse
                </div>

                {{-- ÉVALUATION (côté client uniquement) --}}
                @if($estClientActif)
                    <div class="eval-box">
                        @if($conversationActive->statut === 'termine')
                            <span class="eval-done"><i class="fa-solid fa-circle-check"></i> Vous avez évalué ce prestataire. Projet terminé.</span>
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

                                <div class="mb-2">
                                    <textarea name="commentaire" class="form-control" rows="2" placeholder="Votre commentaire (optionnel)..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-send-message">Envoyer mon évaluation</button>
                            </form>
                        @endif
                    </div>
                @endif

                <div class="p-3 bg-white border-top">
                    <form action="{{ route('messagerie.envoyer') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $conversationActive->idconversion }}">
                        <div class="input-group mb-2">
                            <input type="text" name="contenu" class="form-control border-0 bg-light" placeholder="Écrivez votre message..." required>
                            <button class="btn btn-send-message" type="submit" name="type" value="texte">Envoyer</button>
                            @if($estPrestataireActif)
                                <button class="btn btn-rapport" type="submit" name="type" value="rapport">
                                    <i class="fa-solid fa-file-lines"></i> Envoyer comme rapport
                                </button>
                            @endif
                        </div>
                        @if($estPrestataireActif)
                            <input type="file" name="fichier" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Fichier optionnel pour le rapport (PDF ou image, max 20 Mo)</small>
                        @endif
                    </form>
                </div>
            @else
                <div class="m-auto text-center">Sélectionnez une discussion.</div>
            @endif
        </div>
    </div>
</div>

<script>
    var chatBox = document.getElementById("chatBox");
    if(chatBox) { chatBox.scrollTop = chatBox.scrollHeight; }
</script>
</body>
</html>