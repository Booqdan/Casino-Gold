<?php

namespace CasinoGoldBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cash
 *
 * @ORM\Table(name="cash")
 * @ORM\Entity(repositoryClass="CasinoGoldBundle\Repository\CashRepository")
 */
class Cash
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
     * @var int
     *
     * @ORM\Column(name="cash", type="integer")
     */
    private $cash;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255)
     */
    private $currency;


    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="CasinoGoldBundle\Entity\User", inversedBy="Cash")
     */
    private $user;

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
     * Set cash
     *
     * @param integer $cash
     *
     * @return Cash
     */
    public function setCash($cash)
    {
        $this->cash = $cash;

        return $this;
    }

    /**
     * Get cash
     *
     * @return int
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Cash
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }



    /**
     * Set user
     *
     * @param \CasinoGoldBundle\Entity\User $user
     *
     * @return Cash
     */
    public function setUser(\CasinoGoldBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CasinoGoldBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
