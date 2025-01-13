<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fournisseurs';

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'adresse',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }

    // Accessors pour la compatibilité avec l'anglais
    public function getNameAttribute()
    {
        return $this->nom;
    }

    public function getPhoneAttribute()
    {
        return $this->telephone;
    }

    public function getAddressAttribute()
    {
        return $this->adresse;
    }

    // Mutators pour la compatibilité avec l'anglais
    public function setNameAttribute($value)
    {
        $this->attributes['nom'] = $value;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['telephone'] = $value;
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['adresse'] = $value;
    }
} 