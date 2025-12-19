<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'id_client';
    public $timestamps = true;

    protected $fillable = [
        'nom',
        'contact',
        'note',
    ];

    // Relation : Un client peut avoir plusieurs véhicules
    public function vehicules()
    {
        return $this->hasMany(Vehicule::class, 'client_id', 'id_client');
    }
}
