<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public function players() {
        return $this->hasMany('App\Models\User');
    }
}
