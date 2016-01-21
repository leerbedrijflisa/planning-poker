<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Card;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PlanningGroup
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PlanningGroup
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     *
     * @Assert\NotBlank(message="De naam van de groep mag niet blanco zijn.")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=4)
     */
    private $token;

    /**
     * @var ArrayCollection | Ticket[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="group")
     */
    private $tickets;

    /**
     * @var ArrayCollection | Card[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Card", mappedBy="group")
     */
    private $cards;

    /**
     * @var ArrayCollection | UserSession[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserSession", mappedBy="planningGroup")
     */
    private $sessions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->cards = new ArrayCollection();

        $this->token = substr(md5(microtime()), -4);
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return PlanningGroup
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
     * Set token
     *
     * @param string $token
     *
     * @return PlanningGroup
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
     * Add ticket
     *
     * @param Ticket $ticket
     *
     * @return PlanningGroup
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return ArrayCollection | Ticket[]
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Has active tickets
     *
     * @return bool
     */
    public function hasActiveTicket()
    {
        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->eq('is_active', true));

        return !$this->tickets->matching($criteria)->isEmpty();
    }

    /**
     * Get active ticket
     *
     * @return Ticket
     */
    public function getActiveTicket()
    {
        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->eq('is_active', true));
        $criteria->orderBy(array(
            'created_at' => 'DESC',
        ));

        return $this->tickets->matching($criteria)->first();
    }

    /**
     * Get active tickets
     *
     * @return ArrayCollection | Ticket[]
     */
    public function getActiveTickets()
    {
        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->eq('is_active', true));

        return $this->tickets->matching($criteria);
    }

    /**
     * Get inactive tickets
     *
     * @return ArrayCollection | Ticket[]
     */
    public function getInActiveTickets()
    {
        $criteria = Criteria::create();
        $criteria->where($criteria->expr()->eq('is_active', false));

        return $this->tickets->matching($criteria);
    }


    /**
     * Add card
     *
     * @param Card $card
     *
     * @return PlanningGroup
     */
    public function addCard(Card $card)
    {
        $this->cards[] = $card;

        return $this;
    }

    /**
     * Remove card
     *
     * @param Card $card
     */
    public function removeCard(Card $card)
    {
        $this->cards->removeElement($card);
    }

    /**
     * Get cards
     *
     * @return Collection|Card[]
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Add session
     *
     * @param UserSession $session
     *
     * @return PlanningGroup
     */
    public function addSession(UserSession $session)
    {
        $this->sessions[] = $session;

        return $this;
    }

    /**
     * Remove session
     *
     * @param UserSession $session
     */
    public function removeSession(UserSession $session)
    {
        $this->sessions->removeElement($session);
    }

    /**
     * Get sessions
     *
     * @return Collection | UserSession[]
     */
    public function getSessions()
    {
        return $this->sessions;
    }
}
