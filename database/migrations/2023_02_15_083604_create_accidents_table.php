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
        Schema::create('accidents', function (Blueprint $table) {
            $table->id();
            $table->string('quadkey', 24)
                ->collation("C") // use COLLATE "C" for quadkey
                ->index();
            $table->string('lonlat1', 12)->nullable()->index();
            $table->decimal('latitude', 9, 6)->nullable()->index();
            $table->decimal('longitude', 9, 6)->nullable()->index();
            $table->unsignedTinyInteger('severity_id')->nullable()->index();
            $table->unsignedSmallInteger('year')->index();
            $table->dateTime('datetime')->index();
            $table->foreignId('region_id')->index();
            $table->string('subregion')->nullable()->index();
            $table->string('category')->index();

            // statistics and charts columns
            $table->unsignedInteger('dead_count')->nullable();
            $table->unsignedInteger('injured_count')->nullable();
            $table->unsignedInteger('participants_count')->nullable();
            $table->string('light_conditions')->nullable();

            // group by columns
            $table->string('year-month',8)->index();
            $table->unsignedTinyInteger('weekday')->index();
            $table->unsignedTinyInteger('hour')->index();

            $table->jsonb('info')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accidents');
    }
};
