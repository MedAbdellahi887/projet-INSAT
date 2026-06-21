<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service; // On importe le modèle Service tout neuf

class ServiceController extends Controller
{
    //  Pour afficher tous les services sur le site (Page d'accueil / Catalogue)
    public function index()
    {
        // On récupère uniquement les services actifs
        $services = Service::where('actif', 1)->get(); 
        
        // On envoie la liste à votre vue HTML (Projet.blade.php)
        return view('Projet', compact('services'));
    }

    //  Pour enregistrer un nouveau service depuis un formulaire
    public function stocker(Request $request)
    {
        $service = new Service();

        // Remplissage avec les données reçues du formulaire
        $service->titre = $request->input('titre');
        $service->description = $request->input('description');
        $service->prix_base = $request->input('prix_base');
        
        // Données générées automatiquement
        $service->actif = 1; // 1 = Le service est en ligne directement
        $service->date_creation = now()->format('Y-m-d'); // Date du jour

        // Clés étrangères pour lier le service au vendeur et à la catégorie
        $service->vendeur_idvendeur = $request->input('vendeur_id');
        $service->categorie_idcategorie = $request->input('categorie_id');

        $service->save();

        return redirect()->back()->with('succes', 'Votre service a été publié avec succès !');
    }
}