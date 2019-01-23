<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends BaseModel
{
    public $table = "Notifications";
    public $primaryKey = "NotificationId";
    
    public function user() {
        return $this->belongsTo('App\User');
    }
}
