<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MetaAdRepository")
 */
class MetaAd
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $value;

    /**
     * @var Ad
     * @ORM\ManyToOne(targetEntity="\App\Entity\Ad", inversedBy="metas")
     * @ORM\JoinColumn(name="ad_id", referencedColumnName="id")
     */
    private $ad;

    /**
     * MetaCategory constructor.
     * @param $name
     * @param $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return Ad
     */
    public function getCategory(): Ad
    {
        return $this->ad;
    }

    /**
     * @param Ad $ad
     */
    public function setCategory(Ad $ad): void
    {
        $this->ad = $ad;
    }

    /**
     * @return Ad
     */
    public function getAd(): Ad
    {
        return $this->ad;
    }

    /**
     * @param Ad $ad
     */
    public function setAd(Ad $ad): void
    {
        $this->ad = $ad;
    }
}
