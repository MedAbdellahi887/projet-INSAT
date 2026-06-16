<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class skills extends Model
{
    // Le nom de la table
    protected $table = 'skills';

    // Le nom du clé primaire
    protected $primaryKey = 'idskills';

    // l'incrémentation automatique de idavis
    public $incrementing = true;
    protected $keyType = 'int';

    //désactivation des timestamps de Laravel car ils n'existent pas dans cette table
    public $timestamps = false;

    //  La liste des colonnes que Laravel peut modifier ou remplir
    protected $fillable = [
        'nom',
        'description'
    ];
}
