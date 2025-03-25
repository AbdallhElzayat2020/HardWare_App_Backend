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
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Change to unsignedBigInteger to match the `id` column type in `hospitals`
            $table->unsignedBigInteger('hospital_id');
            $table->foreign('hospital_id')
                ->references('id')->on('hospitals')
                ->onDelete('cascade'); // Optional: Add cascading delete

            // Change to unsignedBigInteger to match the `id` column type in `staff`
            $table->unsignedBigInteger('head_nurse_id')->nullable();
            $table->foreign('head_nurse_id')
                ->references('id')->on('staff')
                ->onDelete('set null'); // Optional: Set to null when the staff is deleted

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wards');
    }
};
