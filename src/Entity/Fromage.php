<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Fromage
 *
 * @ORM\Table(name="fromage", indexes={@ORM\Index(name="type", columns={"type"}), @ORM\Index(name="lait", columns={"lait"})})
 * @ORM\Entity
 */
class Fromage
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
     * @ORM\Column(name="nom", type="string", length=64, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="origine", type="string", length=256, nullable=false)
     */
    private $origine;

    /**
     * @var \Lait
     *
     * @ORM\ManyToOne(targetEntity="Lait", inversedBy="fromages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lait", referencedColumnName="id")
     * })
     */
    private $lait;

    /**
     * @var \Type
     *
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="fromages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=256, nullable=true)
     */
    private $img;

    /**
    * @ORM\OneToMany(targetEntity="Avis", mappedBy="fromage")
    */
    private $avis;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", nullable=false)
     */
    private $prix;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
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

    public function getOrigine(): ?string
    {
        return $this->origine;
    }

    public function setOrigine(string $origine): self
    {
        $this->origine = $origine;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getLait(): ?Lait
    {
        return $this->lait;
    }

    public function setLait(?Lait $lait): self
    {
        $this->lait = $lait;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setFromage($this);
        }

        return $this;
    }

    public function removeAvi(avis $avi): self
    {
        if ($this->avis->contains($avi)) {
            $this->avis->removeElement($avi);
            // set the owning side to null (unless already changed)
            if ($avi->getFromage() === $this) {
                $avi->setFromage(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

}
