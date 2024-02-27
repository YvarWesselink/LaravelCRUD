<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userInfo extends Model
{
    // defines the fillable rows. and what we expect
    protected $fillable = ['user_id','name','adress','age','tel','place','intro','socialFB','socialLI','socialIG','socialTW','socialLT', 'socialTE', 'notes','active', 'level'];
    
    // makes connection bewteen models
    public function userInfo()
    {
        return $this->belongsTo(User::class);
    }

    public function fetchUserInfo()
    {
        return $this->belongsTo(User::class);
    }
}
