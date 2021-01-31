<?php

namespace Strimoid\Models\Traits;

use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class HasAvatar.
 */
trait HasAvatar
{
    public function setAvatar(string $file): void
    {
        $this->deleteAvatar();

        $filename = Str::random(8) . '.png';

        $img = Image::make($file);
        $img->fit(100, 100);
        $img->encode('png');

        $path = $this->avatarPath . $filename;
        Storage::disk('uploads')->put($path, $img);

        $this->avatar = $filename;
    }

    public function deleteAvatar(): void
    {
        if (!$this->avatar) {
            return;
        }

        $path = $this->avatarPath . $this->avatar;
        Storage::disk('uploads')->delete($path);

        $this->avatar = null;
        $this->save();
    }
}
