<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id(); // primaire sleutel van het mediarecord

            $table->morphs('mediable'); // polymorfe relatie (mediable_id + mediable_type)

            $table->string('disk')->default('public'); // storage disk

            $table->string('file_name'); // naam van het bestand
            $table->string('file_path'); // pad naar het bestand

            $table->string('mime_type')->nullable(); // bv image/jpeg
            $table->unsignedBigInteger('file_size')->nullable(); // grootte in bytes

            $table->string('alt_text')->nullable(); // alt tekst (SEO)
            $table->text('caption')->nullable(); // onderschrift

            $table->unsignedInteger('sort_order')->default(0); // volgorde
            $table->boolean('is_featured')->default(false); // featured image

            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
