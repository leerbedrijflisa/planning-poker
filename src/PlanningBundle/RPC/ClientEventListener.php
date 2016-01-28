<?php

namespace PlanningBundle\RPC;

use Gos\Bundle\WebSocketBundle\Event\ClientEvent;
use Gos\Bundle\WebSocketBundle\Event\ServerEvent;
use PlanningBundle\Services\SessionManager;

/**
 * Class ClientEventListener
 * @package PlanningBundle\RPC
 */
class ClientEventListener
{

    /**
     * @var SessionManager
     */
    private $session_manager;

    /**
     * ClientEventListener constructor.
     * @param SessionManager $session_manager
     */
    public function __construct(SessionManager $session_manager)
    {
        $this->session_manager = $session_manager;
    }

    public function onClientDisconnected(ClientEvent $event)
    {
        $resourceId = $event->getConnection()->resourceId;
        if ($session = $this->session_manager->hasSession($resourceId)) {
            $this->session_manager->removeSession($session);
        }
    }

    public function onServerLaunched(ServerEvent $event)
    {
        $this->session_manager->removeAll();
    }
    
}