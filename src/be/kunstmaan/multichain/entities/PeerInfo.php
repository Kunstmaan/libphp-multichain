<?php


namespace be\kunstmaan\multichain\entities;


class PeerInfo
{

    /** @var int */
    private $id; // int(2)
    /** @var string */
    private $addr; // string(19) "192.168.50.12:44898"
    /** @var string */
    private $services; // string(16) "0000000000000001"
    /** @var int */
    private $lastSend; // int(1451118917)
    /** @var int */
    private $lastRecv; // int(1451118917)
    /** @var int */
    private $bytesSent; // int(93361)
    /** @var int */
    private $bytesRecv; // int(134931)
    /** @var int */
    private $connTime; // int(1451116145)
    /** @var float */
    private $pingTime; // float(0.086241)
    /** @var int */
    private $version; // int(70002)
    /** @var string */
    private $subVer; // string(20) "/Multichain:0.1.0.2/"
    /** @var string */
    private $handshakeLocal; // NULL
    /** @var string */
    private $handshake; // NULL
    /** @var bool */
    private $inbound; // bool(true)
    /** @var int */
    private $startingHeight; // int(0)
    /** @var int */
    private $banScore; // int(0)
    /** @var int */
    private $syncedHeaders; // int(-1)
    /** @var int */
    private $syncedBlocks; // int(-1)
    /** @var array */
    private $inFlight; // array(0)
    /** @var bool */
    private $whiteListed; // bool(false)

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return PeerInfo
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * @param string $addr
     * @return PeerInfo
     */
    public function setAddr($addr)
    {
        $this->addr = $addr;
        return $this;
    }

    /**
     * @return string
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param string $services
     * @return PeerInfo
     */
    public function setServices($services)
    {
        $this->services = $services;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastSend()
    {
        return $this->lastSend;
    }

    /**
     * @param int $lastSend
     * @return PeerInfo
     */
    public function setLastSend($lastSend)
    {
        $this->lastSend = $lastSend;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastRecv()
    {
        return $this->lastRecv;
    }

    /**
     * @param int $lastRecv
     * @return PeerInfo
     */
    public function setLastRecv($lastRecv)
    {
        $this->lastRecv = $lastRecv;
        return $this;
    }

    /**
     * @return int
     */
    public function getBytesSent()
    {
        return $this->bytesSent;
    }

    /**
     * @param int $bytesSent
     * @return PeerInfo
     */
    public function setBytesSent($bytesSent)
    {
        $this->bytesSent = $bytesSent;
        return $this;
    }

    /**
     * @return int
     */
    public function getBytesRecv()
    {
        return $this->bytesRecv;
    }

    /**
     * @param int $bytesRecv
     * @return PeerInfo
     */
    public function setBytesRecv($bytesRecv)
    {
        $this->bytesRecv = $bytesRecv;
        return $this;
    }

    /**
     * @return int
     */
    public function getConnTime()
    {
        return $this->connTime;
    }

    /**
     * @param int $connTime
     * @return PeerInfo
     */
    public function setConnTime($connTime)
    {
        $this->connTime = $connTime;
        return $this;
    }

    /**
     * @return float
     */
    public function getPingTime()
    {
        return $this->pingTime;
    }

    /**
     * @param float $pingTime
     * @return PeerInfo
     */
    public function setPingTime($pingTime)
    {
        $this->pingTime = $pingTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     * @return PeerInfo
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubVer()
    {
        return $this->subVer;
    }

    /**
     * @param string $subVer
     * @return PeerInfo
     */
    public function setSubVer($subVer)
    {
        $this->subVer = $subVer;
        return $this;
    }

    /**
     * @return string
     */
    public function getHandshakeLocal()
    {
        return $this->handshakeLocal;
    }

    /**
     * @param string $handshakeLocal
     * @return PeerInfo
     */
    public function setHandshakeLocal($handshakeLocal)
    {
        $this->handshakeLocal = $handshakeLocal;
        return $this;
    }

    /**
     * @return string
     */
    public function getHandshake()
    {
        return $this->handshake;
    }

    /**
     * @param string $handshake
     * @return PeerInfo
     */
    public function setHandshake($handshake)
    {
        $this->handshake = $handshake;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isInbound()
    {
        return $this->inbound;
    }

    /**
     * @param boolean $inbound
     * @return PeerInfo
     */
    public function setInbound($inbound)
    {
        $this->inbound = $inbound;
        return $this;
    }

    /**
     * @return int
     */
    public function getStartingHeight()
    {
        return $this->startingHeight;
    }

    /**
     * @param int $startingHeight
     * @return PeerInfo
     */
    public function setStartingHeight($startingHeight)
    {
        $this->startingHeight = $startingHeight;
        return $this;
    }

    /**
     * @return int
     */
    public function getBanScore()
    {
        return $this->banScore;
    }

    /**
     * @param int $banScore
     * @return PeerInfo
     */
    public function setBanScore($banScore)
    {
        $this->banScore = $banScore;
        return $this;
    }

    /**
     * @return int
     */
    public function getSyncedHeaders()
    {
        return $this->syncedHeaders;
    }

    /**
     * @param int $syncedHeaders
     * @return PeerInfo
     */
    public function setSyncedHeaders($syncedHeaders)
    {
        $this->syncedHeaders = $syncedHeaders;
        return $this;
    }

    /**
     * @return int
     */
    public function getSyncedBlocks()
    {
        return $this->syncedBlocks;
    }

    /**
     * @param int $syncedBlocks
     * @return PeerInfo
     */
    public function setSyncedBlocks($syncedBlocks)
    {
        $this->syncedBlocks = $syncedBlocks;
        return $this;
    }

    /**
     * @return array
     */
    public function getInFlight()
    {
        return $this->inFlight;
    }

    /**
     * @param array $inFlight
     * @return PeerInfo
     */
    public function setInFlight($inFlight)
    {
        $this->inFlight = $inFlight;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isWhiteListed()
    {
        return $this->whiteListed;
    }

    /**
     * @param boolean $whiteListed
     * @return PeerInfo
     */
    public function setWhiteListed($whiteListed)
    {
        $this->whiteListed = $whiteListed;
        return $this;
    }

}