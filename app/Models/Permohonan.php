<?php

namespace App\Models;

use App\Models\User;
use App\Models\Perizinan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'perizinan_id',
        'user_id',
        'nama_pemohon',
        'berkas',
        'formulir',
        'status',
    ];

    protected $casts = [
        'berkas' => 'json',
        'formulir' => 'json',
    ];

    public function perizinan()
    {
        return $this->belongsTo(Perizinan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
