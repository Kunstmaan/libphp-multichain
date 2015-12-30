<?php


namespace be\kunstmaan\multichain;


class MultichainHelper
{

    /** @var MultichainClient */
    protected $multichain;

    public function __construct($multichainClient)
    {
        $this->multichain = $multichainClient;
    }

    /**
     * @param $asset
     * @return bool
     */
    public function isAssetAvailable($asset){
        $assetInfo = $this->multichain->listAssets($asset);
        return !is_null($assetInfo[0]["assetref"]);
    }

    /**
     * @param $asset
     */
    public function waitForAssetAvailability($asset){
        while (!$this->isAssetAvailable($asset)){
            sleep(1);
        }
    }
}