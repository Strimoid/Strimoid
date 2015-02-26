<?php namespace Strimoid\Transformers;

use League\Fractal\TransformerAbstract;
use Strimoid\Models\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @var User
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            '_id'         => $user->getKey(),
            'name'        => $user->name,
            'description' => $content->description,
            'eng'         => (bool) $content->eng,
            'nsfw'        => (bool) $content->nsfw,
        ];
    }
}
