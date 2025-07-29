<?php

namespace Aprog\database\seeders;

use Aprog\Models\ErrorCode;
use Exception;
use Illuminate\Database\Seeder;

class ErrorCodesSeeder extends Seeder
{
    public function run()
    {
        $defaultErrors = [
            'AP000' => 'Невідома помилка!',
            'AP001' => 'Неправильний формат запиту!',
            'AP002' => 'Дані не знайдено!',
            'AP003' => 'Валідація не пройдена!',
            'AP100' => 'Потрібна авторизація!',
            'AP101' => 'Недійсний токен!',
            'AP102' => 'Доступ заборонено!',
            'AP500' => 'Помилка на сервері!',
            'AP505' => 'Ділення на нуль!',
            'CL404' => 'Класу не існує!',
            'MT404' => 'Методу не існує!',
            'TP400' => 'Невірний тип виклику!',
        ];

        foreach ($defaultErrors as $code => $desc) {
            try {
                ErrorCode::updateOrCreate(['code' => $code], ['description' => $desc]);
            } catch (Exception $e) {
                blockExceptionError($e);
            }
        }
    }
}
