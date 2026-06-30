<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drive_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('folder_id')->nullable()->constrained('folders')->nullOnDelete();
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->string('extension')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->boolean('is_favorite')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'folder_id']);
            $table->index(['user_id', 'is_favorite']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drive_files');
    }
};
