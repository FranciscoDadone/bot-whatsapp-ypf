<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use App\Models\Message;
use Laravel\Sanctum\HasApiTokens;

class Ticket extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'from',
        'assigned_to',
        'status',
        'notes',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function from(): HasOne
    {
        return $this->hasOne(PhoneNumber::class, 'id', 'from');
    }

    public function messages()
    {
        return Message::where([['ticket_id', $this->id]])->get();
    }

    public function getAssigned() {
        if ($this->assigned_to == null) return null;
        return User::where([['id', $this->assigned_to]]);
    }
}
