<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('target', 511);
            $table->decimal('amount', 15,4);
            $table->timestamp('paid')->nullable()->default(null);
            $table->string('callback_url', 511)->nullable();
            $table->timestamp('callback_at')->nullable();
            $table->timestamp('expires_at');
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
        Schema::dropIfExists('payments');
    }
}
