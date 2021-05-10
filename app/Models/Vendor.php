<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
