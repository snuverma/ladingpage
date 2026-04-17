<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keyword extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'automation_id',
        'keyword',
        'match_exact',
        'case_sensitive',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'match_exact' => 'boolean',
            'case_sensitive' => 'boolean',
        ];
    }

    /**
     * Get the automation that owns the keyword.
     */
    public function automation(): BelongsTo
    {
        return $this->belongsTo(Automation::class);
    }

    /**
     * Check if text matches this keyword.
     */
    public function matches(string $text): bool
    {
        if ($this->case_sensitive) {
            return $this->match_exact 
                ? $text === $this->keyword 
                : str_contains($text, $this->keyword);
        }

        return $this->match_exact
            ? strtolower($text) === strtolower($this->keyword)
            : str_contains(strtolower($text), strtolower($this->keyword));
    }

    /**
     * Scope to get keywords for automation.
     */
    public function scopeForAutomation($query, $automationId)
    {
        return $query->where('automation_id', $automationId);
    }
}
