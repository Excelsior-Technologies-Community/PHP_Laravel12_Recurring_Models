<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('type')->default('today');
            $table->enum('priority', ['High', 'Medium', 'Low'])->default('Medium');
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('is_all_day')->default(false);
            $table->integer('recurring_interval')->nullable();
            $table->string('recurring_unit')->nullable(); // day, week, month, year
            $table->string('week_of_month')->nullable(); // first, second, third, fourth, last
            $table->string('day_of_week')->nullable(); // monday, tuesday, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};