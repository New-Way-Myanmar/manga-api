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
        Schema::create('mangas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('about')->nullable();
            $table->longText('imagePath')->nullable();
            $table->enum('status', ['0', '1'])->default('0')->comment('0: Ongoing, 1: Compleate');
            $table->string('author', '50')->default('Anonimas');
            $table->date('releaseDate')->nullable();
            $table->string('altName')->nullable();
            $table->foreignId('created_by')->nullable()->references('id')->on('admins')->cascadeOnDelete();
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mangas');
    }
};
