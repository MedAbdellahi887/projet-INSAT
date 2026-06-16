<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\prestataire;
use App\Models\Conversion;
use App\Models\Avis;

class ProfilController extends Controller
{
    public function show($id)
    {
        if (!session('utilisateur_id')) {
            return redirect()->route('login');
        }

        $utilisateur = Utilisateur::find($id);

        if (!$utilisateur) {
            return redirect()->route('home')->withErrors(['error' => 'Utilisateur introuvable.']);
        }

        $prestataireProfil = $utilisateur->role === 'freelancer'
            ? prestataire::where('utilisateur_idutilisateur', $id)->first()
            : null;
        $projetsRealises = 0;
        $avisRecus = collect();

        if ($prestataireProfil) {
            $serviceIds = $prestataireProfil->services->pluck('idservice');

            $projetsRealises = Conversion::whereIn('service_idservice', $serviceIds)
                ->where('statut', 'termine')
                ->count();

            $avisRecus = Avis::where('prestataire_idprestataire', $prestataireProfil->idprestataire)
                ->with('utilisateur')
                ->orderBy('idavis', 'desc')
                ->get();
        }

        return view('auth.Profile', compact('utilisateur', 'prestataireProfil', 'projetsRealises', 'avisRecus'));
    }
}