<?php

namespace CardBundle\Services;

use AppBundle\Entity\Card;
use AppBundle\Entity\UserSession;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class CardService
 * @package CardBundle\Services
 */
class CardService extends ContainerAware
{

    /**
     * @param UserSession $session
     * @param Card $card
     *
     * @return void
     */
    public function toggleCardSelection(UserSession $session, Card $card)
    {
        if (null !== $session->getSelectedCard()) {
            if ($card->getId() == $session->getSelectedCard()->getId()) {
                $session->setSelectedCard(null);
            } else {
                $session->setSelectedCard($card);
            }
        } else {
            $session->setSelectedCard($card);
        }

        $this->container->get('planning_session')->persist($session);
    }

}