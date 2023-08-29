<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Companies
 *
 * @property int $id
 * @property string $name
 * @property string $nip
 * @property string $address
 * @property string $city
 * @property int $post_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|Company newModelQuery()
 * @method static Builder|Company newQuery()
 * @method static Builder|Company onlyTrashed()
 * @method static Builder|Company query()
 * @method static Builder|Company whereAddress($value)
 * @method static Builder|Company whereCreatedAt($value)
 * @method static Builder|Company whereDeletedAt($value)
 * @method static Builder|Company whereId($value)
 * @method static Builder|Company whereName($value)
 * @method static Builder|Company whereNip($value)
 * @method static Builder|Company wherePostCode($value)
 * @method static Builder|Company whereUpdatedAt($value)
 * @method static Builder|Company withTrashed()
 * @method static Builder|Company withoutTrashed()
 * @mixin \Eloquent
 */
class Company extends CrudModelAbstract
{
    protected $table = 'companies';

    protected $fillable = [
        'name',
        'nip',
        'address',
        'city',
        'post_code'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * @return $this
     */
    public function remove(): static
    {
        Employee::query()->where('company_id', '=', $this->id)->update(['deleted_at' => new Carbon()]);
        parent::remove();

        return $this;
    }
}
