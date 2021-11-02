<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderNoCreate extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $fillable = [
        'no',
        'order_no',
        'author',
        'year'
    ];
}
