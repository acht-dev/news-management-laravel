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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->text("title");
            $table->longText("content");
            $table->smallInteger("status")->nullable();
            $table->integer("category_id")->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->integer("created_by")->unsigned()->nullable();
            $table->integer("updated_by")->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
