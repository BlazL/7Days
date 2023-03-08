<?php

namespace App\Traits;

use App\Scopes\TenantScope;

trait BelongsToTenant
{
    protected static function bootBelongsToEntity()
    {
        if (app()->runningInConsole()) {
            return;
        }
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            $model->entity_id = session()->get('tenant_id');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
