<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlowStep extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'flow_id',
        'step_order',
        'type',
        'name',
        'content',
        'buttons',
        'conditions',
        'delay_seconds',
        'next_step_id',
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
            'conditions' => 'array',
        ];
    }

    /**
     * Get the flow that owns this step.
     */
    public function flow(): BelongsTo
    {
        return $this->belongsTo(Flow::class);
    }

    /**
     * Get the next step in the flow.
     */
    public function nextStep(): BelongsTo
    {
        return $this->belongsTo(FlowStep::class, 'next_step_id');
    }

    /**
     * Scope to get steps for a flow ordered by step_order.
     */
    public function scopeForFlow($query, $flowId)
    {
        return $query->where('flow_id', $flowId)->orderBy('step_order');
    }

    /**
     * Check if this is a message type step.
     */
    public function isMessageStep(): bool
    {
        return $this->type === 'message';
    }

    /**
     * Check if this is a button type step.
     */
    public function isButtonStep(): bool
    {
        return $this->type === 'button';
    }

    /**
     * Check if this is a condition type step.
     */
    public function isConditionStep(): bool
    {
        return $this->type === 'condition';
    }
}
