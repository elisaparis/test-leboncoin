<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Ad", inversedBy="categories")
     */
    private $ads;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAds(): ArrayCollection
    {
        return $this->ads;
    }

    /**
     * @param ArrayCollection $ads
     */
    public function setAds(ArrayCollection $ads): void
    {
        $this->ads = $ads;
    }

    /**
     * @param Ad $ad
     * @return Category
     */
    public function addAd(Ad $ad): self
    {
        if ($this->ads->contains($ad)) {
            $this->ads->add($ad);
        }

        return $this;
    }
}
