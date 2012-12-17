<?php

namespace Caseyamcl\ContentRetriever;
use RuntimeException;

class Asset
{
    /**
     * @var ContentMap $contentMap
     */
    private $contentMap;

    /**
     * @var array  Keys are file extensions; values are mime-types
     */
    private $mappings = array(
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'png'  => 'image/png'
    );

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param contentMap $contentMap
     */
    public function __construct(ContentMap $contentMap)
    {
        $this->contentMap = $contentMap;
    }

    // --------------------------------------------------------------

    /**
     * Check if an asset exists
     *
     * @param  string $path
     * @param  string $asset
     */
    public function assetExists($path)
    {
        $asset = basename($path);
        $apath = dirname($path);
        $ext   = pathinfo($asset, PATHINFO_EXTENSION);

        return (isset($this->mappings[$ext]))
            ? $this->contentMap->checkItemExists($apath, $asset)
            : false;
    }

    // --------------------------------------------------------------

    /**
     * Get an asset
     *
     * @param string $path
     * @param string $asset
     * @return string
     */
    public function getAsset($path)
    {
        $asset = basename($path);
        $apath = dirname($path);

        return $this->assetExists($path)
            ? $this->contentMap->getItem($apath, $asset)
            : false;
    }

    // --------------------------------------------------------------

    /**
     * Stream an Asset
     *
     * To be used as a callback
     *
     * @param string $path
     * @param string $asset
     * @return void
     */
    public function streamAsset($path)
    {
        $asset = basename($path);
        $apath = dirname($path);

        if ($this->assetExists($path)) {
            $this->contentMap->streamItem($apath, $asset);
        }
        else {
            echo '';
        }
    }

    // --------------------------------------------------------------

    /**
     * Get mime-type for a given path
     *
     * @param string $path
     * @return string  Mime-Type
     */
    public function getMime($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return $this->mappings[$ext];
    }
}

/* EOF: AssetMap.php */