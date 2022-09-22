<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * @return belongsTo Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return belongsTo User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
