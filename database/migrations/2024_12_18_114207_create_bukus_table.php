<?php

use App\Models\kategori;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(kategori::class);
            $table->text('judul')->nullable();
            $table->string('penulis')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('isbn')->nullable();
            $table->string('tahun')->nullable();
            $table->string('jumlah')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
