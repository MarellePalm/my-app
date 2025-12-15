<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;
use App\Traits\HasFormatedDate;

class Comment extends Model
{
    use HasFactory;
    use HasFormatedDate;

    protected $quarded= [];

    protected $appends = [
        'created_at_formatted',
        'updated_at_formatted',
    ];

    protected $fillable = [
        'user_id',
        'post_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
