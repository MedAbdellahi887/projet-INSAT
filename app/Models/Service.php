<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    // Le nom de la table
    protected $table = 'service';

    // Le nom du clé primaire
    protected $primaryKey = 'idservice';

    // l'incrémentation automatique de idavis
    public $incrementing = true;
    protected $keyType = 'int';

    //désactivation des timestamps de Laravel car ils n'existent pas dans cette table
    public $timestamps = false;

    //  La liste des colonnes que Laravel peut modifier ou remplir
    protected $fillable = [
        'titre',
        'description',
        'prix_base',
        'actif',  
        'date_creation',
        'prestataire_idprestataire',     // C'est la cle etrangere liee a la prestataire
        'categorie_idcategorie' // C'est la cle etrangere liee a la catagorie
    ];
public function prestataire()
{
    return $this->belongsTo(prestataire::class, 'prestataire_idprestataire', 'idprestataire');
}

public function skill()
{
    return $this->belongsTo(skills::class, 'skills_idskills', 'idskills');
}

}
