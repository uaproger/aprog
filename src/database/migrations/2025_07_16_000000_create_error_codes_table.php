<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * --- Слава Україні 🇺🇦 ---
 *
 * --------------------------------------------------------------------------
 *                 Міграція для створення таблиці `error_codes`
 * --------------------------------------------------------------------------
 *
 * Copyright (c) 2025 AlexProger.
 */
return new class extends Migration {
    const TABLE = \Aprog\Enum\TableNameEnum::ERROR_CODES;

    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->string('code')->primary(); # 'AP001', 'US003' тощо
            $table->string('description');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => \Aprog\database\seeders\ErrorCodesSeeder::class
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
