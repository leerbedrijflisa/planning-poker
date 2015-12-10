<?php

namespace PlanningBundle\Controller;

use AppBundle\Entity\PlanningGroup;
use AppBundle\Entity\Ticket;
use PlanningBundle\Form\TicketType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlanningController extends Controller
{

    /**
     * @Route("/planning/{token}/action", name="planning")
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
                'token' => $group->getToken()
            ));
        }

        return array(
            'form'  => $form->createView(),
            'group' => $group,
        );
    }

}
