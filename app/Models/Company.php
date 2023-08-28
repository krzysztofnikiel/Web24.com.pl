<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use phpDocumentor\Reflection\Types\Integer;

/**
 * App\Models\Companies
 *
 * @property int $id
 * @property string $name
 * @property string $nip
 * @property string $address
 * @property string $city
 * @property int $post_code
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property mixed|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePostCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Company withoutTrashed()
 * @mixin \Eloquent
 */
class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'companies';

    protected $hidden = [
        'deleted_at'
    ];

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
     * @param array $request
     * @return $this
     */
    public function patch(array $request): static
    {
        if (isset($request['name'])) {
            $this->name = $request['name'];
        }

        if (isset($request['nip'])) {
            $this->nip = $request['nip'];
        }

        if (isset($request['address'])) {
            $this->address = $request['address'];
        }

        if (isset($request['city'])) {
            $this->city = $request['city'];
        }

        if (isset($request['post_code'])) {
            $this->post_code = $request['post_code'];
        }

        $this->updated_at = new Carbon();

        return $this;
    }

    /**
     * @param array $request
     * @return $this
     */
    public function put(array $request): static
    {
        $this->fillFields($request);

        return $this;
    }

    /**
     * @param array $request
     * @return $this
     */
    public function create(array $request): static
    {
        $this->fillFields($request);
        $this->created_at = new Carbon();

        return $this;
    }

    /**
     * @param array $request
     * @return $this
     */
    private function fillFields(array $request): static
    {
        $this->name = $request['name'];
        $this->nip = $request['nip'];
        $this->address = $request['address'];
        $this->city = $request['city'];
        $this->post_code = $request['post_code'];

        return $this;
    }
}
