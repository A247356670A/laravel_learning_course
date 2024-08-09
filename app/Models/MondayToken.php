<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MondayToken extends Model
{
    use HasFactory;

    protected $table = 'monday_tokens';

    // 允许批量赋值的字段
    protected $fillable = [
        'access_token',
    ];

}
