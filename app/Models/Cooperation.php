<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cooperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'partner_name',
        'document_number',
        'document_type',
        'level',
        'scope',
        'start_date',
        'end_date',
        'status',
        'is_public',
        'file_path',
        'document_link',
        'contact_person',
        'contact_email',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_public' => 'boolean',
    ];

    /**
     * Dynamically compute the active status based on current date
     * unless explicitly set to 'Dalam Proses Perpanjangan'.
     */
    public function getComputedStatusAttribute(): string
    {
        if ($this->status === 'Dalam Proses Perpanjangan') {
            return 'Dalam Proses Perpanjangan';
        }

        $today = Carbon::today();
        if ($this->end_date < $today) {
            return 'Kedaluwarsa';
        }

        if ($this->end_date <= $today->copy()->addDays(90)) {
            return 'Akan Berakhir';
        }

        return 'Aktif';
    }

    /**
     * Calculate days until document expires
     */
    public function getDaysRemainingAttribute(): int
    {
        return (int) Carbon::today()->diffInDays($this->end_date, false);
    }

    /**
     * Target download or view URL (PDF File or Google Docs/Drive Link)
     */
    public function getDownloadUrlAttribute(): ?string
    {
        if (!empty($this->document_link)) {
            return $this->document_link;
        }
        if (!empty($this->file_path)) {
            return route('public.download', $this->id);
        }
        return null;
    }

    /**
     * Check if document has Google Docs or external link
     */
    public function getHasExternalLinkAttribute(): bool
    {
        return !empty($this->document_link);
    }

    /**
     * Get CSS badge color classes for the status
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->computed_status) {
            'Aktif' => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800',
            'Akan Berakhir' => 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800',
            'Kedaluwarsa' => 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800',
            'Dalam Proses Perpanjangan' => 'bg-sky-100 text-sky-800 border-sky-300 dark:bg-sky-950/50 dark:text-sky-300 dark:border-sky-800',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    /**
     * Get CSS badge color classes for the document level
     */
    public function getLevelBadgeClassAttribute(): string
    {
        return match ($this->level) {
            'Internasional' => 'bg-purple-100 text-purple-800 border-purple-300 dark:bg-purple-950/50 dark:text-purple-300 dark:border-purple-800',
            'Nasional' => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800',
            'Lokal' => 'bg-slate-100 text-slate-800 border-slate-300 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
