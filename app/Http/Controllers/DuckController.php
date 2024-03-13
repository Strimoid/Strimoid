<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Response;
use SyHolloway\MrColor\Color;

class DuckController extends BaseController
{
    protected int $salt = 0;
    public function __construct(private readonly \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
    }

    public function drawDuck($username): Response
    {
        do {
            $color = $this->getRandomColor($username);
        } while ($color->lightness > 0.8 || $color->lightness < 0.2);

        $svgPath = resource_path('assets/duck.svg');
        $duck = file_get_contents($svgPath);

        // Duck color
        $duck = str_replace('#CCBBAA', $color, $duck);

        // Background color
        $color->hue > 180
            ? $color->hue -= 180
            : $color->hue += 180;

        $color->lightness = 1 - $color->lightness;
        $color->saturation = 1 - $color->saturation;
        $duck = str_replace('#AABBCC', $color, $duck);

        return $this->responseFactory->make($duck)
            ->header('Content-Type', 'image/svg+xml')
            ->setPublic()
            ->setMaxAge(86400);
    }

    protected function getRandomColor($username): Color
    {
        $hash = md5($username . $this->salt++);
        $hex = substr($hash, -6);

        return Color::create(compact('hex'));
    }
}
