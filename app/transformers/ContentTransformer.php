<?php

namespace Strimoid\Transformers;

use League\Fractal\TransformerAbstract;

class ContentTransformer extends TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'group',
        'user'
    ];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'group',
        'user'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @var Content $content
     * @return array
     */
    public function transform(Content $content)
    {
        return [
            '_id' => $content->id,
            'title' => $content->title,
            'description' => $content->description,
            'eng' => (bool)$content->eng,
            'nsfw' => (bool)$content->nsfw,
            'links' => [
                [
                    'rel' => 'self',
                    'uri' => '/books/' . $book->id,
                ]
            ],
        ];
    }

    /**
     * Include Group
     *
     * @var Content $content
     * @return League\Fractal\ItemResource
     */
    public function includeGroup(Content $content)
    {
        $group = $content->group;

        return $this->item($group, new GroupTransformer);
    }

}