<?php

namespace Groups;

use FakeGroup;

class Popular extends FakeGroup {

    protected function getBuilder($model)
    {
        $builder = with(new $model)->newQuery();

        $groups = ['Nauka',
            'NiezlaStronkaWSieci',
            'ciekawostki',
            'Humor',
            'Linux',
            'Gry',
            'Technika',
            'programowanie',
            'CiekaweMiejsca',
            'Historia',
            'Security',
            'LifeHack',
            'Ksiazki',
            'Matematyka',
            'FotoHistoria',
            'Polska',
            'Internet',
            'Fizyka',
            'FilmyDokumentalne',
            'EarthPorn',
            'Android',
            'Herbata',
            'obrazki',
            'Film',
            'Informatyka',
            'GryTradycyjne',
            'Polandball',
            'muzyka',
            'Mapy',
            'Fantastyka',
            'TED',
            'StareGry',
            'RozwojOsobisty',
            'Astronomia',
            'ZacnaStronka',
            'humorInformatyczny',
            'Seriale',
            'Fotografia',
            'Kultura',
            'UrbanPorn',
            'FortPorn',
            'Psychologia',
            'DIY',
            'webdev',
            'Polskie',
            'krajobrazy',
            'ekonomia'];

        $builder->whereIn('group_id', $groups);

        return $builder;
    }

}
