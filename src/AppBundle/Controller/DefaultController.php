<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PlanningGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return array(
            'groups' => $this->getDoctrine()->getRepository('AppBundle:PlanningGroup')->findAll()
        );
    }

    /**
     * @Route("/join/{token}", name="join_planning_group")
     */
    public function joinAction(PlanningGroup $group)
    {
        $this->container->get('group_service')->join($group);

        return $this->redirectToRoute('planning', array(
            'token' => $group->getToken()
        ));
    }

}
