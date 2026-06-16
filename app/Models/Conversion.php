<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $table = 'conversion'; // Force l'utilisation de ta table SQL
    protected $primaryKey = 'idconversion';
    public $timestamps = false; // Pas de colonnes created_at/updated_at par défaut
    // La liste des colonnes que Laravel peut modifier ou remplir
    protected $fillable = [
        'date_creation',
    'service_idservice',
    'utilisateur_idutilisateur',
    'statut'
    ];

    // Le client qui a démarré la conversation
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_idutilisateur', 'idutilisateur');
    }

    // Le service concerné
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_idservice', 'idservice');
    }

    // Les messages de cette conversation
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversion_idconversion', 'idconversion');
    }
}

    