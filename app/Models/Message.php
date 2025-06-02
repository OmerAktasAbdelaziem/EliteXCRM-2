<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'text',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function getFormattedDateAttribute()
    {
        $date = Carbon::parse($this->created_at);
        $now  = Carbon::now();

        // if the message was sent today, display only the time
        if ($date->isToday()) {
            return $date->format('h:i A');
        }

        // if the message was sent this week, display the day of the week
        if ($date->isCurrentWeek()) {
            return $date->format('l');
        }

        // otherwise, display the date without the time
        return $date->format('d/m/Y');
    }
}
