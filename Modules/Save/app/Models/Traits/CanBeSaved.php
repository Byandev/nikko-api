<?php

namespace Modules\Save\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Save\Models\Save;

trait CanBeSaved
{
    public function saves(): MorphMany
    {
        return $this->morphMany(Save::class, 'savable');
    }

    public function savers(): MorphMany
    {
        return $this->morphMany(Save::class, 'saver');
    }

    public function scopeAppendIsSavedBy(
        Builder $query,
        $saver
    ): void {
        $query->addSelect([
            'is_saved' => Save::selectRaw('count(id) as count')
                ->whereSavableType($this->getMorphClass())
                ->whereColumn((new Save)->qualifyColumn('savable_id'),
                    $this->qualifyColumn('id'))
                ->where('saver_id', $saver->getKey())
                ->where('saver_type', get_class($saver))
                ->take(1),
        ]);

        $query->withCasts(['is_saved' => 'boolean']);
    }

    public function scopeOnlySavedBy(Builder $query, $saver): void
    {
        $query->whereHas('saves', function ($query) use ($saver) {
            $query
                ->where('saver_id', $saver->getKey())
                ->where('saver_type', get_class($saver));
        });
    }

    public function saveBy($saver)
    {
        return $this->saves()
            ->firstOrCreate([
                'saver_id' => $saver->getKey(),
                'saver_type' => get_class($saver),
            ]);
    }

    public function unSaveBy($saver): void
    {
        $this->saves()
            ->where('saver_id', $saver->getKey())
            ->where('saver_type', get_class($saver))
            ->delete();
    }

    public function isSavedBy($saver): bool
    {
        return $this->saves()
            ->where('saver_id', $saver->getKey())
            ->where('saver_type', get_class($saver))
            ->exists();
    }
}
