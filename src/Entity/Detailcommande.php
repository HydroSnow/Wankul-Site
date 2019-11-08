<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Detailcommande
 *
 * @ORM\Table(name="detailcommande", indexes={@ORM\Index(name="detail_id_fromage_fkey", columns={"id_fromage"}), @ORM\Index(name="detail_id_command_fkey", columns={"id_commande"})})
 * @ORM\Entity
 */
class Detailcommande
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
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande", referencedColumnName="id")
     * })
     */
    private $idCommande;

    /**
     * @var \Fromage
     *
     * @ORM\ManyToOne(targetEntity="Fromage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_fromage", referencedColumnName="id")
     * })
     */
    private $idFromage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdCommande(): ?Commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Commande $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }

    public function getIdFromage(): ?Fromage
    {
        return $this->idFromage;
    }

    public function setIdFromage(?Fromage $idFromage): self
    {
        $this->idFromage = $idFromage;

        return $this;
    }


}
