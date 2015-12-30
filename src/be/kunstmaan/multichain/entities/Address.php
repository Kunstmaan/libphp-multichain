<?php


namespace be\kunstmaan\multichain\entities;


class Address
{

    /** @var string */
    private $hash; // string(38) "1Rt2UA23H5A8z7cSE7DeXQMwgYBZneGR4xd6EW"
    /** @var bool */
    private $valid; // bool(true)
    /** @var  bool */
    private $mine; // bool(true)
    /** @var bool */
    private $watchOnly; // bool(false)
    /** @var bool */
    private $script; // bool(false)
    /** @var string */
    private $pubKey; // string(66) "0356dab1b7c2d819d1e732ab5cbb51e25e94e83c9b12e96ea6a89c265d855e5467"
    /** @var bool */
    private $compressed; // bool(true)


    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return Address
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        if ($this->valid === null){
            throw new \RuntimeException("the valid attribute is not hydrated yet, please run the address through the validateAddress method");
        }
        return $this->valid;
    }

    /**
     * @param boolean $valid
     * @return Address
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isMine()
    {
        return $this->mine;
    }

    /**
     * @param boolean $mine
     * @return Address
     */
    public function setMine($mine)
    {
        $this->mine = $mine;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isWatchOnly()
    {
        return $this->watchOnly;
    }

    /**
     * @param boolean $watchOnly
     * @return Address
     */
    public function setWatchOnly($watchOnly)
    {
        $this->watchOnly = $watchOnly;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isScript()
    {
        return $this->script;
    }

    /**
     * @param boolean $script
     * @return Address
     */
    public function setScript($script)
    {
        $this->script = $script;
        return $this;
    }

    /**
     * @return string
     */
    public function getPubKey()
    {
        return $this->pubKey;
    }

    /**
     * @param string $pubKey
     * @return Address
     */
    public function setPubKey($pubKey)
    {
        $this->pubKey = $pubKey;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isCompressed()
    {
        return $this->compressed;
    }

    /**
     * @param boolean $compressed
     * @return Address
     */
    public function setCompressed($compressed)
    {
        $this->compressed = $compressed;
        return $this;
    }

}