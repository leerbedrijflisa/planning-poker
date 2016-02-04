<?php

namespace PlanningBundle\RPC\Response;

use AppBundle\Entity\Card;
use AppBundle\Entity\UserSession;

/**
 * Class CardSelection
 * @package PlanningBundle\RPC\Response
 */
class CardSelection
{

    /**
     * @var Card
     */
    private $card;

    /**
     * @var UserSession
     */
    private $session;

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param Card $card
     * @return CardSelection
     */
    public function setCard($card)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * @return UserSession
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param UserSession $session
     * @return CardSelection
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

}