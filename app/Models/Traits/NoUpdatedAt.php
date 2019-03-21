<?php

namespace Strimoid\Models\Traits;

trait NoUpdatedAt
{
    /**
     * Updated at field is not needed in this model, don't do anything.
     *
     * @param mixed $value
     */
    public function setUpdatedAt($value)
    {
    }
}
