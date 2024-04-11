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
        Schema::create('log_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('log_sheet_no');
            $table->date('date');
            $table->foreignId('transporter_id')->constrained('transporters');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->string('destination');
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
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
        Schema::dropIfExists('log_sheets');
    }
};
