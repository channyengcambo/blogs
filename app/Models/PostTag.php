<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PostTag extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'tag_id'];
}
