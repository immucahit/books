<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'log';

    protected $fillable = [
        'user_id',
        'table_name',
        'action',
        'data'
    ];

    public $timestamps = false;

    protected $casts = [
        'data' => 'array'
    ];
}
