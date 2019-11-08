<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Lait
 *
 * @ORM\Table(name="lait")
 * @ORM\Entity
 */
class Lait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=256, nullable=false)
     */
    private $nom;

    /**
    * @ORM\OneToMany(targetEntity="Fromage", mappedBy="lait")
    */
    private $fromages;

    public function __construct()
    {
        $this->fromages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Fromage[]
     */
    public function getFromages(): Collection
    {
        return $this->fromages;
    }

    public function addFromage(Fromage $fromage): self
    {
        if (!$this->fromages->contains($fromage)) {
            $this->fromages[] = $fromage;
            $fromage->setLait($this);
        }

        return $this;
    }

    public function removeFromage(Fromage $fromage): self
    {
        if ($this->fromages->contains($fromage)) {
            $this->fromages->removeElement($fromage);
            // set the owning side to null (unless already changed)
            if ($fromage->getLait() === $this) {
                $fromage->setLait(null);
            }
        }

        return $this;
    }

}
