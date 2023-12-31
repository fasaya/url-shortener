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
        Schema::create('short_links', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('short_url');
            $table->longText('long_url');
            $table->integer('clicks')->default(0);
            $table->boolean('is_disabled')->default(0);
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('creator_ip')->nullable();
            $table->softDeletes();
            $table->string('deleted_by')->nullable();
            $table->string('deleter_ip')->nullable();

            $table->index('slug');
            $table->index('short_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
