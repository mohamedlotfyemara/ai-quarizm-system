<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'code', 'title', 'description', 'customer_id', 'priority', 'status',
        'assigned_team', 'technician_id', 'customer_confirmed', 'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
        'customer_confirmed' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function report()
    {
        return $this->hasOne(ServiceReport::class);
    }

    public static function generateCode(): string
    {
        $last = static::orderByDesc('id')->first();
        $next = $last ? ((int) substr($last->code, 4)) + 1 : 1001;
        return 'TCK-' . $next;
    }
}
