<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $lastname;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Ad", mappedBy="user", cascade={"persist", "remove"})
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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     * @return User
     */
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     * @return User
     */
    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function addAd(Ad $ad): self
    {
        if ($this->ads->contains($ad)) {
            $this->ads->add($ad);
        }

        return $this;
    }
}
