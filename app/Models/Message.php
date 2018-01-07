<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $appends = [
        'date',
        'time',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'date_time',
    ];

    /**
     *
     * @param string $value
     *
     * @return string
     */
    public function getDateAttribute(): string {
        return $this->created_at->format('d/m/y');
    }


    /**
     * @param string $value
     *
     * @return string
     */
    public function getTimeAttribute(): string {
        return $this->created_at->format('H:i');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
