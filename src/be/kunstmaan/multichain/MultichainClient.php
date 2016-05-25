<?php

namespace be\kunstmaan\multichain;

use JsonRPC\Client as JsonRPCClient;

/**
 * Class MultichainClient
 *
 * @package be\kunstmaan\multichain
 * @link http://www.multichain.com/developers/json-rpc-api/
 */
class MultichainClient
{

    /**
     * The JsonRPC client used to call the multichain api
     *
     * @var \JsonRPC\Client
     */
    private $jsonRPCClient;

    /**
     * Default HTTP headers to send to the server
     *
     * @var array
     */
    private $headers = array(
        'User-Agent: Unofficial Multichain PHP Client <https://github.com/kunstmaan/libphp-multichain>',
    );

    /**
     * Enable debug output to the php error log
     *
     * @var boolean
     */
    private $debug = false;

    /**
     * Constructor
     *
     * @param  string $url Multichain JSON RPC url + port
     * @param  string $username Multichain JSON RPC username
     * @param  string $password Multichain JSON RPC password
     * @param  integer $timeout HTTP timeout
     */
    public function __construct($url, $username, $password, $timeout = 3)
    {
        $this->jsonRPCClient = new JsonRPCClient($url);
        $httpClient = $this->jsonRPCClient->getHttpClient();
        $httpClient->withHeaders($this->headers);
        $this->jsonRPCClient->authentication($username, $password);
    }

    /**
     * @param boolean $debug
     * @return MultichainClient
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        if($debug){
            $httpClient = $this->jsonRPCClient->getHttpClient();
            $httpClient->withDebug();
        }
        return $this;
    }

    /**
     * Returns general information about this node and blockchain. MultiChain adds some fields to Bitcoin Core’s
     * response, giving the blockchain’s chainname, description, protocol, peer-to-peer port. The setupblocks field
     * gives the length in blocks of the setup phase in which some consensus constraints are not applied. The
     * nodeaddress can be passed to other nodes for connecting.
     *
     * @return mixed
     */
    public function getInfo()
    {
        return $this->jsonRPCClient->execute("getinfo");
    }

    /**
     * Returns information about the other nodes to which this node is connected. If this is a MultiChain blockchain,
     * includes handshake and handshakelocal fields showing the remote and local address used during the handshaking
     * for that connection.
     *
     * @return mixed
     */
    public function getPeerInfo()
    {
        return $this->jsonRPCClient->execute("getpeerinfo");
    }

    /**
     * Returns a new address for receiving payments. Omit the account parameter for the default account – see note below
     *
     * NOTE:
     * Bitcoin Core has a notion of “accounts”, whereby each address can belong to specific account, which is credited
     * when bitcoin is sent to that address. However the separation of accounts is not preserved when bitcoin is sent
     * out, because the internal accounting mechanism has no relationship to the bitcoin protocol itself. Because of
     * all the confusion this has caused, Bitcoin Core’s accounts mechanism is to be deprecated in future.
     *
     * MultiChain preserves the accounts mechanism and parameters for full backwards compatibility with the Bitcoin
     * Core API. However, because of its forthcoming deprecation, the mechanism is not applied to native asset
     * balances, all of which are considered as belonging to a single global account. Therefore we recommend not using
     * accounts at all with MultiChain, and using "" for any account parameter in the API.
     *
     * To support multiple users in a single MultiChain node’s wallet, call getnewaddress to get a different address
     * for each user. You should then use MultiChain’s *from* APIs, such as sendassetfrom and sendfromaddress, to
     * control whose funds are spent in each transaction. Unlike bitcoin-style accounts, this method maps directly to
     * the blockchain protocol.
     *
     * @param string $account
     * @return mixed
     */
    public function getNewAddress($account = '')
    {
        return $this->jsonRPCClient->execute("getnewaddress", array($account));
    }

    /**
     * Returns the private key associated with address in this node’s wallet. Use with caution – any node with access to this
     * private key can perform any action restricted to the address, including granting permissions and spending funds.
     *
     * @param $address
     * @return mixed
     */
    public function dumpPrivateKey($address){
        return $this->jsonRPCClient->execute("dumpprivkey", array($address));
    }

