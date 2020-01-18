<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\UrlGenerator;

class GenerateSitemap extends Command
{
    /** @var string */
    protected $name = 'lara:generatesitemap';

    /** @var string */
    protected $description = 'Generate sitemap.';

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        parent::__construct();
    }

    public function fire(): void
    {
        // Generate groups sitemap
        $sitemap = App::make('sitemap');
        $x = 1;

        foreach (Group::all() as $group) {
            $sitemap->add(URL::to($this->urlGenerator->route('group_contents', $group->getKey())), null, '1.0', 'daily');
            $sitemap->add(URL::to($this->urlGenerator->route('group_contents_new', $group->getKey())), null, '1.0', 'daily');
            $sitemap->add(URL::to($this->urlGenerator->route('group_entries', $group->getKey())), null, '1.0', 'daily');

            if (!($x % 100)) {
                $this->info($x . ' groups processed');
            }

            $x++;
        }

        $this->info('All groups processed');
        $sitemap->store('xml', 'sitemap-groups');

        unset($sitemap);

        // Generate entries sitemap
        $sitemap = App::make('sitemap');
        $x = 1;

        foreach (Content::all() as $content) {
            $route = $this->urlGenerator->route('content_comments_slug', [$content->getKey(), Str::slug($content->title)]);

            $sitemap->add(URL::to($route), $content->modified_at, '1.0', 'daily');

            if (!($x % 100)) {
                $this->info($x . ' contents processed');
            }

            $x++;
        }

        $this->info('All contents processed');
        $sitemap->store('xml', 'sitemap-contents');

        unset($sitemap);

        // Generate contents sitemap
        $sitemap = App::make('sitemap');
        $x = 1;

        foreach (Entry::all() as $entry) {
            $route = $this->urlGenerator->route('single_entry', $entry->getKey());

            $sitemap->add(URL::to($route), $entry->modified_at, '1.0', 'daily');

            if (!($x % 100)) {
                $this->info($x . ' entries processed');
            }

            $x++;
        }

        $this->info('All entries processed');
        $sitemap->store('xml', 'sitemap-entries');

        unset($sitemap);

        // Generate global sitemap
        $sitemap = App::make('sitemap');

        $sitemap->addSitemap(URL::to('sitemap-groups.xml'));
        $sitemap->addSitemap(URL::to('sitemap-contents.xml'));
        $sitemap->addSitemap(URL::to('sitemap-entries.xml'));

        $sitemap->store('sitemapindex', 'sitemap');
    }
}
