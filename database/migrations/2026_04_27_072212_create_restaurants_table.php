<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('color', 7)->default('#FF6B35');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Restaurants
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->json('opening_hours')->nullable(); // {"mon": {"open":"08:00","close":"22:00"}, ...}
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('min_order', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->integer('estimated_delivery_time')->default(30); // in minutes
            $table->boolean('is_open')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->json('tags')->nullable(); // ['halal', 'vegetarian', 'spicy']
            $table->string('status')->default('active'); // active, suspended, pending
            $table->timestamps();
            $table->softDeletes();
        });

        // Restaurant Categories (pivot)
        Schema::create('category_restaurant', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'restaurant_id']);
        });

        // Menu Groups / Sections
        Schema::create('menu_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Menus
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('discount_price', 12, 2)->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_spicy')->default(false);
            $table->boolean('is_halal')->default(true);
            $table->boolean('is_vegetarian')->default(false);
            $table->integer('calories')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_ordered')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Menu Variants/Options
        Schema::create('menu_option_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Ukuran, Level Pedas, Topping
            $table->boolean('is_required')->default(false);
            $table->boolean('is_multiple')->default(false);
            $table->integer('min_select')->default(0);
            $table->integer('max_select')->default(1);
            $table->timestamps();
        });

        Schema::create('menu_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_option_group_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Regular, Large, Extra Pedas
            $table->decimal('additional_price', 10, 2)->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_options');
        Schema::dropIfExists('menu_option_groups');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_groups');
        Schema::dropIfExists('category_restaurant');
        Schema::dropIfExists('restaurants');
        Schema::dropIfExists('categories');
    }
};