<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $table = 'vehicules';
    protected $primaryKey = 'id_vehicule';
    public $timestamps = true;

    protected $fillable = [
        'immatriculation',
        'marque_modele',
        'client_id',
        'sim_id',
        'statut',
        'raison_suspension',
    ];

    // Relation : Un véhicule appartient à un client
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id_client');
    }

    // Relation : Un véhicule a une SIM (peut être null)
    public function sim()
    {
        return $this->belongsTo(Sim::class, 'sim_id', 'id_sim');
    }

    // Relation : Un véhicule peut avoir plusieurs interventions
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'vehicule_id', 'id_vehicule');
    }
}
