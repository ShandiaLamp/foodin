<?php

namespace Shandialamp\Foodin\Models;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $table = 'admin_user_tokens';

    protected $fillable = ['token', 'user_id', 'scope', 'expire_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
