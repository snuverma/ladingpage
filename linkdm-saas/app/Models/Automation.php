<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Automation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_id',
        'post_id',
        'name',
        'trigger_type',
        'reply_type',
        'public_reply_message',
        'private_dm_message',
        'dm_buttons',
        'flow_id',
        'is_active',
        'delay_seconds_min',
        'delay_seconds_max',
        'require_follow',
        'follow_required_message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'dm_buttons' => 'array',
            'require_follow' => 'boolean',
        ];
    }

    /**
     * Get the page that owns the automation.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the specific post for this automation (null if global).
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the flow associated with this automation.
     */
    public function flow(): BelongsTo
    {
        return $this->belongsTo(Flow::class);
    }

    /**
     * Get all keywords for this automation.
     */
    public function keywords(): HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    /**
     * Get all messages sent by this automation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Scope to get active automations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get global automations (not post-specific).
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('post_id');
    }

    /**
     * Scope to get post-specific automations.
     */
    public function scopeForPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    /**
     * Check if this is a global automation.
     */
    public function isGlobal(): bool
    {
        return $this->post_id === null;
    }

    /**
     * Get random delay within configured range.
     */
    public function getRandomDelay(): int
    {
        return rand($this->delay_seconds_min, $this->delay_seconds_max);
    }
}