    /**
     * Adds the privkey private key (as obtained from a prior call to dumpprivkey) to the wallet, together with its
     * associated public address. If rescan is true, the entire blockchain is checked for transactions relating to
     * all addresses in the wallet, including the added one.
     *
     * @param $privkey
     * @param string $label
     * @param bool $rescan
     * @return mixed
     */
    public function importPrivateKey($privkey, $label="", $rescan=true){
        return $this->jsonRPCClient->execute("importprivkey", array($privkey, $label, $rescan));
    }

    /**
     * Sends one or more assets to address, returning the txid. In Bitcoin Core, the amount field is the quantity of
     * the bitcoin currency. For MultiChain, an {"asset":qty, ...} object can be used for amount, in which each asset
     * is an asset name, ref or issuance txid, and each qty is the quantity of that asset to send (see native assets).
     * Use "" as the asset inside this object to specify a quantity of the native blockchain currency. See also
     * sendassettoaddress for sending a single asset and sendfromaddress to control the address whose funds are used.
     *
     * @param string $address
     * @param string $amount
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function sendToAddress($address, $amount, $comment = '', $commentTo = '')
    {
        return $this->jsonRPCClient->execute("sendtoaddress", array($address, $amount, $comment, $commentTo));
    }

    /**
     * Outputs a list of available API commands, including MultiChain-specific commands.
     *
     * @return mixed
     */
    public function help()
    {
        return $this->jsonRPCClient->execute("help");
    }

    /**
     * Adds to the atomic exchange transaction in hexstring given by a previous call to createrawexchange or
     * appendrawexchange. This adds an offer to exchange the asset/s in output vout of transaction txid for qty units
     * of asset, where asset is an asset name, ref or issuance txid. The txid and vout should generally be taken from
     * the response to preparelockunspent or preparelockunspentfrom. Multiple items can be specified within the fourth
     * parameter to request multiple assets. Returns a raw hexadecimal transaction in the hex field alongside a
     * complete field stating whether the exchange is complete (i.e. balanced) or not. If complete, the transaction can
     * be transmitted to the network using sendrawtransaction. If not, it can be passed to a further counterparty, who
     * can call decoderawexchange and appendrawexchange as appropriate.
     *
     * @param $hexString
     * @param $txId
     * @param $vOut
     * @param $extra ({"asset":qty, ...})
     * @return mixed
     */
    public function appendRawExchange($hexString, $txId, $vOut, $extra)
    {
        return $this->jsonRPCClient->execute("appendrawexchange", array($hexString, $txId, $vOut, $extra));
    }

    /**
     * Adds a metadata output to the raw transaction in tx-hex given by a previous call to createrawtransaction. The
     * metadata is specified in data-hex in hexadecimal form and added in a new OP_RETURN transaction output. The
     * transaction can then be signed and transmitted to the network using signrawtransaction and sendrawtransaction.
     *
     * @param $txHex
     * @param $dataHex
     * @return mixed
     */
    public function appendRawMetadata($txHex, $dataHex)
    {
        return $this->jsonRPCClient->execute("appendrawmetadata", array($txHex, $dataHex));
    }

    /**
     * Sends transactions to combine large groups of unspent outputs (UTXOs) belonging to the same address into a
     * single unspent output, returning a list of txids. This can improve wallet performance, especially for miners in
     * a chain with short block times and non-zero block rewards. Set addresses to a comma-separated list of addresses
     * to combine outputs for, or * for all addresses in the wallet. Only combine outputs with at least minconf
     * confirmations, and use between mininputs and maxinputs per transaction. A single call to combineunspent can
     * create up to maxcombines transactions over up to maxtime seconds. See also the autocombine runtime parameters.
     *
     * @param string $addresses
     * @param int $minConf
     * @param int $maxCombines
     * @param int $minInputs
     * @param int $maxInputs
     * @param int $maxTime
     * @return mixed
     */
    public function combineUnspent($addresses = "*", $minConf = 1, $maxCombines = 1, $minInputs = 10, $maxInputs = 100, $maxTime = 30)
    {
        return $this->jsonRPCClient->execute("combineunspent", array($addresses, $minConf, $maxCombines, $minInputs, $maxInputs, $maxTime));
    }

