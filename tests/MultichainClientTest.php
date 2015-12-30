<?php

use be\kunstmaan\multichain\MultichainClient;
use be\kunstmaan\multichain\MultichainHelper;

class MultichainClientTest extends \PHPUnit_Framework_TestCase
{

    /** @var MultichainClient */
    protected $multichain;

    /** @var  MultichainHelper */
    protected $helper;

    protected function setUp()
    {
        $this->multichain = new MultichainClient("http://sirius.vanderveer.be:8000", 'multichainrpc', '79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz', 3, true);
        $this->helper = new MultichainHelper($this->multichain);
    }

    /**
     * @group info
     */
    public function testGetInfo()
    {
        $info = $this->multichain->getInfo();
    }

    /**
     * @group info
     */
    public function testGetPeerInfo()
    {
        $peers = $this->multichain->getPeerInfo();
    }

    /**
     * @group address
     */
    public function testGetNewAddress()
    {
        $address = $this->multichain->getNewAddress();
    }

    /**
     * @group send
     */
    public function testSendToAddress(){
        $address1 = $this->multichain->getNewAddress();
        $address2 = $this->multichain->getNewAddress();
        $name = uniqid("asset");
        $issueQty = 1000000;
        $units = 0.01;
        $asset = $this->multichain->issue($address1, $name, $issueQty, $units);
        $this->helper->waitForAssetAvailability($asset);
        $this->multichain->sendToAddress($address2, array($name => 100));
    }


    /**
     * @group info
     */
    public function testHelp()
    {
        $help = $this->multichain->help();
    }

    /**
     * @group info
     */
    public function testGetBlockchainParams()
    {
        $params = $this->multichain->getBlockchainParams();
    }



    /**
     * @group address
     */
    public function testValidateAddress()
    {
        $address = $this->multichain->getNewAddress();
        $validate = $this->multichain->validateAddress($address);
    }

    /**
     * @group address
     */
    public function testGetAddresses()
    {
        $addresses = $this->multichain->getAddresses(true);
    }

    /**
     * @group asset
     */
    public function testIssue()
    {
        $address = $this->multichain->getNewAddress();
        $name = uniqid("asset");
        $issueQty = 1000000;
        $units = 0.01;
        $asset = $this->multichain->issue($address, $name, $issueQty, $units);
    }


    /**
     * @group asset
     */
    public function testListAssets()
    {
        $assets = $this->multichain->listAssets();
    }

    /**
     * @group transaction
     */
    public function testListWalletTransactions()
    {
        $transactions = $this->multichain->listWalletTransactions(10, 0, false, false);
    }
}
