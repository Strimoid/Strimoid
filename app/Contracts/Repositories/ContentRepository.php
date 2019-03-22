<?php

namespace Strimoid\Contracts\Repositories;

interface ContentRepository
{
    public function getContentsFrom($from, $sortBy = 'created_at', $perPage = 'null'): void;

    public function getPopularContentsFrom($from, $sortBy = 'created_at', $perPage = 'null'): void;

    public function getNewContentsFrom($from, $sortBy = 'created_at', $perPage = 'null'): void;
}
