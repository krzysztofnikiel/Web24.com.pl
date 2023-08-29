<?php

namespace App\Models;

interface CrudModelInterface
{
    /**
     * @param array $request
     * @return $this
     */
    public function patch(array $request): static;

    /**
     * @return $this
     */
    public function remove(): static;

    /**
     * @param array $request
     * @param int $id
     * @return $this
     */
    public function put(array $request, int $id): static;

    /**
     * @param array $request
     * @return $this
     */
    public function add(array $request): static;
}
