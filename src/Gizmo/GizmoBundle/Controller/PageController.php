<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/14/14
 * Time: 1:40 PM
 */

namespace Gizmo\GizmoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Templating\EngineInterface;

class PageController {

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var Router
     */
    private $router;

    public function __construct(
        EngineInterface $templating,
        Router $router)
    {
        $this->templating = $templating;
        $this->router = $router;
    }

    public function homeAction(){

        return $this->templating->renderResponse(
            'GizmoBundle:Page:home.html.twig',
            []
        );
    }

    //{cateogory}/{title}
    public function pageAction($category, $title){
        return $this->templating->renderResponse(
            'GizmoBundle:Page:page.html.twig',
            []
        );
    }

} 