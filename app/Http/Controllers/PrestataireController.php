<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\prestataire; // Attention à vérifier la casse (p ou P) selon le nom exact de votre fichier de Modèle

class PrestataireController extends Controller
{
    // Cette fonction permet d'enregistrer le profil d'un nouveau prestataire
    public function devenirPrestataire(Request $request)
    {
        $request->validate([
            'type_prestataire' => 'required|string|max:255',
        ]);

        $idConnecte = session('utilisateur_id');

        if (!$idConnecte) {
            return redirect()->route('login')->withErrors(['code' => 'Votre session a expiré. Veuillez vous reconnecter.']);
        }

        // On cherche d'abord si un profil prestataire existe déjà (créé automatiquement à l'inscription)
        $prestataire = Prestataire::where('utilisateur_idutilisateur', $idConnecte)->first();

        if ($prestataire) {
            // Profil déjà existant → on le MET À JOUR (pas de doublon)
            $prestataire->type_prestataire = $request->input('type_prestataire');
            $prestataire->niveau           = $request->input('niveau', $prestataire->niveau ?? 'junior');
            $prestataire->bio              = $request->input('bio', $prestataire->bio);
            $prestataire->save();
        } else {
            // Pas encore de profil → on en crée un nouveau
            $prestataire = new Prestataire();
            $prestataire->type_prestataire          = $request->input('type_prestataire');
            $prestataire->niveau                    = $request->input('niveau', 'junior');
            $prestataire->note_moyenne              = 5.0;
            $prestataire->inscription_payee         = 0;
            $prestataire->utilisateur_idutilisateur = $idConnecte;
            $prestataire->bio                       = $request->input('bio');
            $prestataire->save();
        }

        // Créer ou mettre à jour le service avec le VRAI prix entré par l'utilisateur
        $premierService = \App\Models\Service::where('prestataire_idprestataire', $prestataire->idprestataire)->first();

        if ($premierService) {
            // Service existe → on le met à jour avec le vrai prix
            if ($request->filled('titre')) $premierService->titre = $request->input('titre');
            if ($request->filled('description')) $premierService->description = $request->input('description');
            if ($request->filled('prix_base')) $premierService->prix_base = $request->input('prix_base');
            $premierService->save();
        } elseif ($request->filled('titre')) {
            // Pas encore de service → on en crée un avec le vrai prix
            \App\Models\Service::create([
                'titre'                     => $request->input('titre'),
                'description'               => $request->input('description', ''),
                'prix_base'                 => $request->input('prix_base', 0),
                'actif'                     => 1,
                'date_creation'             => now()->format('Y-m-d'),
                'prestataire_idprestataire' => $prestataire->idprestataire,
                'niveau_vendeur'            => $prestataire->niveau ?? 'junior',
                'skills_idskills'           => $request->input('skills_idskills', 1),
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Votre profil prestataire a été enregistré avec succès ! Bienvenue.');
    }

    // ================= AFFICHER LE FORMULAIRE DE MODIFICATION =================
    // Affiche le formulaire pré-rempli avec les infos déjà existantes du prestataire connecté
    public function modifierProfilForm()
    {
        $idConnecte = session('utilisateur_id');

        if (!$idConnecte) {
            return redirect()->route('login')->withErrors(['code' => 'Votre session a expiré. Veuillez vous reconnecter.']);
        }

        // On récupère le profil prestataire existant de l'utilisateur connecté
        $prestataireProfil = Prestataire::where('utilisateur_idutilisateur', $idConnecte)->first();

        if (!$prestataireProfil) {
            // S'il n'a pas encore de profil prestataire, on le renvoie vers le formulaire de création
            return redirect()->route('auth.Profile1');
        }

        // On récupère aussi son premier service (s'il existe) pour pré-remplir la section "service"
        $premierService = \App\Models\Service::where('prestataire_idprestataire', $prestataireProfil->idprestataire)->first();

        return view('auth.Profile1Edit', compact('prestataireProfil', 'premierService'));
    }

    // ================= TRAITER LA MODIFICATION =================
    public function modifierProfil(Request $request)
    {
        $idConnecte = session('utilisateur_id');

        if (!$idConnecte) {
            return redirect()->route('login')->withErrors(['code' => 'Votre session a expiré. Veuillez vous reconnecter.']);
        }

        $prestataireProfil = Prestataire::where('utilisateur_idutilisateur', $idConnecte)->first();

        if (!$prestataireProfil) {
            return redirect()->route('auth.Profile1');
        }

        // Validation des champs envoyés par le formulaire
        $request->validate([
            'type_prestataire' => 'required|string|max:255',
            'niveau'            => 'required|string|max:45',
            'bio'               => 'nullable|string',
        ]);

        // Mise à jour des informations du profil (sur la ligne existante, pas une nouvelle ligne)
        $prestataireProfil->type_prestataire = $request->input('type_prestataire');
        $prestataireProfil->niveau           = $request->input('niveau');
        $prestataireProfil->bio              = $request->input('bio');
        $prestataireProfil->save();

        // Si un service existe déjà, on met aussi à jour ses informations
        $premierService = \App\Models\Service::where('prestataire_idprestataire', $prestataireProfil->idprestataire)->first();

        if ($premierService) {
            $premierService->titre        = $request->input('titre', $premierService->titre);
            $premierService->description  = $request->input('description', $premierService->description);
            $premierService->prix_base    = $request->input('prix_base', $premierService->prix_base);
            if ($request->filled('skills_idskills')) {
                $premierService->skills_idskills = $request->input('skills_idskills');
            }
            $premierService->save();
        }

        return redirect()->route('dashboard')
            ->with('success', 'Votre profil a été mis à jour avec succès !');
    }
}