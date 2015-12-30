<?php

use be\kunstmaan\multichain\MultichainClient;

class TransactionTestCase extends \PHPUnit_Framework_TestCase
{

    /** @var MultichainClient */
    protected $multichain;

    protected function setUp()
    {
        $this->multichain = new MultichainClient("http://sirius.vanderveer.be:8000", 'multichainrpc', '79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz', 3, true);
    }

    /**
     * @group transaction
     */
    public function testListWalletTransactions()
    {
        $transactions = $this->multichain->listWalletTransactions(10, 0, false, false);
    }

}