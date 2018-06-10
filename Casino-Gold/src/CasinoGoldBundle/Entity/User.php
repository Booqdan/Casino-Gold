<?php
namespace CasinoGoldBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var array|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CasinoGoldBundle\Entity\Cash", mappedBy="User")
     */
    private $cash = [];

    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    protected $pocket;

    /**
     * @return mixed
     */
    public function getPocket()
    {
        return $this->pocket;
    }

    /**
     * @param mixed $pocket
     */
    public function setPocket($pocket)
    {
        $this->pocket = $pocket;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->cash = new ArrayCollection();
        $this->pocket = 0;
    }

    /**
     * Add cash
     *
     * @param \CasinoGoldBundle\Entity\Cash $cash
     *
     * @return User
     */
    public function addCash(\CasinoGoldBundle\Entity\Cash $cash)
    {
        $this->cash[] = $cash;

        return $this;
    }

    /**
     * Remove cash
     *
     * @param \CasinoGoldBundle\Entity\Cash $cash
     */
    public function removeCash(\CasinoGoldBundle\Entity\Cash $cash)
    {
        $this->cash->removeElement($cash);
    }

    /**
     * Get cash
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * Get AllCash
     * @return int
     */
    public function getAllCash()
    {
        $pocket = 0;
        foreach ($this->cash as $cash) {
            $pocket+=$cash->getCash;
        }
        return $pocket;
    }

}
