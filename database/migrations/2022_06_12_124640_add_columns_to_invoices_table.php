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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('transporter_id')->nullable()->constrained('transporters');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');
            $table->string('destination')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained('drivers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('transporter_id');
            $table->dropColumn('vehicle_id');
            $table->dropColumn('destination');
            $table->dropColumn('driver_id');
        });
    }
};
