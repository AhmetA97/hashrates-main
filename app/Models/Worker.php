<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = ['worker_name', 'worker_id'];


    public function hashrates()
    {
        return $this->hasMany(Hashrate::class, 'worker_id', 'worker_id');
    }
}
