<?php

namespace PlanningBundle\RPC\Response;

/**
 * Class RevelationTopicResponse
 * @package PlanningBundle\RPC\Response
 */
class RevelationTopicResponse
{

    /**
     * @var string
     */
    private $group_token;

    /**
     * @var int
     */
    private $card_id;

    /**
     * @var bool
     */
    private $in_reveal_state;

    /**
     * @var CardSelection[]
     */
    private $selected_cards = array();

    /**
     * @return string
     */
    public function getGroupToken()
    {
        return $this->group_token;
    }

    /**
     * @param string $group_token
     * @return RevelationTopicResponse
     */
    public function setGroupToken($group_token)
    {
        $this->group_token = $group_token;

        return $this;
    }

    /**
     * @return int
     */
    public function getCardId()
    {
        return $this->card_id;
    }

    /**
     * @param int $card_id
     * @return RevelationTopicResponse
     */
    public function setCardId($card_id)
    {
        $this->card_id = $card_id;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isInRevealState()
    {
        return $this->in_reveal_state;
    }

    /**
     * @param boolean $in_reveal_state
     * @return RevelationTopicResponse
     */
    public function setInRevealState($in_reveal_state)
    {
        $this->in_reveal_state = $in_reveal_state;

        return $this;
    }

    /**
     * @return RevelationTopicResponse
     */
    public static function create()
    {
        return new self;
    }

    /**
     * @return CardSelection[]
     */
    public function getSelectedCards()
    {
        return $this->selected_cards;
    }

    /**
     * @param CardSelection[] $selected_cards
     *
     * @return RevelationTopicResponse
     */
    public function setSelectedCards($selected_cards)
    {
        $this->selected_cards = $selected_cards;

        return $this;
    }

    /**
     * @param CardSelection $cardSelection
     *
     * @return RevelationTopicResponse
     */
    public function addSelectedCard(CardSelection $cardSelection)
    {
        $this->selected_cards[] = $cardSelection;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $response = array(
            'group_token'     => $this->group_token,
            'card_id'         => $this->card_id,
            'in_reveal_state' => $this->in_reveal_state,
        );
        foreach ($this->selected_cards as $cardSelection) {
            $response['selected_cards'][] = array(
                'card' => array(
                    'points' => $cardSelection->getCard()->getPoints(),
                ),
                'session' => array(
                    'resource_id' => $cardSelection->getSession()->getResourceId()
                )
            );
        }

        return $response;
    }

}