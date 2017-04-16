<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public function users() {
        return $this->belongsToMany('App\Models\User')
            ->withPivot('role');
    }
}
