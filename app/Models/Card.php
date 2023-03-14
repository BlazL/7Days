<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Card extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id', 'title', 'description', 'position', 'allday', 'started_at', 'ended_at', 'calendar_id', 'google_id', 'user_id', 'color',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function getStartedAtAttribute($start)
    {
        return $this->asDateTime($start)->setTimezone($this->calendar?->timezone ?? config('app.timezone'));
    }

    public function getEndedAtAttribute($end)
    {
        return $this->asDateTime($end)->setTimezone($this->calendar?->timezone ?? config('app.timezone'));
    }

    public function getDurationAttribute()
    {
        return $this->started_at->diffForHumans($this->ended_at, true);
    }

    const POSITION_GAP = 60000;
    const POSITION_MIN = 0.00002;
    protected $guarded = [];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->position = self::query()->whereDate('started_at', $model->started_at)->orderByDesc('position')->first()?->position + self::POSITION_GAP;
        });

        static::saved(function ($model) {
            if ($model->position < self::POSITION_MIN) {
                DB::statement("SET @previousPosition := 0");
                DB::statement(
                    "
                    UPDATE cards
                    SET position = (@previousPosition := @previousPosition + ?)
                    WHERE card_list_id = ?
                    ORDER BY position
                ",
                    [
                        self::POSITION_GAP,
                        $model->card_list_id
                    ]
                );
            }
        });
    }
}
