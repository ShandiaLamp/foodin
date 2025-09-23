<?php

namespace Shandialamp\Foodin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shandialamp\Foodin\Database\Factories\UserTokenFactory;

class UserToken extends Model
{
    use HasFactory;

    protected $table = 'admin_user_tokens';

    protected $fillable = ['token', 'user_id', 'scope', 'expire_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return UserTokenFactory::new();
    }
}
