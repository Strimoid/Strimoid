<?php

namespace Strimoid\Models\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Strimoid\Facades\OEmbed;

trait HasThumbnail
{
    public function getThumbnailPath(int $width = null, int $height = null): string
    {
        $host = config('app.cdn_host');

        if ($this->thumbnail && $width && $height) {
            return $host . '/' . $width . 'x' . $height . '/thumbnails/' . $this->thumbnail;
        }

        if ($this->thumbnail) {
            return $host . '/thumbnails/' . $this->thumbnail;
        }

        return '';
    }

    /**
     * Find thumbnail automatically for url.
     */
    public function autoThumbnail(): void
    {
        if (!$this->url) {
            return;
        }

        $url = OEmbed::getThumbnail($this->url);

        if (!$url) {
            return;
        }

        $this->setThumbnail($url);
    }

    /**
     * Download thumbnail from given url, save it to disk and assign to entity.
     */
    public function setThumbnail(string $url): void
    {
        $this->removeThumbnail();

        if (Str::startsWith($url, '//')) {
            $url = 'http:' . $url;
        }

        $data = file_get_contents($url);
        $filename = Str::random(9) . '.png';

        $img = Image::make($data);
        $img->fit(1024, 768);
        $img->encode('png');

        Storage::disk('thumbnails')->put($filename, (string) $img);

        $this->thumbnail = $filename;
        $this->save();
    }

    /**
     * Remove thumbnail from disk and unset thumbnail attribute.
     */
    public function removeThumbnail(): void
    {
        if (!$this->thumbnail) {
            return;
        }

        Storage::disk('uploads')->delete('thumbnails/' . $this->thumbnail);
        $this->thumbnail = null;
    }
}
