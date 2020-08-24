<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

/**
 * App\Models\Payment
 *
 * @property string $id
 * @property string $target
 * @property float $amount
 * @property string|null $callback_url
 * @property \Illuminate\Support\Carbon|null $callback_at
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon $paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment expired()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment from(\Illuminate\Support\Carbon $from)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment notExpired()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment to(\Illuminate\Support\Carbon $to)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereCallbackAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereCallbackUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    /**
     * Payment session lifetime (in minutes)
     *
     * @var int
     */
    protected const EXPIRATION_TIME = 30;

    /**
     * Set custom boot
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(static function (Model $model) {
            $expirationTime = self::EXPIRATION_TIME;
            $model->setAttribute($model->getKeyName(), (string) Uuid::uuid4());
            if (!$model->getAttribute('expires_at')){
                $model->setAttribute('expires_at',
                    now()->addMinutes($expirationTime)
                );
            }
        });
    }

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['target', 'amount', 'paid', 'callback_url', 'callback_at', 'created_at', 'updated_at'];

    /**
     * @var string[]
     */
    protected $hidden = [];

    /**
     * @var string[]
     */
    protected $appends = [];

    /**
     * @var string[]
     */
    protected $dates = ['expires_at', 'callback_at', 'paid'];

    /**
     * Checks expiry status payment session.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Negative of isExpired().
     *
     * @return bool
     */
    public function isNotExpired(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Scope a query to only expired payments.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeExpired(Builder $query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Scope a query to only not expired payments.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeNotExpired(Builder $query)
    {
        return $query->where('expires_at', '>=', now());
    }

    /**
     * Scope a query to payments period from some date.
     *
     * @param  Builder  $query
     *
     * @param  Carbon  $from
     *
     * @return Builder
     */
    public function scopeFrom(Builder $query, Carbon $from)
    {
        return $query->where('created_at', '>=', $from);
    }

    /**
     * Scope a query to payments period to some date.
     *
     * @param  Builder  $query
     *
     * @param  Carbon  $to
     *
     * @return Builder
     */
    public function scopeTo(Builder $query, Carbon $to)
    {
        return $query->where('created_at', '<=', $to);
    }
}
