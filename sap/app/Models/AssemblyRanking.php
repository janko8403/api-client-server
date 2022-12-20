<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssemblyRanking extends Model
{
    protected $table = 'assemblyOrderRankings';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'position',
        'orderId',
        'userId'
    ];

    public $timestamps = false;
}
