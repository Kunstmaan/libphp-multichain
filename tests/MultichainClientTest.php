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
     * 	Returns general information about this node and blockchain. MultiChain adds some fields to Bitcoin Core’s
     * response, giving the blockchain’s chainname, description, protocol, peer-to-peer port. The setupblocks field
     * gives the length in blocks of the setup phase in which some consensus constraints are not applied. The
     * nodeaddress can be passed to other nodes for connecting.
     *
     * @group info
     */
    public function testGetInfo()
    {
        $info = $this->multichain->getInfo();
    }

    /**
     * Returns information about the other nodes to which this node is connected. If this is a MultiChain blockchain,
     * includes handshake and handshakelocal fields showing the remote and local address used during the handshaking
     * for that connection.
     *
     * @group info
     */
    public function testGetPeerInfo()
    {
        $peers = $this->multichain->getPeerInfo();
    }

    /**
     * Returns a new address for receiving payments. Omit the account parameter for the default account – see note
     * about accounts.
     *
     * @group address
     */
    public function testGetNewAddress()
    {
        $address = $this->multichain->getNewAddress();
    }

    /**
     * Outputs a list of available API commands, including MultiChain-specific commands.
     *
     * @group info
     */
    public function testHelp()
    {
        $help = $this->multichain->help();
    }

    /**
     * Sends one or more assets to address, returning the txid. In Bitcoin Core, the amount field is the quantity of
     * the bitcoin currency. For MultiChain, an {"asset":qty, ...} object can be used for amount, in which each asset
     * is an asset name, ref or issuance txid, and each qty is the quantity of that asset to send (see native assets).
     * Use "" as the asset inside this object to specify a quantity of the native blockchain currency. See also
     * sendassettoaddress for sending a single asset and sendfromaddress to control the address whose funds are used.
     *
     * @group send
     */
    public function testSendToAddress(){
        $address1 = $this->multichain->getNewAddress();
        $address2 = $this->multichain->getNewAddress();
        $assetInfo = $this->createTestAsset($address1);
        $this->multichain->sendToAddress($address2, array($assetInfo["name"] => 100));
    }

    /**
     * @param $address
     * @return mixed
     */
    private function createTestAsset($address){
        $name = uniqid("asset");
        $issueQty = 1000000;
        $units = 0.01;
        $assetTxId = $this->multichain->issue($address, $name, $issueQty, $units);
        // before the asset is usable, we need to wait a while
        $this->helper->waitForAssetAvailability($assetTxId);
        return $this->helper->getAssetInfoFromTxId($assetTxId);
    }

    /**
     * Returns information about address including a check for its validity.
     *
     * @group address
     */
    public function testValidateAddress()
    {
        $address = $this->multichain->getNewAddress();
        $validate = $this->multichain->validateAddress($address);
    }

    /**
     * Adds to the atomic exchange transaction in hexstring given by a previous call to createrawexchange or
     * appendrawexchange. This adds an offer to exchange the asset/s in output vout of transaction txid for qty units
     * of asset, where asset is an asset name, ref or issuance txid. The txid and vout should generally be taken from
     * the response to preparelockunspent or preparelockunspentfrom. Multiple items can be specified within the fourth
     * parameter to request multiple assets. Returns a raw hexadecimal transaction in the hex field alongside a
     * complete field stating whether the exchange is complete (i.e. balanced) or not. If complete, the transaction
     * can be transmitted to the network using sendrawtransaction. If not, it can be passed to a further counterparty,
     * who can call decoderawexchange and appendrawexchange as appropriate.
     *
     * @group exchange
     */
    public function testAppendRawExchange(){
        $address1 = $this->multichain->getNewAddress();
        $assetInfo = $this->createTestAsset($address1);
        $lock = $this->multichain->prepareLockUnspent(array($assetInfo["name"] => 123));
        $hexString = $this->multichain->createRawExchange($lock["txid"], $lock["vout"], array($assetInfo["name"] => 100));
        $this->multichain->appendRawExchange($hexString, $lock["txid"], $lock["vout"], array($assetInfo["name"] => 10));
    }

    /**
     * Creates a new atomic exchange transaction which offers to exchange the asset/s in output vout of transaction
     * txid for qty units of asset, where asset is an asset name, ref or issuance txid. The txid and vout should
     * generally be taken from the response to preparelockunspent or preparelockunspentfrom. Multiple items can be
     * specified within the third parameter to request multiple assets. Returns a raw partial transaction in
     * hexadecimal which can be passed to the counterparty, who can call decoderawexchange and appendrawexchange
     * as appropriate.
     *
     * @group exchange
     */
    public function testCreateRawExchange(){
        $address1 = $this->multichain->getNewAddress();
        $assetInfo = $this->createTestAsset($address1);
        $lock = $this->multichain->prepareLockUnspent(array($assetInfo["name"] => 123));
        $this->multichain->createRawExchange($lock["txid"], $lock["vout"], array($assetInfo["name"] => 100));
    }

    /**
     * Prepares an unspent transaction output (useful for building atomic exchange transactions) containing qty units
     * of asset, where asset is an asset name, ref or issuance txid. Multiple items can be specified within the first
     * parameter to include several assets within the output. The output will be locked against automatic selection for
     * spending unless the optional lock parameter is set to false. Returns the txid and vout of the prepared output.
     *
     * @group exchange
     */
    public function testPrepareLockUnspent(){
        $address1 = $this->multichain->getNewAddress();
        $assetInfo = $this->createTestAsset($address1);
        $this->multichain->prepareLockUnspent(array($assetInfo["name"] => 123));
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
