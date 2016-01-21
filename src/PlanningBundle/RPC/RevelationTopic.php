<?php

namespace PlanningBundle\RPC;

use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use PlanningBundle\Services\SessionManager;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $response = array();

        $session = $this->session_manager->hasSession($connection->resourceId);
        $sessionCount = $session->getPlanningGroup()->getSessions()->count();

        if (null !== $session) {
            $selected = 0;
            foreach ($session->getPlanningGroup()->getSessions() as $uSession) {
                if (null !== $uSession->getSelectedCard()) {
                    $selected++;
                }
            }
            $response['group_token'] = $session->getPlanningGroup()->getToken();
            $response['card_id'] = (!is_null($session->getSelectedCard())) ? $session->getSelectedCard()->getId() : null;
            $response['reveal'] = $selected == $sessionCount;
            if (true == $response['reveal']) {
                $response['cards'] = array();
                foreach ($session->getPlanningGroup()->getSessions() as $session) {
                    $response['cards'][] = $session->getSelectedCard()->getPoints();
                }
            }

            $topic->broadcast($response);
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