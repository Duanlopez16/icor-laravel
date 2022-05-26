<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * City
 */
class City extends Model
{
    /**
     * table
     *
     * @var string
     */
    protected $table = 'city';

    use HasFactory;
}
