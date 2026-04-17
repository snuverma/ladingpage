<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'automation_id',
        'recipient_id',
        'platform',
        'message_content',
        'buttons',
        'type',
        'status',
        'sent_at',
        'delivered_at',
        'retry_count',
        'error_message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'buttons' => 'array',
            'sent_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    /**
     * Get the automation that sent this message.
     */
    public function automation(): BelongsTo
    {
        return $this->belongsTo(Automation::class);
    }

    /**
     * Scope to get messages by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending messages.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Mark message as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark message as delivered.
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark message as failed.
     */
    public function markAsFailed(string $error): void
    {
        $this->increment('retry_count');
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }
}
