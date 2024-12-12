<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pengembalian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengembalians';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'pinjam_id', 'tanggal_kembali', 'denda'];

    public function pinjam()
    {
        return $this->belongsTo(pinjam::class, 'pinjam_id', 'id');
    }
}
