<?php

namespace DigitallyHappy\Assets;

class AssetManager
{
    public $loaded;

    protected $assetsVersion = null;

    public function __construct()
    {
        $this->loaded = [];
    }

    /**
     * Set the version of the assets (useful for cache bursting, for example).
     */
    public function setAssetsVersion(?string $version): void
    {
        $this->assetsVersion = $version;
    }

    protected function applyAssetsVersionToPath(string $path): string
    {
        if (!empty($this->assetsVersion)) {
            if (strpos($path, '?') === false) {
                $path .= '?' . $this->assetsVersion;
            }
            else {
                $path .= '&' . $this->assetsVersion;
            }
        }
        return $path;
    }

    public function echoCss($path)
    {
        if ($this->isLoaded($path)) {
            return;
        }

        $this->markAsLoaded($path);

        $path = $this->applyAssetsVersionToPath($path);

        echo '<link href="'.asset($path).'" rel="stylesheet" type="text/css" />';
    }

    public function echoJs($path)
    {
        if ($this->isLoaded($path)) {
            return;
        }

        $this->markAsLoaded($path);

        $path = $this->applyAssetsVersionToPath($path);

        echo '<script src="'.asset($path).'"></script>';
    }

    /**
     * Adds the asset to the current loaded assets.
     *
     * @param  string  $asset
     * @return void
     */
    public function markAsLoaded($asset)
    {
        if (! $this->isLoaded($asset)) {
            $this->loaded[] = $asset;
        }
    }

    /**
     * Checks if the asset is already on loaded asset list.
     *
     * @param  string  $asset
     * @return bool
     */
    public function isLoaded($asset)
    {
        if (in_array($asset, $this->loaded)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the current loaded assets on app lifecycle.
     *
     * @return array
     */
    public function loaded()
    {
        return $this->loaded;
    }
}
