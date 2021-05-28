<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingInvoice extends Model
{
    use HasFactory, DefaultDatetimeFormat;

    protected $casts = [
        'detail' => 'json',
    ];

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_users_id')->select('id', 'username', 'name', 'email');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

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

    public function getDetailAttribute($value)
    {
        return array_values(json_decode($value, true) ?: []);
    }

    public function setDetailAttribute($value)
    {
        $this->attributes['detail'] = json_encode(array_values($value));
    }
}
