<?php

use be\kunstmaan\multichain\MultichainClient;

class AssetTestCase extends \PHPUnit_Framework_TestCase
{

    /** @var MultichainClient */
    protected $multichain;

    protected function setUp()
    {
        $this->multichain = new MultichainClient("http://sirius.vanderveer.be:8000", 'multichainrpc', '79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz', 3, true);
    }

    /**
     * @group asset
     */
    public function testIssue()
    {
        $address = $this->multichain->getNewAddress();
        $name = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 5);
        $issueQty = 100;
        $units = 0.001;
        $asset = $this->multichain->issue($address, $name, $issueQty, $units);
        $this->assertInstanceOf('be\kunstmaan\multichain\entities\Asset', $asset);
        $this->assertNotNull($asset->getIssueTxId());
        $this->assertEquals($issueQty, $asset->getIssueQty());
        $this->assertEquals($units, $asset->getUnits());
        $this->assertEquals($name, $asset->getName());
    }


    /**
     * @group asset
     */
    public function testListAssets()
    {
        $assets = $this->multichain->listAssets();
        if (sizeof($assets) > 0) {
            $asset = $assets[0];
            $this->assertInstanceOf('be\kunstmaan\multichain\entities\Asset', $asset);
            $this->assertNotNull($asset->getIssueTxId());
        } else {
            $this->markTestIncomplete(
                'No assets found'
            );
        }
    }

}