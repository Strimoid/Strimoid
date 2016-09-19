<?php namespace Strimoid\Models\Traits;

use Config;
use Image;
use OEmbed;
use Storage;
use Str;

trait HasThumbnail
{
    /**
     * Get path to thumbnail in requested size.
     */
    public function getThumbnailPath(int $width = null, int $height = null) : string
    {
        $host = config('app.cdn_host');

        if ($this->thumbnail && $width && $height) {
            return $host.'/'.$width.'x'.$height.'/thumbnails/'.$this->thumbnail;
        } elseif ($this->thumbnail) {
            return $host.'/thumbnails/'.$this->thumbnail;
        }

        return '';
    }

    /**
     * Find thumbnail automatically for url.
     */
    public function autoThumbnail()
    {
        if (!$this->url) {
            return false;
        }

        $url = OEmbed::getThumbnail($this->url);

        if (!$url) {
            return false;
        }

        $this->setThumbnail($url);
    }

    /**
     * Download thumbnail from given url, save it to disk and assign to entity.
     */
    public function setThumbnail(string $url)
    {
        $this->removeThumbnail();

        if (starts_with($url, '//')) {
            $url = 'http:'.$url;
        }

        $data = file_get_contents($url);
        $filename = Str::random(9).'.png';

        $img = Image::make($data);
        $img->fit(640, 480);
        $img->encode('png');

        Storage::disk('thumbnails')->put($filename, (string) $img);

        $this->thumbnail = $filename;
        $this->save();
    }

    /**
     * Remove thumbnail from disk and unset thumbnail attribute.
     */
    public function removeThumbnail()
    {
        if (!$this->thumbnail) {
            return;
        }

        Storage::disk('uploads')->delete('thumbnails/'.$this->thumbnail);
        $this->thumbnail = null;
    }
}
