<?php namespace Strimoid\Repositories;

use Strimoid\Contracts\Repositories\ContentRepository as ContentRepositoryContract;
use Strimoid\Models\Content;

class ContentRepository extends Repository implements ContentRepositoryContract
{
    /**
     * @var Content
     */
    protected $content;

    /**
     * @param Content $content
     */
    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentsFrom($from, $sortBy = 'created_at', $perPage = null)
    {
        $builder = $from->contents();
        $builder->orderBy($sortBy, 'desc');

        if ($perPage) {
            return $this->paginate($builder, $perPage);
        }

        return $builder->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getPopularContentsFrom($from, $sortBy = 'created_at', $perPage = null)
    {
        $builder = $from->contents();
        $builder->orderBy($sortBy, 'desc');

        if ($perPage) {
            return $this->paginate($builder, $perPage);
        }

        return $builder->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewContentsFrom($from, $sortBy = 'created_at', $perPage = null)
    {
    }
}
