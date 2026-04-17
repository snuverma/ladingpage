<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_id',
        'page_id',
        'name',
        'username',
        'access_token',
        'token_expires_at',
        'is_active',
        'instagram_account_id',
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
            'instagram_account_id' => 'array',
            'token_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the account that owns the page.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get all posts for this page.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get all automations for this page.
     */
    public function automations(): HasMany
    {
        return $this->hasMany(Automation::class);
    }

    /**
     * Get all flows for this page.
     */
    public function flows(): HasMany
    {
        return $this->hasMany(Flow::class);
    }

    /**
     * Check if page has linked Instagram account.
     */
    public function hasInstagramAccount(): bool
    {
        return !empty($this->instagram_account_id);
    }

    /**
     * Scope to get active pages.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
