<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerData extends Model
{
    protected $table = 'customerdata';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customerId',
        'name',
        'cityDicId',
        'streetName',
        'zipCode',
        'isActive'
    ];

    public $timestamps = false;
}
