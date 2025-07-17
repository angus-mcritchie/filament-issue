<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class NullableHtml implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (blank($value)) {
            return null;
        }

        return str(html_entity_decode($value))->toHtmlString();
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (blank($value)) {
            return null;
        }

        return (string) $value;
    }
}
