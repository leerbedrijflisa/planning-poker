<?php

namespace PlanningBundle\Services;

use AppBundle\Entity\PlanningGroup;
use AppBundle\Entity\UserSession;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class PlanningSession
 * @package PlanningBundle\Services
 */
class PlanningSession extends ContainerAware
{

    /**
     * @return bool
     */
    public function hasSession()
    {
        return $this->container->get('session')->has('planning_session');
    }

    /**
     * @param PlanningGroup $group
     *
     * @return bool
     */
    public function validate(PlanningGroup $group)
    {
        if ($this->hasSession()) {
            $session = $this->getSession();

            if (null !== $session->getPlanningGroup()) {
                return $session->getPlanningGroup()->getId() == $group->getId();
            }
        }

        return false;
    }

    /**
     * @return UserSession
     */
    public function createSession()
    {
        $em = $this->container->get('doctrine')->getManager();

        $session = new UserSession();
        $session->setToken(uniqid(time()));

        $em->persist($session);
        $em->flush();

        $this->setSession($session);

        return $session;
    }

    /**
     * @param UserSession $session
     *
     * @return void
     */
    public function setSession(UserSession $session)
    {
        $this->container->get('session')->set('planning_session', $session);
    }

    /**
     * @return UserSession
     */
    public function getSession()
    {
        return $this->container->get('session')->get('planning_session');
    }

    /**
     * @param UserSession $session
     *
     * @return void
     */
    public function persist(UserSession $session)
    {
        $em = $this->container->get('doctrine')->getManager();

        $em->merge($session);
        $em->flush();
    }

}