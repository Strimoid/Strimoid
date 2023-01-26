<?php

namespace Strimoid\Http\ViewComposers;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\View\View;
use JavaScript;
use Strimoid\Models\Content;
use Strimoid\Models\Group;

class JavascriptComposer
{
    public function __construct(
        private AuthManager $authManager,
        private Repository $configRepository
    ) {
    }

    public function compose(View $view): void
    {
        $data = $view->getData();

        if ($this->authManager->check()) {
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
                'env' => app()->environment(),
                'pusher_key' => $this->configRepository->get('broadcasting.connections.pusher.key'),
            ],
        ]);
    }

    protected function putUserInfo(): void
    {
        JavaScript::put([
            'user' => user(),
            'settings' => user()->settings,
        ]);
    }
}
