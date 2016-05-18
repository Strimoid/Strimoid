<?php namespace Strimoid\Http\Controllers;

use SyHolloway\MrColor\Color;

class DuckController extends BaseController
{
    protected $salt = 0;

    public function drawDuck($username)
    {
        do {
            $color = $this->getRandomColor($username);
        } while ($color->lightness > 0.8 || $color->lightness < 0.2);

        $svgPath = resource_path('assets/duck.svg');
        $duck = file_get_contents($svgPath);

        // Duck color
        $duck = str_replace('#CCBBAA', $color, $duck);

        // Background color
        ($color->hue > 180)
            ? $color->hue -= 180
            : $color->hue += 180 ;

        $color->lightness = 1 - $color->lightness;
        $color->saturation = 1 - $color->saturation;
        $duck = str_replace('#AABBCC', $color, $duck);

        return response($duck)->header('Content-Type', 'image/svg+xml');
    }

    protected function getRandomColor($username)
    {
        $hash = md5($username.$this->salt++);
        $hex = substr($hash, -6);
        return Color::create(compact('hex'));
    }
}