    /**
     * Creates a new atomic exchange transaction which offers to exchange the asset/s in output vout of transaction
     * txid for qty units of asset, where asset is an asset name, ref or issuance txid. The txid and vout should
     * generally be taken from the response to preparelockunspent or preparelockunspentfrom. Multiple items can be
     * specified within the third parameter to request multiple assets. Returns a raw partial transaction in
     * hexadecimal which can be passed to the counterparty, who can call decoderawexchange and appendrawexchange as
     * appropriate.
     *
     * @param $txId
     * @param $vOut
     * @param $extra
     * @return mixed
     */
    public function createRawExchange($txId, $vOut, $extra)
    {
        return $this->jsonRPCClient->execute("createrawexchange", array($txId, $vOut, $extra));
    }

    /**
     * Decodes the raw exchange transaction in hexstring, given by a previous call to createrawexchange or
     * appendrawexchange. Returns details on the offer represented by the exchange and its present state. The offer
     * field in the response lists the quantity of native currency and/or assets which are being offered for exchange.
     * The ask field lists the native currency and/or assets which are being asked for. The candisable field specifies
     * whether this wallet can disable the exchange transaction by double-spending against one of its inputs. The
     * cancomplete field specifies whether this wallet has the assets required to complete the exchange. The complete
     * field specifies whether the exchange is already complete (i.e. balanced) and ready for sending. If verbose is
     * true then all of the individual stages in the exchange are listed. Other fields relating to fees are only
     * relevant for blockchains which use a native currency.
     *
     * @param $hexString
     * @param bool $verbose
     * @return mixed
     */
    public function decodeRawExchange($hexString, $verbose = false)
    {
        return $this->jsonRPCClient->execute("decoderawexchange", array($hexString, $verbose));
    }

    /**
     * Sends a transaction to disable the offer of exchange in hexstring, returning the txid. This is achieved by
     * spending one of the exchange transaction’s inputs and sending it back to the wallet. To check whether this can
     * be used on an exchange transaction, check the candisable field of the output of decoderawexchange.
     *
     * @param $hexString
     * @return mixed
     */
    public function disableRawTransaction($hexString)
    {
        return $this->jsonRPCClient->execute("disablerawtransaction", array($hexString));
    }

    /**
     * Returns a list of all the asset balances for address in this node’s wallet, with at least minconf confirmations.
     * Use includeLocked to include unspent outputs which have been locked, e.g. by a call to preparelockunspent.
     *
     * @param $address
     * @param int $minConf
     * @param bool $includeLocked
     * @return mixed
     */
    public function getAddressBalances($address, $minConf = 1, $includeLocked = false)
    {
        return $this->jsonRPCClient->execute("getaddressbalances", array($address, $minConf, $includeLocked));
    }

    /**
     * Returns a list of addresses in this node’s wallet. Set verbose to true to get more information about each
     * address, formatted like the output of the validateaddress command.
     *
     * @param bool $verbose
     * @return mixed
     */
    public function getAddresses($verbose = false)
    {
        return $this->jsonRPCClient->execute("getaddresses", array($verbose));
    }

    /**
     * Provides information about transaction txid related to address in this node’s wallet, including how it affected
     * that address’s balance. Use verbose to provide details of transaction inputs and outputs.
     *
     * @param $address
     * @param $txId
     * @param bool $verbose
     * @return mixed
     */
    public function getAddressTransaction($address, $txId, $verbose = false)
    {
        return $this->jsonRPCClient->execute("getaddresstransaction", array($address, $txId, $verbose));
    }

    /**
     * Returns a list of all the asset balances for account in this node’s wallet, with at least minconf confirmations.
     * Omit the account parameter or use "" for the default account – see note about accounts. Use includeWatchOnly to
     * include the balance of watch-only addresses and includeLocked to include unspent outputs which have been locked,
     * e.g. by a call to preparelockunspent.
     *
     * @param string $account
     * @param int $minConf
     * @param bool $includeWatchOnly
     * @param bool $includeLocked
     * @return mixed
     */
    public function getAssetBalances($account = "", $minConf = 1, $includeWatchOnly = false, $includeLocked = false)
    {
        return $this->jsonRPCClient->execute("getassetbalances", array($account, $minConf, $includeWatchOnly, $includeLocked));
    }

