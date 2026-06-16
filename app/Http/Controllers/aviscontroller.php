<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avis;
use App\Models\Conversion;
use App\Models\prestataire;

class AvisController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'note'             => 'required|integer|min:1|max:5',
            'commentaire'      => 'nullable|string',
            'conversation_id'  => 'required|exists:conversion,idconversion',
        ]);

        $userId = session('utilisateur_id');
        if (!$userId) return redirect()->route('login');

        $conversation = Conversion::with('service.prestataire')->find($request->conversation_id);

        if (!$conversation || !$conversation->service || !$conversation->service->prestataire) {
            return redirect()->back()->withErrors(['error' => 'Conversation invalide.']);
        }

        $prestataireId = $conversation->service->prestataire->idprestataire;

        // Créer l'avis
        Avis::create([
            'note'                       => $request->note,
            'commentaire'                => $request->commentaire,
            'date_avis'                  => now(),
            'utilisateur_idutilisateur'  => $userId,
            'prestataire_idprestataire'  => $prestataireId,
            'conversion_idconversion'    => $conversation->idconversion,
        ]);

        // Marquer la conversation comme terminée
        $conversation->statut = 'termine';
        $conversation->save();

        // Recalculer la note moyenne du prestataire
        $prestataireModel = prestataire::find($prestataireId);
        $moyenne = Avis::where('prestataire_idprestataire', $prestataireId)->avg('note');
        $prestataireModel->note_moyenne = round($moyenne, 1);
        $prestataireModel->save();

        return redirect()->route('messagerie', ['id' => $conversation->idconversion])
            ->with('statut', 'Merci pour votre évaluation !');
    }
}