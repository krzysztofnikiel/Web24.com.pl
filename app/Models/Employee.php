<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Employees
 *
 * @property int $id
 * @property int $company_id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $phone_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|Employee newModelQuery()
 * @method static Builder|Employee newQuery()
 * @method static Builder|Employee query()
 * @method static Builder|Employee whereCompanyId($value)
 * @method static Builder|Employee whereCreatedAt($value)
 * @method static Builder|Employee whereDeletedAt($value)
 * @method static Builder|Employee whereEmail($value)
 * @method static Builder|Employee whereId($value)
 * @method static Builder|Employee whereLastname($value)
 * @method static Builder|Employee whereName($value)
 * @method static Builder|Employee wherePhoneNumber($value)
 * @method static Builder|Employee whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Employee extends CrudModelAbstract
{
    protected $table = 'employees';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone_number',
        'company_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
