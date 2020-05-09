<?php

namespace Strimoid\Providers;

use Illuminate\Support\ServiceProvider;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use Strimoid\Markdown\CoreExtension;
use Strimoid\Markdown\MentionExtension;
use Strimoid\Markdown\SpoilerExtension;

class MarkdownServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('markdown', function () {
            $config = [
                'allow_unsafe_links' => false,
                'html_input' => 'escape',
                'renderer' => [
                    'block_separator' => "\n",
                    'inner_separator' => "\n",
                    'soft_break'      => "<br>",
                ],
            ];

            $environment = new Environment($config);
            $environment->addExtension(new CoreExtension());
            $environment->addExtension(new MentionExtension());
            $environment->addExtension(new SpoilerExtension());

            return new CommonMarkConverter([], $environment);
        });
    }

    public function provides()
    {
        return ['markdown'];
    }
}