    /**
     * Returns a list of all the asset balances in this node’s wallet, with at least minconf confirmations. Use
     * includeWatchOnly to include the balance of watch-only addresses and includeLocked to include unspent outputs
     * which have been locked, e.g. by a call to preparelockunspent.
     *
     * @param int $minConf
     * @param bool $includeWatchOnly
     * @param bool $includeLocked
     * @return mixed
     */
    public function getTotalBalances($minConf = 1, $includeWatchOnly = false, $includeLocked = false)
    {
        return $this->jsonRPCClient->execute("getassetbalances", array($minConf, $includeWatchOnly, $includeLocked));
    }

    /**
     * Returns information about address including a check for its validity.
     *
     * @param $address
     * @return mixed
     */
    public function validateAddress($address)
    {
        return $this->jsonRPCClient->execute("validateaddress", array($address));
    }

    /**
     * Provides information about transaction txid in this node’s wallet, including how it affected the node’s total
     * balance. Use includeWatchOnly to consider watch-only addresses as if they belong to this wallet and verbose to
     * provide details of transaction inputs and outputs.
     *
     * @param $txId
     * @param bool $includeWatchOnly
     * @param bool $verbose
     * @return mixed
     */
    public function getWalletTransaction($txId, $includeWatchOnly = false, $verbose = false)
    {
        return $this->jsonRPCClient->execute("getwallettransaction", array($txId, $includeWatchOnly, $verbose));
    }

    /**
     * Grants permissions to addresses, where addresses is a comma-separated list of addresses and permissions is one
     * of connect, send, receive, issue, mine, admin, or a comma-separated list thereof. If the chain uses a native
     * currency, you can send some to each recipient using the native-amount parameter. Returns the txid of the
     * transaction granting the permissions. For more information, see permissions management.
     *
     * @param $addresses
     * @param $permissions
     * @param int $nativeAmount
     * @param string $comment
     * @param string $commentTo
     * @param int $startBlock
     * @param null $endBlock
     * @return mixed
     */
    public function grant($addresses, $permissions, $nativeAmount = 0, $comment = '', $commentTo = '', $startBlock = 0, $endBlock = null)
    {
        return $this->jsonRPCClient->execute("grant", array($addresses, $permissions, $nativeAmount, $comment, $commentTo, $startBlock, $endBlock));
    }

    /**
     * This works like grant, but with control over the from-address used to grant the permissions. If there are
     * multiple addresses with administrator permissions on one node, this allows control over which address is used.
     *
     * @param $fromAddress
     * @param $toAddresses
     * @param $permissions
     * @param int $nativeAmount
     * @param string $comment
     * @param string $commentTo
     * @param int $startBlock
     * @param null $endBlock
     * @return mixed
     */
    public function grantFrom($fromAddress, $toAddresses, $permissions, $nativeAmount = 0, $comment = '', $commentTo = '', $startBlock = 0, $endBlock = null)
    {
        return $this->jsonRPCClient->execute("grantfrom", array($fromAddress, $toAddresses, $permissions, $nativeAmount, $comment, $commentTo, $startBlock, $endBlock));
    }

    /**
     * Creates a new asset name on the blockchain, sending the initial qty units to address. The smallest transactable
     * unit is given by units, e.g. 0.01. If the chain uses a native currency, you can send some with the new asset
     * using the native-amount parameter.
     *
     * @param $address
     * @param $name
     * @param $qty
     * @param int $units
     * @param int $nativeAmount
     * @param null $custom
     * @param bool $open
     * @return mixed
     */
    public function issue($address, $name, $qty, $units = 1, $nativeAmount = 0, $custom = null, $open=false)
    {
        $params = array($address, array('name'=>$name, 'open'=>$open), $qty, $units, $nativeAmount);
        if (!is_null($custom)) {
            $params[] = $custom;
        }
        return $this->jsonRPCClient->execute("issue", $params);
    }

    /**
     * Issues qty additional units of asset, sending them to address. The asset can be specified using its name, ref or
     * issuance txid – see native assets for more information. If the chain uses a native currency, you can send some with the
     * new asset units using the native-amount parameter. Any custom fields will be attached to the new issuance event, and not
     * affect the original values (use listassets with verbose=true to see both sets). Returns the txid of the issuance
     * transaction. For more information, see native assets.
     *
     * @param $address
     * @param $asset
     * @param $qty
     * @param int $nativeAmount
     * @param null $custom
     * @return mixed
     */
    public function issueMore($address, $asset, $qty, $nativeAmount=0, $custom = null){
        $params = array($address, $asset, $qty, $nativeAmount);
        if (!is_null($custom)) {
            $params[] = $custom;
        }
        return $this->jsonRPCClient->execute("issuemore", $params);
    }

