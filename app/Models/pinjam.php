<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class pinjam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pinjams';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    public function buku():belongsTo
    {
        return $this->belongsTo(buku::class);
    }
    public function user():belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
