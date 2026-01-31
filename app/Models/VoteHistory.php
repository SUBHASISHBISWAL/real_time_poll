<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteHistory extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    protected $table = 'vote_history';

    protected $fillable = [
        'poll_id',
        'option_id',
        'ip_address',
        'action',
        'previous_option_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function option()
    {
        return $this->belongsTo(PollOption::class, 'option_id');
    }
    
    public function previousOption()
    {
        return $this->belongsTo(PollOption::class, 'previous_option_id');
    }
}
