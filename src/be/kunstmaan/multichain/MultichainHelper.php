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
     * @param $assetTxId
     * @return bool
     */
    public function isAssetAvailable($assetTxId){
        $assetInfo = $this->getAssetInfoFromTxId($assetTxId);
        return !is_null($assetInfo["assetref"]);
    }

    /**
     * @param $assetTxId
     */
    public function waitForAssetAvailability($assetTxId){
        while (!$this->isAssetAvailable($assetTxId)){
            sleep(1);
        }
    }

    /**
     * @param $assetTxId
     * @return mixed
     */
    public function getAssetInfoFromTxId($assetTxId){
        $assetInfo = $this->multichain->listAssets($assetTxId);
        return $assetInfo[0];
    }

}