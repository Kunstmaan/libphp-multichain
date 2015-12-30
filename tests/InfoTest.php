<?php

use be\kunstmaan\multichain\MultichainClient;

class InfoTestCase extends \PHPUnit_Framework_TestCase
{

    /** @var MultichainClient */
    protected $multichain;

    protected function setUp()
    {
        $this->multichain = new MultichainClient("http://sirius.vanderveer.be:8000", 'multichainrpc', '79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz', 3, true);
    }

    /**
     * @group info
     */
    public function testGetInfo()
    {
        $info = $this->multichain->getInfo();
        $this->assertInstanceOf('be\kunstmaan\multichain\entities\Info', $info);
        $this->assertNotNull($info->getVersion());
        $this->assertNotNull($info->getProtocolVersion());
        $this->assertNotNull($info->getChainName());
        $this->assertNotNull($info->getDescription());
        $this->assertNotNull($info->getProtocol());
        $this->assertNotNull($info->getPort());
        $this->assertNotNull($info->getSetupBlocks());
        $this->assertNotNull($info->getNodeAddress());
        $this->assertNotNull($info->getWalletVersion());
        $this->assertNotNull($info->getBalance());
        $this->assertNotNull($info->getBlocks());
        $this->assertNotNull($info->getTimeOffset());
        $this->assertNotNull($info->getConnections());
        $this->assertNotNull($info->getProxy());
        $this->assertNotNull($info->getDifficulty());
        $this->assertNotNull($info->isTestNet());
        $this->assertNotNull($info->getKeyPoolOldest());
        $this->assertNotNull($info->getKeyPoolSize());
        $this->assertNotNull($info->getPayTxFee());
        $this->assertNotNull($info->getRelayFee());
        $this->assertNotNull($info->getErrors());
    }

}
