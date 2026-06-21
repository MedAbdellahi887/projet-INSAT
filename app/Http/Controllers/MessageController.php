<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversion; 
use App\Models\Message;
use App\Models\Utilisateur;
use Carbon\Carbon;

class MessageController extends Controller
{
    public function index($id = null)
{
    $utilisateur_id = session('utilisateur_id');
    if (!$utilisateur_id) return redirect()->route('login');

    // 1. Mettre à jour la dernière activité de l'utilisateur connecté
    $utilisateurConnecte = Utilisateur::find($utilisateur_id);
    if ($utilisateurConnecte) {
        $utilisateurConnecte->derniere_activite = now();
        $utilisateurConnecte->save();
    }

    // Chargement des conversations
    $conversations = Conversion::where('utilisateur_idutilisateur', $utilisateur_id)
        ->orWhereHas('service.prestataire', function ($query) use ($utilisateur_id) {
            $query->where('utilisateur_idutilisateur', $utilisateur_id);
        })
        ->with(['utilisateur', 'service.prestataire.utilisateur', 'messages'])
        ->get();

    $conversationActive = $id ? Conversion::with(['utilisateur', 'service.prestataire.utilisateur', 'messages'])->find($id) : null;

    // 🌟 AJOUT : Si une conversation est ouverte, on marque instantanément les messages de l'AUTRE comme LUS
    if ($conversationActive) {
        Message::where('conversion_idconversion', $id)
            ->where('utilisateur_idutilisateur', '!=', $utilisateur_id)
            ->where('lu', 0)
            ->update(['lu' => 1]);
    }

    $messages = $conversationActive ? $conversationActive->messages : collect();

    // 2. Identifier l'autre interlocuteur dans la conversation active
    $interlocuteur = null;
    if ($conversationActive) {
        if ($conversationActive->utilisateur_idutilisateur == $utilisateur_id) {
            $interlocuteur = $conversationActive->service->prestataire->utilisateur ?? null;
        } else {
            $interlocuteur = $conversationActive->utilisateur ?? null;
        }
    }

    return view('auth.messagerie', compact('conversations', 'conversationActive', 'messages', 'utilisateurConnecte', 'interlocuteur'));
}

    public function envoyer(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string',
            'conversation_id' => 'required|exists:conversion,idconversion',
            'type' => 'nullable|in:texte,rapport',
            'fichier' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $userId = session('utilisateur_id');
        if (!$userId) return redirect()->route('login');

        $message = new Message();
        $message->contenu = $request->input('contenu');
        $message->type = $request->input('type', 'texte');
        $message->date_envoi = now()->format('Y-m-d H:i:s');
        $message->lu = 0;
        $message->conversion_idconversion = $request->input('conversation_id');
        $message->utilisateur_idutilisateur = $userId;

        if ($request->hasFile('fichier')) {
            $chemin = $request->file('fichier')->store('rapports', 'public');
            $message->fichier = $chemin;
        }

        $user = Utilisateur::find($userId);
        if ($user) {
            $message->email = $user->email;
            $message->expediteur = trim($user->prenom . ' ' . $user->nom);
        }

        $message->save();

        return redirect()->route('messagerie', ['id' => $request->input('conversation_id')])
                         ->with('statut', 'Message envoyé !');
    }

    public function demarrer($service_id)
    {
        $userId = session('utilisateur_id');

        if (!$userId) return redirect()->route('login');

        $conversation = Conversion::where('utilisateur_idutilisateur', $userId)
            ->where('service_idservice', $service_id)
            ->first();

        if (!$conversation) {
            $conversation = Conversion::create([
                'utilisateur_idutilisateur' => $userId,
                'service_idservice'         => $service_id,
                'date_creation'             => now(),
            ]);
        }

        return redirect()->route('messagerie', ['id' => $conversation->idconversion]);
    }

}