<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\prestataire_skils; // On importe le modèle (qui pointe sur prestataire_skils)

class pres_skilsController extends Controller
{
    // Cette fonction permet d'associer une compétence à un prestataire
    public function lierCompetence(Request $request)
    {
        //  On prépare une nouvelle ligne vide pour la table 'prestataire_skils' (une objet)
        $liaison = new prestataire_skils();

        //  On remplit avec les identifiants reçus (depuis un formulaire ou une liste)
        $liaison->skills_idskills = $request->input('skills_id');
        $liaison->prestataire_idprestataire = $request->input('prestataire_id');

        // 3. On sauvegarde dans la base de données
        $liaison->save();

        return "La compétence a bien été ajoutée au prestataire !";
    }
}