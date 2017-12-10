<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(){
        return $this->belongsToMany(User::class)->withPivot('read');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(){
        return $this->hasMany(Message::class);
    }

    /**
     * @param User $user
     * @return int
     */
    public function markAsRead(User $user){
        return $this->users()->updateExistingPivot($user->id,['read' => true]);
    }

    /**
     * @param User $user
     * @return int
     */
    public function markAsUnread(User $user){
        return $this->users()->updateExistingPivot($user->id,['read' => false]);
    }
}
