<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 */
abstract class CrudModelAbstract extends Model implements CrudModelInterface
{
    use HasFactory;
    use SoftDeletes;

    protected $hidden = [
        'deleted_at'
    ];

    /**
     * @param array $request
     * @return $this
     */
    public function patch(array $request): static
    {
        $this->fill($request);

        $this->updated_at = new Carbon();

        return $this;
    }

    /**
     * @return $this
     */
    public function remove(): static
    {
        $this->deleted_at = new Carbon();

        return $this;
    }

    /**
     * @param array $request
     * @return $this
     */
    public function add(array $request): static
    {
        $this->fill($request);
        $this->created_at = new Carbon();

        return $this;
    }

    /**
     * @param array $request
     * @param int $id
     * @return $this
     */
    public function put(array $request, int $id): static
    {
        $this->fill($request);

        if($this->id == null) {
            $this->created_at = new Carbon();
            $this->id = $id;
        } else {
            $this->updated_at = new Carbon();
        }

        return $this;
    }
}
