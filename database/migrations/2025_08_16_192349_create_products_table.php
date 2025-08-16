<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('products', function (Blueprint $t) {
      $t->id();

      // Relations
      $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
      $t->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

      // Main fields
      $t->string('name');
      $t->string('slug')->unique();
      $t->text('short_description')->nullable();
      $t->longText('description')->nullable();
      $t->string('sku')->unique()->nullable();

      // Pricing & inventory
      $t->decimal('price', 10, 2);
      $t->decimal('sale_price', 10, 2)->nullable();
      $t->unsignedInteger('stock_qty')->default(0);

      // Status for homepage sections
      $t->boolean('is_active')->default(true);
      $t->boolean('is_featured')->default(false); // "Special"
      $t->timestamp('published_at')->nullable()->index();

      $t->timestamps();
    });
  }

  public function down(): void {
    Schema::dropIfExists('products');
  }
};
