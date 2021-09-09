<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivePaymentBatchReceive extends Model
{
    use HasFactory;

    public function receive()
    {
        return $this->belongsTo(Receive::class);
    }
}
