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
            'AP000' => 'Невідома помилка',
            'AP001' => 'Неправильний формат запиту',
            'AP002' => 'Дані не знайдено',
            'AP003' => 'Валідація не пройдена',
            'AP100' => 'Потрібна авторизація',
            'AP101' => 'Недійсний токен',
            'AP102' => 'Доступ заборонено',
            'AP500' => 'Помилка на сервері',
            'CK001' => 'Помилка індексу матеріалу на деталі (найчастіше індекс -1)',
            'CK002' => 'Помилка індексу крайки на деталі (найчастіше індекс -1)',
            'IK404' => 'Відсутній $projectArray["project"]["good"]',
            'IK064' => 'Не вдалося розшифрувати кодування base64',
            'IK055' => 'Serialize string не пройшла валідацію',
            'IK089' => 'Не вірна структура файлу kronas',
            'IK053' => 'Помилка парсингу вкладки фурнітури з Excel файлу',
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