    /**
     * This works like issue, but with control over the from-address used to issue the asset. If there are multiple
     * addresses with asset issuing permissions on one node, this allows control over which address is used.
     *
     * @param $fromAddress
     * @param $toAddress
     * @param $name
     * @param $qty
     * @param int $units
     * @param int $nativeAmount
     * @param null $custom
     * @return mixed
     */
    public function issueFrom($fromAddress, $toAddress, $name, $qty, $units = 1, $nativeAmount = 0, $custom = null)
    {
        return $this->jsonRPCClient->execute("issuefrom", array($fromAddress, $toAddress, $name, $qty, $units, $nativeAmount, $custom));
    }

    /**
     * Lists information about the count most recent transactions related to address in this node’s wallet, including
     * how they affected that address’s balance. Use skip to go back further in history and verbose to provide details
     * of transaction inputs and outputs.
     *
     * @param $address
     * @param int $count
     * @param int $skip
     * @param bool $verbose
     * @return mixed
     */
    public function listAddressTransactions($address, $count = 10, $skip = 0, $verbose = false)
    {
        return $this->jsonRPCClient->execute("listaddresstransactions", array($address, $count, $skip, $verbose));
    }

    /**
     * Returns information about all assets issued on the blockchain. If an issuance txid
     * (see native assets) is provided in asset, then information is only returned about that one asset.
     *
     * @param null $asset
     * @return mixed
     */
    public function listAssets($asset = null)
    {
        return $this->jsonRPCClient->execute("listassets", array($asset));
    }

    /**
     * Returns a list of all permissions currently granted to addresses. To list information about specific permissions
     * only, set permissions to one of connect, send, receive, issue, mine, admin, or a comma-separated list thereof.
     * Omit or pass all to list all permissions. Provide a comma-delimited list in addresses to list the permissions
     * for particular addresses only or * for all addresses. If verbose is true, the admins output field lists the
     * administrator/s who assigned the corresponding permission, and the pending field lists permission changes which
     * are waiting to reach consensus.
     *
     * @param string $permissions
     * @param string $addresses
     * @param bool $verbose
     * @return mixed
     */
    public function listPermissions($permissions = "all", $addresses = "*", $verbose = false)
    {
        return $this->jsonRPCClient->execute("listpermissions", array($permissions, $addresses, $verbose));
    }

    /**
     * Lists information about the count most recent transactions in this node’s wallet, including how they affected
     * the node’s total balance. Use skip to go back further in history and includeWatchOnly to consider watch-only
     * addresses as if they belong to this wallet. Use verbose to provide the details of transaction inputs and outputs.
     * Note that unlike Bitcoin Core’s listtransactions command, the response contains one element per transaction,
     * rather than one per transaction output.
     *
     * @param int $count
     * @param int $skip
     * @param bool $includeWatchOnly
     * @param bool $verbose
     * @return mixed
     */
    public function listWalletTransactions($count = 10, $skip = 0, $includeWatchOnly = false, $verbose = false)
    {
        return $this->jsonRPCClient->execute("listwallettransactions", array($count, $skip, $includeWatchOnly, $verbose));
    }

    /**
     * Prepares an unspent transaction output (useful for building atomic exchange transactions) containing qty units
     * of asset, where asset is an asset name, ref or issuance txid. Multiple items can be specified within the first
     * parameter to include several assets within the output. The output will be locked against automatic selection for
     * spending unless the optional lock parameter is set to false. Returns the txid and vout of the prepared output.
     *
     * @param $assetsToLock
     * @param bool $lock
     * @return mixed
     */
    public function prepareLockUnspent($assetsToLock, $lock = true)
    {
        return $this->jsonRPCClient->execute("preparelockunspent", array($assetsToLock, $lock));
    }

    /**
     * This works like preparelockunspent, but with control over the from-address whose funds are used to prepare the
     * unspent transaction output. Any change from the transaction is send back to from-address.
     *
     * @param $fromAddress
     * @param $assetsToLock
     * @param bool $lock
     * @return mixed
     */
    public function prepareLockUnspentFrom($fromAddress, $assetsToLock, $lock = true)
    {
        return $this->jsonRPCClient->execute("preparelockunspentfrom", array($fromAddress, $assetsToLock, $lock));
    }

