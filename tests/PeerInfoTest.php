<?php

use be\kunstmaan\multichain\MultichainClient;

class PeerInfoTestCase extends \PHPUnit_Framework_TestCase
{

    /** @var MultichainClient */
    protected $multichain;

    protected function setUp()
    {
        $this->multichain = new MultichainClient("http://sirius.vanderveer.be:8000", 'multichainrpc', '79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz', 3, true);
    }

    /**
     * @group peerinfo
     */
    public function testPeerInfo()
    {
        $peers = $this->multichain->getPeerInfo();
        if (sizeof($peers) > 0) {
            $peerInfo = $peers[0];
            $this->assertInstanceOf('be\kunstmaan\multichain\entities\PeerInfo', $peerInfo);
            $this->assertNotNull($peerInfo->getId());
            $this->assertNotNull($peerInfo->getAddr());
            $this->assertNotNull($peerInfo->getServices());
            $this->assertNotNull($peerInfo->getLastSend());
            $this->assertNotNull($peerInfo->getLastRecv());
            $this->assertNotNull($peerInfo->getBytesSent());
            $this->assertNotNull($peerInfo->getBytesRecv());
            $this->assertNotNull($peerInfo->getConnTime());
            $this->assertNotNull($peerInfo->getPingTime());
            $this->assertNotNull($peerInfo->getVersion());
            $this->assertNotNull($peerInfo->getSubVer());
            //$this->assertNotNull($peerInfo->getHandshakeLocal());
            //$this->assertNotNull($peerInfo->getHandshake());
            $this->assertNotNull($peerInfo->isInbound());
            $this->assertNotNull($peerInfo->getStartingHeight());
            $this->assertNotNull($peerInfo->getBanScore());
            $this->assertNotNull($peerInfo->getSyncedHeaders());
            $this->assertNotNull($peerInfo->getSyncedBlocks());
            $this->assertNotNull($peerInfo->getInFlight());
            $this->assertNotNull($peerInfo->isWhiteListed());
        } else {
            $this->markTestIncomplete(
                'No peers found'
            );
        }
    }

}