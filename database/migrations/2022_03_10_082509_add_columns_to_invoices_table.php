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
            $table->foreignId('log_sheet_id')->constrained('log_sheets');
            $table->string('invoice_no');
            $table->string('date');
            $table->foreignId('client_id')->constrained('clients');
            $table->string('gross_weight');
            $table->foreignId('transporter_id')->constrained('transporters');
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->string('destination');
            $table->string('no_of_packs');
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
            //
        });
    }
};
