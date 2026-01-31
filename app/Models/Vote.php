<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'poll_id',
        'option_id',
        'ip_address',
        'voted_at',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function option()
    {
        return $this->belongsTo(PollOption::class, 'option_id');
    }
}
