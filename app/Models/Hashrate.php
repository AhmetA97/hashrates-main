<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashrate extends Model
{
    use HasFactory;

    protected $fillable = ['worker_name', 'worker_id', 'hashrate', 'date', 'reject'];


    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'worker_id');
    }
}
