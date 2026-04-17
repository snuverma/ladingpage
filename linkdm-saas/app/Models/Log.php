<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'account_id',
        'automation_id',
        'event_type',
        'platform',
        'external_id',
        'payload',
        'message',
        'level',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    /**
     * Get the user associated with this log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account associated with this log.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the automation associated with this log.
     */
    public function automation(): BelongsTo
    {
        return $this->belongsTo(Automation::class);
    }

    /**
     * Scope to filter by level.
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to get error logs.
     */
    public function scopeErrors($query)
    {
        return $query->where('level', 'error');
    }

    /**
     * Scope to filter by event type.
     */
    public function scopeEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Create an info log entry.
     */
    public static function info(string $eventType, string $message, ?Model $related = null): self
    {
        return self::createLog('info', $eventType, $message, $related);
    }

    /**
     * Create an error log entry.
     */
    public static function error(string $eventType, string $message, ?Model $related = null): self
    {
        return self::createLog('error', $eventType, $message, $related);
    }

    /**
     * Create a warning log entry.
     */
    public static function warning(string $eventType, string $message, ?Model $related = null): self
    {
        return self::createLog('warning', $eventType, $message, $related);
    }

    /**
     * Helper method to create log entries.
     */
    private static function createLog(string $level, string $eventType, string $message, ?Model $related = null): self
    {
        $data = [
            'level' => $level,
            'event_type' => $eventType,
            'message' => $message,
        ];

        if ($related instanceof User) {
            $data['user_id'] = $related->id;
        } elseif ($related instanceof Account) {
            $data['account_id'] = $related->id;
            $data['user_id'] = $related->user_id;
        } elseif ($related instanceof Automation) {
            $data['automation_id'] = $related->id;
            $data['page_id'] = $related->page_id;
        }

        return self::create($data);
    }
}
