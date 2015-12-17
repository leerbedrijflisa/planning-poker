<?php

namespace GroupBundle\Services;

use AppBundle\Entity\PlanningGroup;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class GroupService
 * @package GroupBundle\Services
 */
class GroupService extends ContainerAware
{

    /**
     * @param PlanningGroup $group
     *
     * @return bool
     */
    public function join(PlanningGroup $group)
    {
        $planningSession = $this->container->get('planning_session');
        if ($planningSession->hasSession()) {
            $session = $planningSession->getSession();
        } else {
            $session = $planningSession->createSession();
        }

        $session->setPlanningGroup($group);

        $planningSession->persist($session);

        return null !== $session->getPlanningGroup();
    }

}