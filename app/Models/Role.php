<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active', 'can_post_jobs'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
