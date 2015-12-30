<?php

namespace be\kunstmaan\multichain;

use be\kunstmaan\multichain\entities\Address;
use be\kunstmaan\multichain\entities\Asset;
use be\kunstmaan\multichain\entities\Info;
use be\kunstmaan\multichain\entities\PeerInfo;
use JsonRPC\Client as JsonRPCClient;

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
        'User-Agent: Unofficial Multichain PHP Client <https://github.com/kunstmaan/php-multichain>',
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
    public function __construct($url, $username, $password, $timeout = 3, $debug = false)
    {
        $this->jsonRPCClient = new JsonRPCClient($url, $timeout, $this->headers);
        $this->debug = $debug;
        $this->jsonRPCClient->debug = $debug;
        $this->jsonRPCClient->authentication($username, $password);
    }

    /**
     * Returns general information about this node and blockchain. MultiChain adds some fields to Bitcoin Core’s
     * response, giving the blockchain’s chainname, description, protocol, peer-to-peer port. The setupblocks field
     * gives the length in blocks of the setup phase in which some consensus constraints are not applied. The
     * nodeaddress can be passed to other nodes for connecting.
     *
     * @link http://www.multichain.com/developers/json-rpc-api/
     */
    public function getInfo()
    {
        $response = $this->jsonRPCClient->execute("getinfo");
        $info = new Info();
        $info->setVersion($response["version"])
            ->setProtocolVersion($response["protocolversion"])
            ->setChainName($response["chainname"])
            ->setDescription($response["description"])
            ->setProtocol($response["protocol"])
            ->setPort($response["port"])
            ->setSetupBlocks($response["setupblocks"])
            ->setNodeAddress($response["nodeaddress"])
            ->setWalletVersion($response["walletversion"])
            ->setBalance($response["balance"])
            ->setBlocks($response["blocks"])
            ->setTimeOffset($response["timeoffset"])
            ->setConnections($response["connections"])
            ->setProxy($response["proxy"])
            ->setDifficulty($response["difficulty"])
            ->setTestNet($response["testnet"])
            ->setKeyPoolOldest($response["keypoololdest"])
            ->setKeyPoolSize($response["keypoolsize"])
            ->setPayTxFee($response["paytxfee"])
            ->setRelayFee($response["relayfee"])
            ->setErrors($response["errors"]);
        return $info;
    }

    /**
     * Returns information about the other nodes to which this node is connected. If this is a MultiChain blockchain,
     * includes handshake and handshakelocal fields showing the remote and local address used during the handshaking
     * for that connection.
     *
     * @return PeerInfo[]
     * @link http://www.multichain.com/developers/json-rpc-api/
     */
    public function getPeerInfo()
    {
        $response = $this->jsonRPCClient->execute("getpeerinfo");
        $peers = array();
        foreach ($response as $peer) {
            $peerInfo = new PeerInfo();
            $peerInfo->setId($peer["id"])
                ->setAddr($peer["addr"])
                ->setServices($peer["services"])
                ->setLastSend($peer["lastsend"])
                ->setLastRecv($peer["lastrecv"])
                ->setBytesSent($peer["bytessent"])
                ->setBytesRecv($peer["bytesrecv"])
                ->setConnTime($peer["conntime"])
                ->setPingTime($peer["pingtime"])
                ->setVersion($peer["version"])
                ->setSubVer($peer["subver"])
                ->setHandshakeLocal($peer["handshakelocal"])
                ->setHandshake($peer["handshake"])
                ->setInbound($peer["inbound"])
                ->setStartingHeight($peer["startingheight"])
                ->setBanScore($peer["banscore"])
                ->setSyncedHeaders($peer["synced_headers"])
                ->setSyncedBlocks($peer["synced_blocks"])
                ->setInFlight($peer["inflight"])
                ->setWhiteListed($peer["whitelisted"]);
            $peers[] = $peerInfo;
        }
        return $peers;
    }

    /**
     * Returns a new address for receiving payments. The account parameter is ommitted by default.
     *
     * The account parameter is ommitted by default because:
     *
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
     * @link http://www.multichain.com/developers/json-rpc-api/
     */
    public function getNewAddress($verbose = true)
    {
        $response = $this->jsonRPCClient->execute("getnewaddress");
        $address = new Address();
        $address->setHash($response);
        if ($verbose) {
            $address = $this->validateAddress($address);
        }
        return $address;
    }

    /**
     * Sends one or more assets to address, returning the txid. In Bitcoin Core, the amount field is the quantity of
     * the bitcoin currency. For MultiChain, an {"asset":qty, ...} object can be used for amount, in which each asset
     * is an asset name, ref or issuance txid, and each qty is the quantity of that asset to send (see native assets).
     * Use "" as the asset inside this object to specify a quantity of the native blockchain currency. See also
     * sendassettoaddress for sending a single asset and sendfromaddress to control the address whose funds are used.
     *
     * @param Address $address
     * @param $amount
     * @param string $comment
     * @param string $commentTo
     */
    public function sendToAddress(Address $address, $amount, $comment = '', $commentTo = '')
    {
        // TODO
    }

    /**
     * Outputs a list of available API commands, including MultiChain-specific commands.
     */
    public function help()
    {
        // TODO
    }

    /**
     * Shuts down the this blockchain node, i.e. stops the multichaind process.
     */
    public function stop()
    {
        // TODO
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
     * @param $hexstring
     * @param $txid
     * @param $vout
     * @param $extra
     */
    public function appendRawExchange($hexstring, $txid, $vout, $extra)
    {
        // TODO
    }

    /**
     * Adds a metadata output to the raw transaction in tx-hex given by a previous call to createrawtransaction. The
     * metadata is specified in data-hex in hexadecimal form and added in a new OP_RETURN transaction output. The
     * transaction can then be signed and transmitted to the network using signrawtransaction and sendrawtransaction.
     *
     * @param $txHex
     * @param $dataHex
     */
    public function appendRawMetadata($txHex, $dataHex)
    {
        // TODO
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
     */
    public function combineUnspent($addresses = "*", $minConf = 1, $maxCombines = 1, $minInputs = 10, $maxInputs = 100, $maxTime = 30)
    {
        // TODO
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
     */
    public function createRawExchange($txId, $vOut, $extra)
    {
        // TODO
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
     */
    public function decodeRawExchange($hexString, $verbose = false)
    {
        // TODO
    }

    /**
     * Sends a transaction to disable the offer of exchange in hexstring, returning the txid. This is achieved by
     * spending one of the exchange transaction’s inputs and sending it back to the wallet. To check whether this can
     * be used on an exchange transaction, check the candisable field of the output of decoderawexchange.
     *
     * @param $hexString
     */
    public function disablerawtransaction($hexString)
    {
        // TODO
    }


    /**
     * Returns a list of all the asset balances for address in this node’s wallet, with at least minconf confirmations.
     * Use includeLocked to include unspent outputs which have been locked, e.g. by a call to preparelockunspent.
     *
     * @param $address
     * @param int $minConf
     * @param bool $includeLocked
     */
    public function getAddressBalances($address, $minConf = 1, $includeLocked = false)
    {
        // TODO
    }

    /**
     * Returns a list of addresses in this node’s wallet. Set verbose to true to get more information about each
     * address, formatted like the output of the validateaddress command.
     *
     * @param bool $verbose
     * @return Address[]
     */
    public function getAddresses($verbose = true)
    {
        $response = $this->jsonRPCClient->execute("getaddresses", array($verbose));
        $addresses = array();
        foreach ($response as $addr) {
            $address = new Address();
            if (is_array($addr)) {
                $address->setHash($addr["address"])
                    ->setMine($addr["ismine"])
                    ->setWatchOnly($addr["iswatchonly"])
                    ->setScript($addr["isscript"])
                    ->setPubKey($addr["pubkey"])
                    ->setCompressed($addr["iscompressed"]);
            } else {
                $address->setHash($addr);
            }
            $addresses[] = $address;
        }
        return $addresses;
    }

    /**
     * Provides information about transaction txid related to address in this node’s wallet, including how it affected
     * that address’s balance. Use verbose to provide details of transaction inputs and outputs.
     *
     * @param $address
     * @param $txId
     * @param bool $verbose
     */
    public function getAddressTransaction($address, $txId, $verbose = false)
    {
        // TODO
    }

    /**
     * Returns a list of all the asset balances for account in this node’s wallet, with at least minconf confirmations.
     * Omit the account parameter or use "" for the default account – see note about accounts. Use includeWatchOnly to
     * include the balance of watch-only addresses and includeLocked to include unspent outputs which have been locked,
     * e.g. by a call to preparelockunspent.
     *
     * @param string $account
     * @param int $minconf
     * @param bool $includeWatchOnly
     * @param bool $includeLocked
     */
    public function getAssetBalances($account = "", $minconf = 1, $includeWatchOnly = false, $includeLocked = false)
    {
        // TODO
    }

    /**
     * Returns a list of all the parameters of this blockchain, reflecting the content of its params.dat file.
     */
    public function getBlockchainParams()
    {
        // TODO
    }

    /**
     * Returns a list of all the asset balances in this node’s wallet, with at least minconf confirmations. Use
     * includeWatchOnly to include the balance of watch-only addresses and includeLocked to include unspent outputs
     * which have been locked, e.g. by a call to preparelockunspent.
     *
     * @param int $minconf
     * @param bool $includeWatchOnly
     * @param bool $includeLocked
     */
    public function getTotalBalances($minconf = 1, $includeWatchOnly = false, $includeLocked = false)
    {
        // TODO
    }

    /**
     * Returns information about address including a check for its validity.
     *
     * @param Address $address
     * @return Address
     */
    public function validateAddress(Address $address)
    {
        $response = $this->jsonRPCClient->execute("validateaddress", array($address->getHash()));
        $address->setValid($response["isvalid"])
            ->setMine($response["ismine"])
            ->setWatchOnly($response["iswatchonly"])
            ->setScript($response["isscript"])
            ->setPubKey($response["pubkey"])
            ->setCompressed($response["iscompressed"]);
        return $address;
    }

    /**
     * Creates a new asset name on the blockchain, sending the initial qty units to address. The smallest transactable
     * unit is given by units, e.g. 0.01. If the chain uses a native currency, you can send some with the new asset
     * using the native-amount parameter.
     *
     * @param Address $address
     * @param $name
     * @param $qty
     * @param int $units
     * @param int $nativeAmount
     * @param null $custom
     * @return Asset
     */
    public function issue(Address $address, $name, $qty, $units = 1, $nativeAmount = 0, $custom = null)
    {
        $params = array($address->getHash(), $name, $qty, $units, $nativeAmount);
        if (!is_null($custom)) {
            $params[] = $custom;
        }
        $response = $this->jsonRPCClient->execute("issue", $params);
        $asset = new Asset();
        $asset->setIssueTxId($response);
        $moreinfo = $this->listAssets($asset);
        if (sizeof($moreinfo) > 0) {
            $asset = $moreinfo[0];
        }
        return $asset;
    }


    /**
     * Returns information about all assets issued on the blockchain. If an issuance txid
     * (see native assets) is provided in asset, then information is only returned about that one asset.
     *
     * @param Asset|null $asset
     * @return Asset[]
     */
    public function listAssets(Asset $asset = null)
    {
        $response = (is_null($asset) ? $this->jsonRPCClient->execute("listassets") : $this->jsonRPCClient->execute("listassets", array($asset->getIssueTxId())));
        $assets = array();
        foreach ($response as $asst) {
            $asset = new Asset();
            $asset->setIssueTxId($asst["issuetxid"])
                ->setAssetRef($asst["assetref"])
                ->setDetails($asst["details"])
                ->setIssueQty($asst["issueqty"])
                ->setIssueRaw($asst["issueraw"])
                ->setMultiple($asst["multiple"])
                ->setName($asst["name"])
                ->setUnits($asst["units"]);
            $assets[] = $asset;
        }
        return $assets;
    }


    // Lists information about the count most recent transactions in this node’s wallet, including how they affected the node’s total balance. Use skip to go back further in history and includeWatchOnly to consider watch-only addresses as if they belong to this wallet. Use verbose to provide the details of transaction inputs and outputs. Note that unlike Bitcoin Core’s listtransactions command, the response contains one element per transaction, rather than one per transaction output.

    public function listWalletTransactions($count = 10, $skip = 0, $includeWatchOnly = false, $verbose = true)
    {
        $response = $this->jsonRPCClient->execute("listwallettransactions", array($count, $skip, $includeWatchOnly, $verbose));
    }
}