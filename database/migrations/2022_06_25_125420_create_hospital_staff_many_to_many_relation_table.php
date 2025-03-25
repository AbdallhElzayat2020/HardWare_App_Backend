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
        Schema::create('hospital_group_staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on('staff')
                ->onDelete('cascade'); // Optional: Add cascading delete if needed

            // Change to unsignedBigInteger to match the `id` column type in `hospital_groups`
            $table->unsignedBigInteger('hospital_group_id');
            $table->foreign('hospital_group_id')->references('id')->on('hospital_groups')
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
        // Corrected table name in the down method
        Schema::dropIfExists('hospital_group_staff');
    }
};
