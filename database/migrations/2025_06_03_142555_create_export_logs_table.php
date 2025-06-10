<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('export_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->enum('export_type', ['penyewa', 'transaksi', 'barang']);
            $table->enum('format', ['PDF', 'Excel']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('export_logs');
    }
};