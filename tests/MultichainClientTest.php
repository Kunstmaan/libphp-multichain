<?php

use be\kunstmaan\multichain\MultichainClient;
use be\kunstmaan\multichain\MultichainHelper;

class MultichainClientTest extends \PHPUnit_Framework_TestCase
{

    /** @var MultichainClient */
    protected $multichain;

    /** @var  MultichainHelper */
    protected $helper;

    public function setUp()
    {
        $this->multichain = new MultichainClient("http://sirius.vanderveer.be:8000", 'multichainrpc', '79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz', 3, false);
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
        $info = $this->multichain->setDebug(true)->getInfo();
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
        $peers = $this->multichain->setDebug(true)->getPeerInfo();
    }

    /**
     * Returns a new address for receiving payments. Omit the account parameter for the default account – see note
     * about accounts.
     *
     * @group address
     */
    public function testGetNewAddress()
    {
        $address = $this->multichain->setDebug(true)->getNewAddress();
    }

    /**
     * Outputs a list of available API commands, including MultiChain-specific commands.
     *
     * @group info
     */
    public function testHelp()
    {
        $help = $this->multichain->setDebug(true)->help();
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
        $this->multichain->setDebug(true)->sendToAddress($address2, array($assetInfo["name"] => 100));
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
        $this->multichain->setDebug(true)->validateAddress($address);
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
        $this->multichain->setDebug(true)->appendRawExchange($hexString, $lock["txid"], $lock["vout"], array($assetInfo["name"] => 10));
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
        $assetInfo1 = $this->createTestAsset($address1);
        $assetInfo2 = $this->createTestAsset($address1);
        $lock = $this->multichain->prepareLockUnspent(array($assetInfo1["name"] => 123));
        $this->multichain->setDebug(true)->createRawExchange($lock["txid"], $lock["vout"], array($assetInfo2["name"] => 100));
    }

    /**
     * Decodes the raw exchange transaction in hexstring, given by a previous call to createrawexchange or
     * appendrawexchange. Returns details on the offer represented by the exchange and its present state. The offer
     * field in the response lists the quantity of native currency and/or assets which are being offered for exchange.
     * The ask field lists the native currency and/or assets which are being asked for. The candisable field specifies
     * whether this wallet can disable the exchange transaction by double-spending against one of its inputs. The
     * cancomplete field specifies whether this wallet has the assets required to complete the exchange. The
     * complete field specifies whether the exchange is already complete (i.e. balanced) and ready for sending. If
     * verbose is true then all of the individual stages in the exchange are listed. Other fields relating to fees are
     * only relevant for blockchains which use a native currency.
     *
     * @group exchange
     */
    public function testDecodeRawExchange(){
        $address1 = $this->multichain->getNewAddress();
        $assetInfo1 = $this->createTestAsset($address1);
        $assetInfo2 = $this->createTestAsset($address1);
        $lock = $this->multichain->prepareLockUnspent(array($assetInfo1["name"] => 123));
        $hexString = $this->multichain->createRawExchange($lock["txid"], $lock["vout"], array($assetInfo2["name"] => 100));
        $this->multichain->setDebug(true)->decodeRawExchange($hexString, true);

    }

    /**
     * Sends a transaction to disable the offer of exchange in hexstring, returning the txid. This is achieved by
     * spending one of the exchange transaction’s inputs and sending it back to the wallet. To check whether this can
     * be used on an exchange transaction, check the candisable field of the output of decoderawexchange.
     *
     * @group exchange
     */
    public function testDisableRawTransaction(){
        $address1 = $this->multichain->getNewAddress();
        $assetInfo1 = $this->createTestAsset($address1);
        $assetInfo2 = $this->createTestAsset($address1);
        $lock = $this->multichain->prepareLockUnspent(array($assetInfo1["name"] => 123));
        $hexString = $this->multichain->createRawExchange($lock["txid"], $lock["vout"], array($assetInfo2["name"] => 100));
        $this->multichain->setDebug(true)->disableRawTransaction($hexString);
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
        $this->multichain->setDebug(true)->prepareLockUnspent(array($assetInfo["name"] => 123));
    }

    /**
     * Adds a metadata output to the raw transaction in tx-hex given by a previous call to createrawtransaction. The
     * metadata is specified in data-hex in hexadecimal form and added in a new OP_RETURN transaction output. The
     * transaction can then be signed and transmitted to the network using signrawtransaction and sendrawtransaction.
     *
     * @group metadata
     */
    public function testAppendRawMetadata(){
        $address1 = $this->multichain->getNewAddress();
        $assetInfo1 = $this->createTestAsset($address1);
        $assetInfo2 = $this->createTestAsset($address1);
        $lock = $this->multichain->prepareLockUnspent(array($assetInfo1["name"] => 123));
        $txHex = $this->multichain->createRawExchange($lock["txid"], $lock["vout"], array($assetInfo2["name"] => 100));
        $this->multichain->setDebug(true)->appendRawMetadata($txHex, "fakemetadata");
    }

    /**
     * Sends transactions to combine large groups of unspent outputs (UTXOs) belonging to the same address into a
     * single unspent output, returning a list of txids. This can improve wallet performance, especially for miners in
     * a chain with short block times and non-zero block rewards. Set addresses to a comma-separated list of addresses
     * to combine outputs for, or * for all addresses in the wallet. Only combine outputs with at least minconf
     * confirmations, and use between mininputs and maxinputs per transaction. A single call to combineunspent can
     * create up to maxcombines transactions over up to maxtime seconds. See also the autocombine runtime parameters.
     *
     * @group operations
     */
    public function testCombineUnspent(){
        //$this->multichain->setDebug(true)->combineUnspent();
    }

    /**
     * Returns a list of all the asset balances for address in this node’s wallet, with at least minconf confirmations.
     * Use includeLocked to include unspent outputs which have been locked, e.g. by a call to preparelockunspent.
     *
     * @group address
     */
    public function testGetAddressBalances(){
        $address1 = $this->multichain->getNewAddress();
        $this->createTestAsset($address1);
        $this->multichain->setDebug(true)->getAddressBalances($address1);
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
