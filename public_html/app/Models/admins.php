<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admins extends Model
{
    // defines the fillable rows.
    protected $fillable = ['user_id','name','level'];

    // makes connection bewteen models
    public function admins()
    {
        return $this->belongsTo(User::class);
    }
    public function fetchAdmin()
    {
        return $this->belongsTo(User::class);
    }
}
