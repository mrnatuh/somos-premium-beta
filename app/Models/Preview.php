<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preview extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cc',
        'week_ref',
        'month_ref',
        'invoicing',
        'mp',
        'mo',
        'gd',
        'rou',
        'variation',
        'status'
    ];
}
