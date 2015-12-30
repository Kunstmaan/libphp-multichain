<?php

use be\kunstmaan\multichain\MultichainClient;

class AddressTestCase extends \PHPUnit_Framework_TestCase
{

    /** @var MultichainClient */
    protected $multichain;

    protected function setUp()
    {
        $this->multichain = new MultichainClient("http://sirius.vanderveer.be:8000", 'multichainrpc', '79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz', 3, true);
    }

    /**
     * @group address
     */
    public function testGetNewAddress()
    {
        $address = $this->multichain->getNewAddress();
        $this->assertInstanceOf('be\kunstmaan\multichain\entities\Address', $address);
        $this->assertNotNull($address->getHash());
        $this->assertNotNull($address->getPubKey());
        $address = $this->multichain->getNewAddress(false);
        $this->assertInstanceOf('be\kunstmaan\multichain\entities\Address', $address);
        $this->assertNotNull($address->getHash());
        $this->assertNull($address->getPubKey());
    }

    /**
     * @group address
     */
    public function testValidateAddress()
    {
        $address = $this->multichain->getNewAddress(true);
        $this->assertInstanceOf('be\kunstmaan\multichain\entities\Address', $address);
        $this->assertNotNull($address->getHash());
        $this->assertNotNull($address->getPubKey());
        $this->assertNotNull($address->isCompressed());
        $this->assertNotNull($address->isMine());
        $this->assertNotNull($address->isScript());
        $this->assertNotNull($address->isValid());
        $this->assertNotNull($address->isWatchOnly());
    }

    /**
     * @group address
     */
    public function testGetAddresses()
    {
        $addresses = $this->multichain->getAddresses(true);
        if (sizeof($addresses) > 0) {
            $address = $addresses[0];
            $this->assertInstanceOf('be\kunstmaan\multichain\entities\Address', $address);
            $this->assertNotNull($address->getHash());
        } else {
            $this->markTestIncomplete(
                'No addresses found'
            );
        }
    }

}