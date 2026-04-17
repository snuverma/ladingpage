<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flow extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'page_id',
        'name',
        'description',
        'trigger_type',
        'is_active',
        'configuration',
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
            'configuration' => 'array',
        ];
    }

    /**
     * Get the page that owns the flow.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get all steps for this flow.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(FlowStep::class)->orderBy('step_order');
    }

    /**
     * Scope to get active flows.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the first step of the flow.
     */
    public function getFirstStepAttribute(): ?FlowStep
    {
        return $this->steps()->first();
    }
}
