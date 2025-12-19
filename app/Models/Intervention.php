<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    use HasFactory;

    protected $table = 'interventions';
    protected $primaryKey = 'id_intervention';
    public $timestamps = true;

    protected $fillable = [
        'vehicule_id',
        'description',
        'date_intervention',
    ];

    protected $casts = [
        'date_intervention' => 'date',
    ];

    // Relation : Une intervention appartient à un véhicule
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class, 'vehicule_id', 'id_vehicule');
    }
}
