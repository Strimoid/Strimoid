<?php namespace Strimoid\Models\Traits;

use Image;
use Storage;
use Str;

/**
 * Class HasAvatar.
 */
trait HasAvatar
{
    /**
     * Set entity avatar to image from given path.
     *
     * @param $file
     */
    public function setAvatar($file)
    {
        $this->deleteAvatar();

        $filename = Str::random(8).'.png';

        $img = Image::make($file);
        $img->fit(100, 100);
        $img->encode('png');

        $path = $this->avatarPath.$filename;
        Storage::disk('uploads')->put($path, $img);

        $this->avatar = $filename;
    }

    /**
     * Delete entity avatar.
     */
    public function deleteAvatar()
    {
        if (!$this->avatar) {
            return;
        }

        $path = $this->avatarPath.$this->avatar;
        Storage::disk('uploads')->delete($path);

        $this->avatar = null;
        $this->save();
    }
}
