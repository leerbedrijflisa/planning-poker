<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PlanningGroup;
use GroupBundle\Form\GroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;   
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'groups' => $this->getDoctrine()->getRepository('AppBundle:PlanningGroup')->findAll()
        );
    }

    /**
    * @Route("/create", name="create_group")
    * @Template()
    */
    public function createGroupAction(Request $request)
    {
        $group = new PlanningGroup();

        $form = $this->createForm(new GroupType(), $group);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('planning', array('token' => $group->getToken()));
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
