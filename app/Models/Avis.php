<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';
    protected $primaryKey = 'idavis';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'note',
        'commentaire',
        'date_avis',
        'utilisateur_idutilisateur',
        'prestataire_idprestataire',
        'conversion_idconversion'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_idutilisateur', 'idutilisateur');
    }

    public function prestataire()
    {
        return $this->belongsTo(prestataire::class, 'prestataire_idprestataire', 'idprestataire');
    }

    public function conversion()
    {
        return $this->belongsTo(Conversion::class, 'conversion_idconversion', 'idconversion');
    }
}