<?php

namespace Strimoid\Models\Traits;

trait NoUpdatedAt
{
    /**
     * Updated at field is not needed in this model, don't do anything.
     *
     */
    public function setUpdatedAt($value): void
    {
    }
}
