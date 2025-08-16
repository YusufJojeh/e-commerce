<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('settings', function (Blueprint $t) {
      $t->id();
      $t->string('key')->unique();   // e.g. site.name, site.logo_light, home.limits
      $t->text('value')->nullable(); // string or JSON
      $t->string('group')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('settings'); }
};
