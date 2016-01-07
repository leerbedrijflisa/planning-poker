<?php

namespace PlanningBundle\RPC;

use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\RPC\RpcInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class RPCServer
 * @package PlanningBundle\RPC
 */
class RPCServer extends ContainerAware implements RpcInterface
{

    public function selectCard(ConnectionInterface $connection, WampRequest $request, $params)
    {
        $group = $this->container->get('doctrine')->getRepository('AppBundle:PlanningGroup')->findOneBy(array(
            'token' => $params['group-token'],
        ));


        $stmt = $this->container->get('database_connection')->prepare("SELECT * FROM user_session WHERE planning_group_id = :planning_group_id AND selected_card_id IS NOT NULL");
        $stmt->bindValue('planning_group_id', $group->getId(), \PDO::PARAM_INT);
        $stmt->execute();
        $selectedSessions = $stmt->rowCount();

        $stmt = $this->container->get('database_connection')->prepare("SELECT * FROM user_session WHERE planning_group_id = :planning_group_id");
        $stmt->bindValue('planning_group_id', $group->getId(), \PDO::PARAM_INT);
        $stmt->execute();
        $allSessions = $stmt->rowCount();

        return array(
            "result" => array(
                'reveal' => ($allSessions == $selectedSessions),
                'ses' => count($group->getSessions()),
                'sel' => $selectedSessions
            ),

        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pp.server';
    }
}