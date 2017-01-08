<?php

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class BaseDTO
 *
 * @package App\DTO
 */
abstract class BaseDTO implements \JsonSerializable, Arrayable
{
    protected $guarded = ['created_by', 'updated_by'];
    protected $eagerLoaded = [];

    /** {@inheritDoc} */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /** {@inheritDoc} */
    public function toArray()
    {
        return $this->extractValue(getPublicObjectVars($this));
    }

    /**
     * Extract a value to array
     *
     * @param mixed $value
     *
     * @return array
     */
    protected function extractValue($value)
    {
        if ($value instanceof Model) {
            return $this->modelHandler($value);
        } elseif (is_array($value)) {
            return $this->arrayHandler($value);
        } elseif (is_callable($value)) {
            return $this->callbackHandler($value);
        } else {
            return $value;
        }
    }

    protected function modelHandler($value)
    {
        $members = $value->attributesToArray();

        foreach ($this->eagerLoaded as $relationProperty => $data) {
            $value->load($relationProperty);

            if ($value->{$relationProperty}) {
                $members = array_merge($members, (new $data['dto']($value->{$relationProperty}))->toArray());
            }

            unset($members[$data['property']]);
        }

        if ($value->timestamps) {
            unset($members[$value->getCreatedAtColumn()]);
            unset($members[$value->getUpdatedAtColumn()]);
        }

        if (isset(class_uses_recursive($value)[SoftDeletes::class])) {
            unset($members[$value->getDeletedAtColumn()]);
        }

        return $this->extractValue($members);
    }

    protected function arrayHandler($value)
    {
        $parsed = [];

        foreach ($value as $key => $subValue) {
            if (!in_array($key, $this->guarded)) {
                $parsed[Str::camel($key)] = $this->extractValue($subValue);
            }
        }

        return $parsed;
    }

    protected function callbackHandler($value)
    {
        return $this->extractValue(call_user_func($value));
    }
}

/**
 * Get only the public properties of an object
 *
 * @param object $obj
 *
 * @return array
 */
function getPublicObjectVars($obj)
{
    return get_object_vars($obj);
}