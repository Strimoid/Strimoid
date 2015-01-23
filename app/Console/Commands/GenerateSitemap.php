<?php namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GenerateSitemap extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lara:generatesitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        // Generate groups sitemap
        $sitemap = App::make("sitemap");
        $x = 1;

        foreach (Group::all() as $group) {
            $sitemap->add(URL::to(route('group_contents', $group->_id)), null, '1.0', 'daily');
            $sitemap->add(URL::to(route('group_contents_new', $group->_id)), null, '1.0', 'daily');
            $sitemap->add(URL::to(route('group_entries', $group->_id)), null, '1.0', 'daily');

            if (!($x % 100))
                $this->info($x .' groups processed');

            $x++;
        }

        $this->info('All groups processed');
        $sitemap->store('xml', 'sitemap-groups');

        unset($sitemap);

        // Generate entries sitemap
        $sitemap = App::make("sitemap");
        $x = 1;

        foreach (Content::all() as $content) {
            $route = route('content_comments_slug', array($content->_id, Str::slug($content->title)));

            $sitemap->add(URL::to($route), $content->modified_at, '1.0', 'daily');

            if (!($x % 100))
                $this->info($x .' contents processed');

            $x++;
        }

        $this->info('All contents processed');
        $sitemap->store('xml', 'sitemap-contents');

        unset($sitemap);

        // Generate contents sitemap
        $sitemap = App::make("sitemap");
        $x = 1;

        foreach (Entry::all() as $entry) {
            $route = route('single_entry', $entry->_id);

            $sitemap->add(URL::to($route), $entry->modified_at, '1.0', 'daily');

            if (!($x % 100))
                $this->info($x .' entries processed');

            $x++;
        }

        $this->info('All entries processed');
        $sitemap->store('xml', 'sitemap-entries');

        unset($sitemap);

        // Generate global sitemap
        $sitemap = App::make ("sitemap");

        $sitemap->addSitemap(URL::to('sitemap-groups.xml'));
        $sitemap->addSitemap(URL::to('sitemap-contents.xml'));
        $sitemap->addSitemap(URL::to('sitemap-entries.xml'));

        $sitemap->store('sitemapindex', 'sitemap');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}