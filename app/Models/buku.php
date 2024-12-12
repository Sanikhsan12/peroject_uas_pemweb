<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class buku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bukus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'kategori_id',
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'isbn',
        'jumlah',
    ];

    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'kategori_id', 'id');
    }
}
