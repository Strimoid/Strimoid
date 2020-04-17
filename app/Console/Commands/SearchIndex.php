<?php

namespace Strimoid\Console\Commands;

use Illuminate\Console\Command;
use MeiliSearch\Client;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\Group;

class SearchIndex extends Command
{
    protected $signature = 'search:index {--cleanup}';
    protected $description = 'Synchronize search engine index';

    private Client $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Client(
            config('strimoid.meilisearch.url'),
            config('strimoid.meilisearch.master_key')
        );
    }

    public function handle(): void
    {
        if ($this->option('cleanup')) {
            $this->client->deleteIndex('contents');
            $this->client->deleteIndex('entries');
            $this->client->deleteIndex('groups');
        }

        $this->indexContents();
        $this->indexEntries();
        $this->indexGroups();
    }

    private function indexContents(): void
    {

        $index = $this->client->createIndex('contents');

        Content::chunk(100, function ($contents) use ($index) {
            $documents = array_map(fn(Content $content) => [
                'id' => $content->id,
                'title' => $content->title,
                'description' => $content->description,
            ], $contents->all());

            $index->addDocuments($documents);
        });
    }

    private function indexEntries(): void
    {

        $index = $this->client->createIndex('entries');

        Entry::chunk(100, function($entries) use ($index) {
            $documents = array_map(fn(Entry $entry) => [
                'id' => $entry->id,
                'text' => $entry->text,
                'text_source' => $entry->text_source,
            ], $entries->all());

            $index->addDocuments($documents);
        });
    }

    private function indexGroups(): void
    {
        $index = $this->client->createIndex('groups');

        Group::chunk(100, function ($groups) use ($index) {
           $documents = array_map(fn(Group $group) => [
               'id' => $group->id,
               'urlname' => $group->urlname,
               'name' => $group->name,
               'description' => $group->description,
           ], $groups->all());

           $index->addDocuments($documents);
        });
    }
}
