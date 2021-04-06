<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    public function setFileAttribute($file)
    {
        if (is_array($file)) {
            $this->attributes['file'] = json_encode($file);
        }
    }

    public function getFileAttribute($file)
    {
        return json_decode($file, true);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
