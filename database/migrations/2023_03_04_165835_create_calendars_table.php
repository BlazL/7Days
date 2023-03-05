<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('google_account_id');
            $table->string('google_id');
            $table->string('name');
            $table->string('color');
            $table->string('timezone');
            $table->boolean('import')->default(false);
            $table->timestamps();

            $table->foreign('google_account_id')
                ->references('id')->on('google_accounts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendars');
    }
};
