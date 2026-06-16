<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\prestataire;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('actif', 1)->get();
        return view('Projet', compact('services'));
    }

    public function stocker(Request $request)
    {
        $request->validate([
            'titre'       => 'required|string|max:255',
            'description' => 'required|string',
            'prix_base'   => 'required|numeric|min:0',
        ]);

        $utilisateurId = session('utilisateur_id');

        if (!$utilisateurId) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Vous devez être connecté.']);
        }

        $prestataireProfil = prestataire::where('utilisateur_idutilisateur', $utilisateurId)->first();

        if (!$prestataireProfil) {
            return redirect()->back()
                ->withErrors(['error' => 'Vous devez d\'abord créer votre profil prestataire.']);
        }

        $service = new Service();
        $service->titre = $request->input('titre');
        $service->description = $request->input('description');
        $service->prix_base = $request->input('prix_base');
        $service->niveau_vendeur = $prestataireProfil->niveau ?? 'basic';
        $service->actif = 1;
        $service->date_creation = now()->format('Y-m-d');
        $service->prestataire_idprestataire = $prestataireProfil->idprestataire;

        $service->save();

        return redirect()->route('profile')->with('success', 'Votre service a été publié avec succès !');
    }
}