<?php

namespace PlanningBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class CardsExtension extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CardsExtension constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getGlobals()
    {
        return array(
            'cards' => $this->container->get('doctrine')->getRepository('AppBundle:Card')->findAll()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'cards';
    }
}
