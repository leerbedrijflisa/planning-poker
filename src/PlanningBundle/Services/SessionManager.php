<?php

namespace PlanningBundle\Services;

use AppBundle\Entity\Card;
use AppBundle\Entity\PlanningGroup;
use AppBundle\Entity\UserSession;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Types\Type;
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
     * @param $resourceId
     * @return UserSession|null
     */
    public function getSession($resourceId)
    {
        if ($session = $this->hasSession($resourceId)) {
            return $session;
        }

        return null;
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

    public function removeAll()
    {
        $em = $this->container->get('doctrine')->getManager();
        $repo = $em->getRepository('AppBundle:UserSession');

        foreach ($repo->findAll() as $session) {
            $em->remove($session);
        }
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

    /**
     * @param PlanningGroup $group
     * @return ArrayCollection
     */
    public function getActiveSessionsAsync(PlanningGroup $group)
    {
        $em = $this->container->get('doctrine')->getManager();

        /** @var ObjectRepository|EntityRepository $repo */
        $repo = $em->getRepository('AppBundle:UserSession');

        $qb = $repo->createQueryBuilder('us');
        $qb->select('user_session')
            ->from('AppBundle:UserSession', 'user_session')
            ->where($qb->expr()->eq('user_session.planningGroup', ':group'))
            ->setParameter('group', $group, Type::OBJECT)
            ->groupBy('user_session.id');

        return new ArrayCollection($qb->getQuery()->getResult(Query::HYDRATE_ARRAY));
    }

    /**
     * @param PlanningGroup $group
     * @return ArrayCollection
     */
    public function getSelectedSessionsAsync(PlanningGroup $group)
    {
        $em = $this->container->get('doctrine')->getManager();

        /** @var ObjectRepository|EntityRepository $repo */
        $repo = $em->getRepository('AppBundle:UserSession');

        $qb = $repo->createQueryBuilder('us');
        $qb->select('user_session')
            ->from('AppBundle:UserSession', 'user_session')
            ->where($qb->expr()->eq('user_session.planningGroup', ':group'))
            ->andWhere($qb->expr()->isNotNull('user_session.selectedCard'))
            ->setParameter('group', $group, Type::OBJECT)
            ->groupBy('user_session.id');

        return new ArrayCollection($qb->getQuery()->getResult(Query::HYDRATE_ARRAY));
    }

}