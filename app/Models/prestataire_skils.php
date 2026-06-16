<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class prestataire_skils extends Model
{
    // Le nom de la table
    protected $table = 'prestataire_skils';

    // Le nom du clé primaire
    protected $primaryKey = 'id';

    // l'incrémentation automatique de idavis
    public $incrementing = true;
    protected $keyType = 'int';

    //désactivation des timestamps de Laravel car ils n'existent pas dans cette table
    public $timestamps = false;

    //  La liste des colonnes que Laravel peut modifier ou remplir
    protected $fillable = [
        ' skills_idskills',
        'prestataire_idprestataire'
         // C'est la cle etrangere liee a la skills
    ];
}
