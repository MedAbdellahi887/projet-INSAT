<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    protected $primaryKey = 'idmessage';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
    'contenu',
    'type',
    'fichier',
    'email',
    'destinataire',
    'expediteur',
    'date_envoi',
    'lu',
    'conversion_idconversion',
    'utilisateur_idutilisateur'
];
// L'utilisateur qui a envoyé le message
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_idutilisateur', 'idutilisateur');
    }
}