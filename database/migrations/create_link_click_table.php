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
        Schema::create('short_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('referer_host')->nullable();
            $table->integer('link_id')->unsigned();
            $table->timestamps();

            $table->index('ip');
            $table->index('referer_host');
            $table->index('link_id');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_link_clicks');
    }
};
