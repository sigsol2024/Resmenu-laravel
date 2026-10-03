<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'restaurant_id', 'plan_id', 'billing_cycle',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
    ];

    /** The only extension an admin may grant without a payment. */
    public const TRIAL_EXTENSION_DAYS = 7;

    /** A trial (running or lapsed) that has never had a paid period. */
    public function isTrialLike(): bool
    {
        if ($this->current_period_end !== null) {
            return false;
        }

        return $this->status === 'trial'
            || ($this->status === 'expired' && $this->trial_ends_at !== null);
    }

    /** True when the trial ends later than the standard extension from today. */
    public function trialCanBeShortened(): bool
    {
        return $this->isTrialLike()
            && $this->trial_ends_at !== null
            && $this->trial_ends_at->greaterThan(now()->addDays(self::TRIAL_EXTENSION_DAYS));
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }
}
