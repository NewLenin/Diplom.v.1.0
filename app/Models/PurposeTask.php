<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurposeTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'purpose_id',
        'name',
        'status'
    ];
}
