<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incomes extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'amount', 'description', 'data', 'user_id', ];
    public $timestamps = false;
}
