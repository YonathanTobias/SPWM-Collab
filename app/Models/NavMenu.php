<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
        'url',
        'icon',
        'is_external',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_external' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Parent menu item (if this is a sub-menu).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavMenu::class, 'parent_id');
    }

    /**
     * Sub-menu items (children).
     */
    public function children(): HasMany
    {
        return $this->hasMany(NavMenu::class, 'parent_id')->orderBy('order', 'asc');
    }

    /**
     * Active children items.
     */
    public function activeChildren(): HasMany
    {
        return $this->hasMany(NavMenu::class, 'parent_id')->where('is_active', true)->orderBy('order', 'asc');
    }

    /**
     * Get menu hierarchy level (1: Menu Utama, 2: Sub-Menu, 3: Sub-Sub-Menu).
     */
    public function getLevelAttribute(): int
    {
        if (is_null($this->parent_id)) {
            return 1;
        }

        if ($this->parent && is_null($this->parent->parent_id)) {
            return 2;
        }

        return 3;
    }

    /**
     * Label for menu level.
     */
    public function getLevelLabelAttribute(): string
    {
        return match ($this->level) {
            1 => 'Menu Utama',
            2 => 'Sub-Menu',
            3 => 'Sub-Sub-Menu',
            default => 'Menu',
        };
    }

    /**
     * Hierarchy path string (e.g. "Profil > Visi Misi > Sasaran Mutu").
     */
    public function getBreadcrumbTitleAttribute(): string
    {
        if ($this->level === 1) {
            return $this->title;
        }

        if ($this->level === 2) {
            return ($this->parent ? $this->parent->title : '') . ' › ' . $this->title;
        }

        $grandparent = $this->parent && $this->parent->parent ? $this->parent->parent->title . ' › ' : '';
        $parent = $this->parent ? $this->parent->title . ' › ' : '';

        return $grandparent . $parent . $this->title;
    }
}
