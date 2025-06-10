<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('item_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['tersedia', 'disewa']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('item_availability');
    }
};