<?php

namespace PlanningBundle\RPC;

use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\RPC\RpcInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class PlanningPokerServer
 * @package PlanningBundle\RPC
 */
class PlanningPokerServer implements RpcInterface, ContainerAwareInterface
{

    use ContainerAwareTrait;

    public function joinGroup(ConnectionInterface $connection, WampRequest $request, $params)
    {
        $session = $this->container->get('session_manager')->hasSession($connection->resourceId);

        if (null === $session) {
            $group = $this->container->get('doctrine')->getRepository('AppBundle:PlanningGroup')->findOneBy(array(
                'token' => $params['group-token']
            ));
            if (null !== $group) {
                $session = $this->container->get('session_manager')->createSession($group, $connection->resourceId);
            } else {
                throw new \RuntimeException(sprintf("Could not join group. Invalid group token '%s'", $params['group-token']));
            }
        }

        return array(
            'result' => array(
                'resource_id' => $session->getResourceId()
            ),
        );
    }

    public function selectCard(ConnectionInterface $connection, WampRequest $request, $params)
    {
        $session = $this->container->get('session_manager')->hasSession($connection->resourceId);

        if (null !== $session) {
            $card = $this->container->get('doctrine')->getRepository('AppBundle:Card')->find($params['card-id']);

            $this->container->get('session_manager')->selectCard($session, $card);

            return array(
                'result' => array(
                    'card_id' => $card->getId()
                )
            );
        }

        throw new \RuntimeException(
            sprintf("Card could not be selected")
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planning_poker_server';
    }
}