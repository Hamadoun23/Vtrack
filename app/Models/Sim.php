<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sim extends Model
{
    use HasFactory;

    protected $table = 'sims';
    protected $primaryKey = 'id_sim';
    public $timestamps = true;

    protected $fillable = [
        'iccid',
        'last5',
        'numero',
        'operateur',
        'statut',
        'raison_blocage',
    ];

    // Relation : Une SIM peut être associée à un véhicule
    public function vehicule()
    {
        return $this->hasOne(Vehicule::class, 'sim_id', 'id_sim');
    }
}
