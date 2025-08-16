<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('slides', function (Blueprint $t) {
      $t->id();
      $t->enum('position', ['main','slider'])->default('slider'); // hero vs carousel
      $t->string('title')->nullable();
      $t->string('subtitle')->nullable();
      $t->string('image_path');              // e.g. banners/hero.jpg (disk=public)
      $t->string('cta_label')->nullable();
      $t->string('cta_url')->nullable();
      $t->unsignedInteger('sort_order')->default(0);
      $t->timestamp('starts_at')->nullable();
      $t->timestamp('ends_at')->nullable();
      $t->boolean('is_active')->default(true);
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('slides'); }
};
