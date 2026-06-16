<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\prestataire; // Attention à vérifier la casse (p ou P) selon le nom exact de votre fichier de Modèle

class PrestataireController extends Controller
{
    // Cette fonction permet d'enregistrer le profil d'un nouveau prestataire
    public function devenirPrestataire(Request $request)
    {
        // 1. Validation de sécurité pour s'assurer que le type de prestataire est bien envoyé
        $request->validate([
            'type_prestataire' => 'required|string|max:255',
        ]);

        // 2. On prépare une nouvelle ligne vide dans la table 'prestataire'
        $prestataire = new Prestataire();

        // On remplit avec les données du formulaire ou des valeurs par défaut
        $prestataire->type_prestataire = $request->input('type_prestataire'); // ex: Développeur, Designer...
        
        // On définit des valeurs de départ automatiques pour un nouveau prestataire
        $prestataire->niveau = 'Debutant'; 
        $prestataire->note_moyenne = 0.0;     // Pas encore de note au début
        $prestataire->inscription_payee = 0;  // 0 = non payé par défaut (si c'est un abonnement/frais)

        // ================= CORRECTION SÉCURISÉE ICI =================
        // Au lieu de chercher dans le formulaire ($request), on prend l'ID de l'utilisateur connecté en session
        $idConnecte = session('utilisateur_id');

        // Sécurité supplémentaire : si la session s'est déconnectée entre temps
        if (!$idConnecte) {
            return redirect()->route('login')->withErrors(['code' => 'Votre session a expiré. Veuillez vous reconnecter.']);
        }

        $prestataire->utilisateur_idutilisateur = $idConnecte;
        // ============================================================

        // 3. On sauvegarde dans la base de données
        $prestataire->save();

        // 4. Redirection propre vers le dashboard avec un message flash de succès
        return redirect()->route('dashboard')
            ->with('success', 'Votre profil prestataire a été créé avec succès ! Bienvenue.');
    }
}