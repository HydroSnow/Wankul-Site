<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Token
 *
 * @ORM\Table(name="token", indexes={@ORM\Index(name="user", columns={"user"})})
 * @ORM\Entity
 */
class Token
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=32, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validAfter", type="date", nullable=false)
     */
    private $validafter;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validBefore", type="date", nullable=false)
     */
    private $validbefore;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getValidafter(): ?\DateTimeInterface
    {
        return $this->validafter;
    }

    public function setValidafter(\DateTimeInterface $validafter): self
    {
        $this->validafter = $validafter;

        return $this;
    }

    public function getValidbefore(): ?\DateTimeInterface
    {
        return $this->validbefore;
    }

    public function setValidbefore(\DateTimeInterface $validbefore): self
    {
        $this->validbefore = $validbefore;

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }


}
