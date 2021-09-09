<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivePaymentBatch extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function receivePaymentBatchReceives()
    {
        return $this->hasMany(ReceivePaymentBatchReceive::class);
    }
}
