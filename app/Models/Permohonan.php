<?php

namespace App\Models;

use App\Models\Perizinan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'perizinan_id',
        'nama_pemohon',
        'berkas'
    ];

    protected $casts = [
        'berkas' => 'json'
    ];

    public function perizinan()
    {
        return $this->belongsTo(Perizinan::class);
    }
}
