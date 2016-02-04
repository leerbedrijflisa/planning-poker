<?php

namespace PlanningBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\PlanningGroup;
use AppBundle\Entity\Ticket;
use PlanningBundle\Form\TicketType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlanningController extends Controller
{

    /**
     * @Route("/planning/{token}", name="planning")
     * @Template()
     */
    public function planningAction(Request $request, PlanningGroup $group)
    {
        $ticket = new Ticket();

        $form = $this->createForm(new TicketType(), $ticket);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ticket->setGroup($group);

            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('planning', array(
                'token' => $group->getToken(),
            ));
        }

        return array(
            'form'  => $form->createView(),
            'group' => $group,
        );
    }

    /**
     * @Route("/planning/{token}/card/{id}/select", name="planning_card_select", options={"expose": true})
     * @ParamConverter("group", class="AppBundle:PlanningGroup", options={"mapping": {"token": "token"}})
     * @ParamConverter("card", class="AppBundle:Card", options={"mapping": {"id": "id"}})
     */
    public function selectCardAction(PlanningGroup $group, Card $card)
    {
        if (!$this->get('planning_session')->validate($group)) {
            throw $this->createAccessDeniedException();
        }

        $session = $this->get('planning_session')->getSession();

        $this->container->get('card_service')->toggleCardSelection($session, $card);

        return new JsonResponse(array(
            'group_token' => $group->getToken(),
            'card_id'     => $card->getId(),
        ));
    }

}
