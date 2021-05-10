<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }
}
