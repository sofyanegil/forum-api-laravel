<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCommentLike extends Model
{
    protected $fillable = [
        'user_id',
        'comment_id',
    ];
}
