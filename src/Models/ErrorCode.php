<?php

namespace Aprog\Models;

use Aprog\Enum\TableNameEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * class ErrorCode
 *
 * --------------------------------------------------------------------------
 *                              Модель ErrorCode
 * --------------------------------------------------------------------------
 *
 * @property string code
 * @property string description
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * Copyright (c) 2025 AlexProger.
 * @method static updateOrCreate(array $array, string[] $array1)
 */
class ErrorCode extends Model
{
    use HasFactory;

    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $fillable = [
        'code',
        'description',
    ];
    protected $table = TableNameEnum::ERROR_CODES;
}
