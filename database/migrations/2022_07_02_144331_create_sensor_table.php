<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->string('dui')->unique();
            $table->string('communication_channel')->nullable();
            $table->string('lightId')->nullable();
            $table->string('buttonId')->nullable();
            $table->tinyInteger('required_light_status')->nullable();
            $table->tinyInteger('required_button_status')->nullable();

            // Define foreign key for room_id
            $table->foreign('room_id')->references('id')->on('rooms')
                ->onDelete('cascade'); // Optional: Add cascading delete if needed

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensors');
    }
};
