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
}
