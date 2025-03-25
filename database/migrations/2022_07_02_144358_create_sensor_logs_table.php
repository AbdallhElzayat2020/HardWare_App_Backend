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
        Schema::create('sensor_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sensor_id');
            $table->double('fall');
            $table->double('stand');
            $table->double('unoccuipied');
            $table->tinyInteger('light_status');
            $table->tinyInteger('button_status');
            $table->double('motion')->nullable();

            // Define foreign key for sensor_id
            $table->foreign('sensor_id')->references('id')->on('sensors')
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
        Schema::dropIfExists('sensor_logs');
    }
};
