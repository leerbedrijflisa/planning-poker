<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSession
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UserSession
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PlanningGroup", inversedBy="sessions")
     */
    private $planningGroup;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Card")
     */
    private $selectedCard;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return UserSession
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set planningGroup
     *
     * @param PlanningGroup $planningGroup
     *
     * @return UserSession
     */
    public function setPlanningGroup(PlanningGroup $planningGroup = null)
    {
        $this->planningGroup = $planningGroup;

        return $this;
    }

    /**
     * Get planningGroup
     *
     * @return PlanningGroup
     */
    public function getPlanningGroup()
    {
        return $this->planningGroup;
    }

    /**
     * Set selectedCard
     *
     * @param Card $selectedCard
     *
     * @return UserSession
     */
    public function setSelectedCard(Card $selectedCard = null)
    {
        $this->selectedCard = $selectedCard;

        return $this;
    }

    /**
     * Get selectedCard
     *
     * @return Card
     */
    public function getSelectedCard()
    {
        return $this->selectedCard;
    }
}
