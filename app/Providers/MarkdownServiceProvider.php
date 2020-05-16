<?php

namespace Strimoid\Providers;

use Illuminate\Support\ServiceProvider;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use Strimoid\Markdown\CoreExtension;
use Strimoid\Markdown\ImageLinkExtension;
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

            // Official CommonMark extension
            $environment->addExtension(new AutolinkExtension());
            $environment->addExtension(new ExternalLinkExtension());

            // Strm CommonMark Extensions
            $environment->addExtension(new ImageLinkExtension());
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
