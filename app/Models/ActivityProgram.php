<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'institute_id',
        'title',
        'description'
    ];
}
