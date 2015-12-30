<?php


namespace be\kunstmaan\multichain\entities;


class Asset
{

    /** @var string */
    private $name; // "bxzcm",
    /** @var string */
    private $issueTxId; // "4b6d550d35e8e6d6d1c4385b0e335fc9e5d18c91d48d2460d7bcf762f4991c02",
    /** @var string */
    private $assetRef; // null,
    /** @var int */
    private $multiple; // 1000,
    /** @var float */
    private $units; // 0.001,
    /** @var array */
    private $details; // [],
    /** @var float */
    private $issueQty; // 100,
    /** @var int */
    private $issueRaw; // 100000

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Asset
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getIssueTxId()
    {
        return $this->issueTxId;
    }

    /**
     * @param string $issueTxId
     * @return Asset
     */
    public function setIssueTxId($issueTxId)
    {
        $this->issueTxId = $issueTxId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAssetRef()
    {
        return $this->assetRef;
    }

    /**
     * @param string $assetRef
     * @return Asset
     */
    public function setAssetRef($assetRef)
    {
        $this->assetRef = $assetRef;
        return $this;
    }

    /**
     * @return int
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * @param int $multiple
     * @return Asset
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
        return $this;
    }

    /**
     * @return float
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param float $units
     * @return Asset
     */
    public function setUnits($units)
    {
        $this->units = $units;
        return $this;
    }

    /**
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param array $details
     * @return Asset
     */
    public function setDetails($details)
    {
        $this->details = $details;
        return $this;
    }

    /**
     * @return float
     */
    public function getIssueQty()
    {
        return $this->issueQty;
    }

    /**
     * @param float $issueQty
     * @return Asset
     */
    public function setIssueQty($issueQty)
    {
        $this->issueQty = $issueQty;
        return $this;
    }

    /**
     * @return int
     */
    public function getIssueRaw()
    {
        return $this->issueRaw;
    }

    /**
     * @param int $issueRaw
     * @return Asset
     */
    public function setIssueRaw($issueRaw)
    {
        $this->issueRaw = $issueRaw;
        return $this;
    }


}