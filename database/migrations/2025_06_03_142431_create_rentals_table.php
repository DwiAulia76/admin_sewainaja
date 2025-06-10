<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('penyewa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', [
                'menunggu pembayaran',
                'disewa',
                'selesai',
                'terlambat'
            ])->default('menunggu pembayaran');
            $table->string('condition_before')->nullable();
            $table->string('condition_after')->nullable();
            $table->text('damage_report')->nullable();
            $table->decimal('fine_amount', 10, 2)->nullable();
            $table->boolean('confirmed_by_admin')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rentals');
    }
};