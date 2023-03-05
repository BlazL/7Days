<?php

namespace App\Models;

use App\Jobs\SynchronizeGoogleEvents;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_id', 'name', 'color', 'timezone', 'import'
    ];

    protected $casts = ['import' => 'boolean'];


    public function googleAccount()
    {
        return $this->belongsTo(GoogleAccount::class);
    }

    public function events()
    {
        return $this->hasMany(Card::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($calendar) {
            if ($calendar->import) {
                SynchronizeGoogleEvents::dispatch($calendar);
            }
        });

        static::updated(function ($calendar) {
            if ($calendar->isDirty('import')) {
                SynchronizeGoogleEvents::dispatch($calendar);
            }
        });
        // static::updated(function ($calendar) {
        //     Log::info('updated');
        //     // SynchronizeGoogleEvents::dispatch($calendar);
        // });
    }
}
