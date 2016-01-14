<?php

namespace PlanningBundle\RPC;

use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use PlanningBundle\Services\SessionManager;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

/**
 * Class RevelationTopic
 * @package PlanningBundle\RPC
 */
class RevelationTopic implements TopicInterface
{

    /**
     * @var SessionManager
     */
    private $session_manager;

    /**
     * RevelationTopic constructor.
     * @param SessionManager $session_manager
     */
    public function __construct(SessionManager $session_manager)
    {
        $this->session_manager = $session_manager;
    }

    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        dump(count($topic));
    }

    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {

    }

    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     * @param $event
     * @param  array $exclude
     * @param  array $eligible
     */
    public function onPublish(
        ConnectionInterface $connection,
        Topic $topic,
        WampRequest $request,
        $event,
        array $exclude,
        array $eligible
    ) {
        $session = $this->session_manager->hasSession($connection->resourceId);

        if (null !== $session) {
            $selected = 0;
            foreach ($session->getPlanningGroup()->getSessions() as $uSession) {
                if (null !== $uSession->getSelectedCard()) {
                    $selected++;
                }
            }

            $topic->broadcast(array(
                'group_token' => $session->getPlanningGroup()->getToken(),
                'card_id' => $session->getSelectedCard()->getId(),
                'reveal' => $selected == $session->getPlanningGroup()->getSessions()->count()
            ));
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'planning_poker_server';
    }
}