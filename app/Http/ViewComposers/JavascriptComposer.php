<?php namespace Strimoid\Http\ViewComposers;

use Auth;
use Illuminate\View\View;
use JavaScript;
use Strimoid\Models\Content;
use Strimoid\Models\Group;

class JavascriptComposer
{
    public function compose(View $view)
    {
        $data = $view->getData();

        if (Auth::check()) {
            $this->putUserInfo();
        }

        if (array_key_exists('group', $data) && $data['group'] instanceof Group) {
            JavaScript::put(['group' => $data['group']]);
        }

        if (array_key_exists('content', $data) && $data['content'] instanceof Content) {
            JavaScript::put(['content' => $data['content']->toArray()]);
        }

        JavaScript::put([
            'config' => [
                'env'        => app()->environment(),
                'pusher_key' => config('broadcasting.connections.pusher.key'),
            ],
        ]);
    }

    protected function putUserInfo()
    {
        JavaScript::put([
            'user'     => user(),
            'settings' => user()->settings,
        ]);
    }
}