    /**
     * Revokes permissions from addresses, where addresses is a comma-separated list of addresses and permissions is
     * one of connect, send, receive, issue, mine, admin, or a comma-separated list thereof. Equivalent to calling
     * grant with start-block=0 and end-block=0. Returns the txid of transaction revoking the permissions. For more
     * information, see permissions management.
     *
     * @param $addresses
     * @param $permissions
     * @param int $nativeAmount
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function revoke($addresses, $permissions, $nativeAmount = 0, $comment = '', $commentTo = '')
    {
        return $this->jsonRPCClient->execute("revoke", array($addresses, $permissions, $nativeAmount, $comment, $commentTo));
    }

    /**
     * This works like revoke, but with control over the from-address used to revoke the permissions. If there are
     * multiple addresses with administrator permissions on one node, this allows control over which address is used.
     *
     * @param $fromAddress
     * @param $toAddresses
     * @param $permissions
     * @param int $nativeAmount
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function revokeFrom($fromAddress, $toAddresses, $permissions, $nativeAmount = 0, $comment = '', $commentTo = '')
    {
        return $this->jsonRPCClient->execute("revokefrom", array($fromAddress, $toAddresses, $permissions, $nativeAmount, $comment, $commentTo));
    }

    /**
     * This works like sendassettoaddress, but with control over the from-address whose funds are used. Any change from
     * the transaction is sent back to from-address. See also sendfromaddress for sending multiple assets in one
     * transaction.
     *
     * @param $fromAddress
     * @param $toAddress
     * @param $asset
     * @param $qty
     * @param null $nativeAmount
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function sendAssetFrom($fromAddress, $toAddress, $asset, $qty, $nativeAmount = null, $comment = '', $commentTo = '')
    {
        $nativeAmount = $this->findDefaultMinimumPerOutput($nativeAmount);
        return $this->jsonRPCClient->execute("sendassetfrom", array($fromAddress, $toAddress, $asset, $qty, $nativeAmount, $comment, $commentTo));
    }

    /**
     * Returns a list of all the parameters of this blockchain, reflecting the content of its params.dat file.
     *
     * @return mixed
     */
    public function getBlockchainParams()
    {
        return $this->jsonRPCClient->execute("getblockchainparams");
    }

    /**
     * Sends qty of asset to address, returning the txid. The asset can be specified using its name, ref or issuance
     * txid – see native assets for more information. See also sendassetfrom to control the address whose funds are
     * used, sendtoaddress for sending multiple assets in one transaction, and sendfromaddress to combine both of these.
     *
     * @param $address
     * @param $asset
     * @param $qty
     * @param null $nativeAmount
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function sendAssetToAddress($address, $asset, $qty, $nativeAmount = null, $comment = '', $commentTo = '')
    {
        $nativeAmount = $this->findDefaultMinimumPerOutput($nativeAmount);
        return $this->jsonRPCClient->execute("sendassettoaddress", array($address, $asset, $qty, $nativeAmount, $comment, $commentTo));
    }

    /**
     * This works like sendtoaddress, but with control over the from-address whose funds are used. Any
     * change from the transaction is sent back to from-address.
     *
     * @param $fromAddress
     * @param $toAddress
     * @param $amount
     * @param string $comment
     * @param string $commentTo
     * @return mixed
     */
    public function sendFromAddress($fromAddress, $toAddress, $amount, $comment = '', $commentTo = '')
    {
        return $this->jsonRPCClient->execute("sendfromaddress", array($fromAddress, $toAddress, $amount, $comment, $commentTo));
    }

    /**
     * This works like sendtoaddress (listed above), but includes the data-hex hexadecimal metadata in an additional
     * OP_RETURN transaction output.
     *
     * @param $address
     * @param $amount
     * @param $dataHex
     * @return mixed
     */
    public function sendWithMetadata($address, $amount, $dataHex)
    {
        return $this->jsonRPCClient->execute("sendwithmetadata", array($address, $amount, $dataHex));
    }

