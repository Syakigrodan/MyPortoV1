<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->text('message');
            $table->string('avatar_path')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['is_pinned', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
