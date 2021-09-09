<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receive extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function receivePaymentBatchReceives()
    {
        return $this->hasMany(ReceivePaymentBatchReceive::class);
    }

    public function salesOrderBatchReceives()
    {
        return $this->hasMany(SalesOrderBatchReceive::class);
    }
}
