<?php

namespace App\Models;

use App\Models\Persyaratan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perizinan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perizinan'
    ];

    public function persyaratan()
    {
        return $this->hasMany(Persyaratan::class);
    }
}
