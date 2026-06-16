<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class prestataire extends Model
{
    // Le nom de la table
    protected $table = 'prestataire';

    // Le nom du clé primaire
    protected $primaryKey = 'idprestataire';

    // l'incrémentation automatique de idavis
    public $incrementing = true;
    protected $keyType = 'int';

    //désactivation des timestamps de Laravel car ils n'existent pas dans cette table
    public $timestamps = false;

    //  La liste des colonnes que Laravel peut modifier ou remplir
    protected $fillable = [
        'type_prestataire',
        'niveau',
        'note_moyenne',
        'inscription_payee',
        'utilisateur_idutilisateur', // C'est la cle etrangere liee a l'utilisateur
        'bio'
    ];
public function utilisateur()
{
    return $this->belongsTo(Utilisateur::class, 'utilisateur_idutilisateur', 'idutilisateur');
}

public function services()
{
    return $this->hasMany(Service::class, 'prestataire_idprestataire', 'idprestataire');
}

public function skills()
{
    return $this->belongsToMany(skills::class, 'prestataire_skils', 'prestataire_idprestataire', 'skills_idskills');
}
public function avis()
{
    return $this->hasMany(Avis::class, 'prestataire_idprestataire', 'idprestataire');
}
}