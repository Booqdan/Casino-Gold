<?php

namespace CasinoGoldBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CardColor
 *
 * @ORM\Table(name="card_color")
 * @ORM\Entity(repositoryClass="CasinoGoldBundle\Repository\CardColorRepository")
 */
class CardColor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var array|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CasinoGoldBundle\Entity\Card", mappedBy="CardColor")
     */
    private $card = [];

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return CardColor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->card = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add card
     *
     * @param \CasinoGoldBundle\Entity\Card $card
     *
     * @return CardColor
     */
    public function addCard(\CasinoGoldBundle\Entity\Card $card)
    {
        $this->card[] = $card;

        return $this;
    }

    /**
     * Remove card
     *
     * @param \CasinoGoldBundle\Entity\Card $card
     */
    public function removeCard(\CasinoGoldBundle\Entity\Card $card)
    {
        $this->card->removeElement($card);
    }

    /**
     * Get card
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCard()
    {
        return $this->card;
    }
}
