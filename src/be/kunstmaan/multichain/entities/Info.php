<?php


namespace be\kunstmaan\multichain\entities;


class Info
{

    /** @var string */
    private $version; // string(12) "1.0 alpha 13"
    /** @var int */
    private $protocolVersion; // int(10002)
    /** @var string */
    private $chainName; // string(9) "testchain"
    /** @var string */
    private $description; // string(28) "Multichain network testchain"
    /** @var string */
    private $protocol; // string(10) "multichain"
    /** @var int */
    private $port; // int(7447)
    /** @var int */
    private $setupBlocks; // int(60)
    /** @var string */
    private $nodeAddress; // string(24) "testchain@10.0.2.15:7447"
    /** @var int */
    private $walletVersion; // int(60000)
    /** @var float */
    private $balance; // float(0)
    /** @var int */
    private $blocks; // int(51)
    /** @var int */
    private $timeOffset; // int(0)
    /** @var int */
    private $connections; // int(2)
    /** @var string */
    private $proxy; // string(0) ""
    /** @var float */
    private $difficulty; // float(1.526E-5)
    /** @var bool */
    private $testNet; // bool(false)
    /** @var int */
    private $keyPoolOldest; // int(1451116090)
    /** @var int */
    private $keyPoolSize; // int(2)
    /** @var float */
    private $payTxFee; // float(0)
    /** @var float */
    private $relayFee; // float(0)
    /** @var string */
    private $errors;  // string(0) ""

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return Info
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return int
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @param int $protocolVersion
     * @return Info
     */
    public function setProtocolVersion($protocolVersion)
    {
        $this->protocolVersion = $protocolVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getChainName()
    {
        return $this->chainName;
    }

    /**
     * @param string $chainName
     * @return Info
     */
    public function setChainName($chainName)
    {
        $this->chainName = $chainName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Info
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     * @return Info
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return Info
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return int
     */
    public function getSetupBlocks()
    {
        return $this->setupBlocks;
    }

    /**
     * @param int $setupBlocks
     * @return Info
     */
    public function setSetupBlocks($setupBlocks)
    {
        $this->setupBlocks = $setupBlocks;
        return $this;
    }

    /**
     * @return string
     */
    public function getNodeAddress()
    {
        return $this->nodeAddress;
    }

    /**
     * @param string $nodeAddress
     * @return Info
     */
    public function setNodeAddress($nodeAddress)
    {
        $this->nodeAddress = $nodeAddress;
        return $this;
    }

    /**
     * @return int
     */
    public function getWalletVersion()
    {
        return $this->walletVersion;
    }

    /**
     * @param int $walletVersion
     * @return Info
     */
    public function setWalletVersion($walletVersion)
    {
        $this->walletVersion = $walletVersion;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     * @return Info
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return int
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param int $blocks
     * @return Info
     */
    public function setBlocks($blocks)
    {
        $this->blocks = $blocks;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeOffset()
    {
        return $this->timeOffset;
    }

    /**
     * @param int $timeOffset
     * @return Info
     */
    public function setTimeOffset($timeOffset)
    {
        $this->timeOffset = $timeOffset;
        return $this;
    }

    /**
     * @return int
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * @param int $connections
     * @return Info
     */
    public function setConnections($connections)
    {
        $this->connections = $connections;
        return $this;
    }

    /**
     * @return string
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @param string $proxy
     * @return Info
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @return float
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * @param float $difficulty
     * @return Info
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTestNet()
    {
        return $this->testNet;
    }

    /**
     * @param boolean $testNet
     * @return Info
     */
    public function setTestNet($testNet)
    {
        $this->testNet = $testNet;
        return $this;
    }

    /**
     * @return int
     */
    public function getKeyPoolOldest()
    {
        return $this->keyPoolOldest;
    }

    /**
     * @param int $keyPoolOldest
     * @return Info
     */
    public function setKeyPoolOldest($keyPoolOldest)
    {
        $this->keyPoolOldest = $keyPoolOldest;
        return $this;
    }

    /**
     * @return int
     */
    public function getKeyPoolSize()
    {
        return $this->keyPoolSize;
    }

    /**
     * @param int $keyPoolSize
     * @return Info
     */
    public function setKeyPoolSize($keyPoolSize)
    {
        $this->keyPoolSize = $keyPoolSize;
        return $this;
    }

    /**
     * @return float
     */
    public function getPayTxFee()
    {
        return $this->payTxFee;
    }

    /**
     * @param float $payTxFee
     * @return Info
     */
    public function setPayTxFee($payTxFee)
    {
        $this->payTxFee = $payTxFee;
        return $this;
    }

    /**
     * @return float
     */
    public function getRelayFee()
    {
        return $this->relayFee;
    }

    /**
     * @param float $relayFee
     * @return Info
     */
    public function setRelayFee($relayFee)
    {
        $this->relayFee = $relayFee;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $errors
     * @return Info
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

}