    /**
     * This works like sendtoaddress (listed above), but with control over the from-address whose funds are used, and
     * with the data-hex hexadecimal metadata added in an additional OP_RETURN transaction output. Any change from the
     * transaction is sent back to from-address.
     *
     * @param $fromAddress
     * @param $toAddress
     * @param $amount
     * @param $dataHex
     * @return mixed
     */
    public function sendWithMetadataFrom($fromAddress, $toAddress, $amount, $dataHex)
    {
        return $this->jsonRPCClient->execute("sendwithmetadatafrom", array($fromAddress, $toAddress, $amount, $dataHex));
    }

    /**
     * Creates a transaction spending the specified inputs, sending to the given addresses. In Bitcoin Core, each
     * amount field is a quantity of the bitcoin currency. For MultiChain, an {"asset":qty, ...} object can be used for
     * amount, in which each asset is an asset name, ref or issuance txid, and each qty is the quantity of that asset
     * to send (see native assets). Use "" as the asset inside this object to specify a quantity of the native
     * blockchain currency.
     *
     * @param $inputs
     * @param $addresses
     * @return mixed
     */
    public function createRawTransaction($inputs, $addresses)
    {
        return $this->jsonRPCClient->execute("createrawtransaction", array($inputs, $addresses));
    }

    /**
     * Returns a JSON object describing the serialized transaction in hexstring. For a MultiChain blockchain, each
     * transaction output includes assets and permissions fields listing any assets or permission changes encoded
     * within that output. There will also be a data field listing the content of any OP_RETURN outputs in the
     * transaction.
     *
     * @param $hexString
     * @return mixed
     */
    public function decodeRawTransaction($hexString)
    {
        return $this->jsonRPCClient->execute("decoderawtransaction", array($hexString));
    }

    /**
     * Returns information about the block with hash. If this is a MultiChain blockchain and format is true or omitted,
     * then the output includes a field miner showing the address of the miner of the block.
     *
     * @param $hash
     * @param bool $format
     * @return mixed
     */
    public function getBlock($hash, $format = true)
    {
        return $this->jsonRPCClient->execute("getblock", array($hash, $format));
    }

    /**
     * If verbose is 1, returns a JSON object describing transaction txid. For a MultiChain blockchain, each transaction
     * output includes assets and permissions fields listing any assets or permission changes encoded within that
     * output. There will also be a data field listing the content of any OP_RETURN outputs in the transaction.
     *
     * @param $txId
     * @param int $verbose
     * @return mixed
     */
    public function getRawTransaction($txId, $verbose = 0)
    {
        return $this->jsonRPCClient->execute("getrawtransaction", array($txId, $verbose));
    }

    /**
     * Returns details about an unspent transaction output vout of txid. For a MultiChain blockchain, includes assets
     * and permissions fields listing any assets or permission changes encoded within the output. Set confirmed to true
     * to include unconfirmed transaction outputs.
     *
     * @param $txId
     * @param $vOut
     * @param bool $unconfirmed
     * @return mixed
     */
    public function getTxOut($txId, $vOut, $unconfirmed = false)
    {
        return $this->jsonRPCClient->execute("gettxout", array($txId, $vOut, $unconfirmed));
    }

    /**
     * Returns a list of unspent transaction outputs in the wallet, with between minconf and maxconf confirmations. For
     * a MultiChain blockchain, each transaction output includes assets and permissions fields listing any assets or
     * permission changes encoded within that output. If addresses is provided, only outputs which pay an address in
     * this array will be included.
     *
     * @param int $minConf
     * @param int $maxConf
     * @param null $addresses
     * @return mixed
     */
    public function listUnspent($minConf = 1, $maxConf = 999999, $addresses = null)
    {
        return $this->jsonRPCClient->execute("listunspent", array($minConf, $maxConf, $addresses));
    }

    /**
     * @param $nativeAmount
     * @return mixed
     */
    private function findDefaultMinimumPerOutput($nativeAmount)
    {
        if (is_null($nativeAmount)) {
            $blockchainParams = $this->getBlockchainParams();
            $nativeAmount = $blockchainParams["minimum-per-output"];
            return $nativeAmount;
        }
        return $nativeAmount;
    }

    /**
     * Submits raw transaction (serialized, hex-encoded) to local node and network.
     * Returns the transaction hash in hex
     *
     * @param $hex
     * @param bool $allowHighFees
     * @return mixed
     */
    public function sendRawTransaction($hex, $allowHighFees = false)
    {
        return $this->jsonRPCClient->execute("sendrawtransaction", array($hex, $allowHighFees));
    }
}
