<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'job_title',
        'bio',
        'industry',
        'cv_resume',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
