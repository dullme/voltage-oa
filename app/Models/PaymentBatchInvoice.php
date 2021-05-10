<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentBatchInvoice extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
