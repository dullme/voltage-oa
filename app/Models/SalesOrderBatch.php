<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderBatch extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function salesOrderBatchReceives()
    {
        return $this->hasMany(SalesOrderBatchReceive::class);
    }
}
