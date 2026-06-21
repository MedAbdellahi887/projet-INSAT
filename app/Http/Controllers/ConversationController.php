<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversion;

class ConversationController extends Controller
{
    // Cette fonction reçoit les données du formulaire pour lancer une discussion
    public function démarrer(Request $request)
    {
        //  On prépare une nouvelle ligne dans la table 'conversation'
        $conversation = new Conversion();

        //  On remplit avec les données (Saisies ou automatiques)
        $conversation->note = $request->input('note'); // Si vous donnez une note de départ
        
        // On enregistre automatiquement la date et l'heure de création
        $conversation->date_creation = now(); 
        
        // On récupère les identifiants nécessaires (clés étrangères)
        $conversation->service_idservice = $request->input('service_id');
        $conversation->utilisateur_idutilisateur = $request->input('utilisateur_id');

        //  Le modèle traduit en SQL et insère automatiquement en base de données
        $conversation->save();

        //  On redirige l'utilisateur vers la page de discussion avec un message
        return "La conversation a été créée automatiquement avec l'ID numéro : " . $conversation->idconversation;
    }
}