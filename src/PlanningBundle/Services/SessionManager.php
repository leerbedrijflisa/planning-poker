<?php

namespace PlanningBundle\Services;

use AppBundle\Entity\Card;
use AppBundle\Entity\PlanningGroup;
use AppBundle\Entity\UserSession;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class SessionManager
 * @package PlanningBundle\Services
 */
class SessionManager implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    /**
     * @param $resourceId
     * @return UserSession
     */
    public function hasSession($resourceId)
    {
        $session = $this->container->get('doctrine')->getRepository('AppBundle:UserSession')->findOneBy(compact('resourceId'));

        return $session;
    }

    /**
     * @param PlanningGroup $group
     * @param $resourceId
     * @return UserSession
     */
    public function createSession(PlanningGroup $group, $resourceId)
    {
        $session = new UserSession();
        $session->setPlanningGroup($group);
        $session->setResourceId($resourceId);

        $em = $this->container->get('doctrine')->getManager();
        $em->persist($session);
        $em->flush();

        return $session;
    }

    /**
     * @param UserSession $session
     */
    public function removeSession(UserSession $session)
    {
        $em = $this->container->get('doctrine')->getManager();
        $em->remove($session);
        $em->flush();
    }

    public function selectCard(UserSession $session, Card $card)
    {
        $em = $this->container->get('doctrine')->getManager();

        if ($session->getSelectedCard() == $card) {
            $session->setSelectedCard(null);
        } else {
            $session->setSelectedCard($card);
        }

        $em->persist($session);
        $em->flush();
    }

}