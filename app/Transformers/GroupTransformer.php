<?php

namespace Strimoid\Transformers;

use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Group $group)
    {
        return [
            '_id' => $group->id,
            'avatar' => $group->avatar,
            'created_at' => $group->created_at,
            'description' => $group->description,
            'name' => $group->name,
            'sidebar' => $group->sidebar,
            'links' => [
                [
                    'rel' => 'self',
                    'uri' => '/books/' . $book->id,
                ]
            ],
        ];
    }

}