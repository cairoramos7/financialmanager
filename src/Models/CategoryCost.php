<?php

namespace CROFin\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryCost extends Model
{
    /* Mass Assignment */
    protected $fillable = [
        'name',
        'user_id'
    ];
}