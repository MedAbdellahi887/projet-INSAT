<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    // le nom du table
    protected $table = 'utilisateur';

    // specifier le cle primaire'idutilisateur'
    protected $primaryKey = 'idutilisateur';

    //Auto incrementation et le type du cle primaire
    public $incrementing = true;
    protected $keyType = 'int';

    //Desactivation des colonnes 'created_at' et 'updated_at' car elles ne sont pas dans la table et par default laravel dire que chaque table contient ces colonnes par default  
    public $timestamps = false;
    // liste des colonnes que Laravel a le droit de modifier automatiquement
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'telephone',
        'date_inscription',
        'adresse',
        'role',
        'photo',
        'est-active',
        'derniere_active'
    ];
    public function prestataire()
{
    return $this->hasOne(prestataire::class, 'utilisateur_idutilisateur', 'idutilisateur');
}

}

    
