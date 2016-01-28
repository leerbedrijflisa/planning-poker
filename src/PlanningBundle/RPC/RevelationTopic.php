<?php

namespace PlanningBundle\RPC;

use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use PlanningBundle\RPC\Response\RevelationTopicResponse;
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

        $response = RevelationTopicResponse::create();

        if ($session = $this->session_manager->hasSession($connection->resourceId)) {
            $activeSessions = $this->session_manager->getActiveSessionsAsync($session->getPlanningGroup());
            $selectedSessions = $this->session_manager->getSelectedSessionsAsync($session->getPlanningGroup());

            $response
                ->setGroupToken($session->getPlanningGroup()->getToken())
                ->setCardId(null !== $session->getSelectedCard() ? $session->getSelectedCard()->getId() : null)
                ->setInRevealState($activeSessions->count() == $selectedSessions->count());

            if ($response->isInRevealState()) {
                foreach ($session->getPlanningGroup()->getSessions() as $session) {
                    $response->addSelectedCard($session->getSelectedCard());
                }
            }

            $topic->broadcast($response->toArray());
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