<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/14/14
 * Time: 1:40 PM
 */

namespace Gizmo\GizmoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
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
        $videoLen = range(1, 5);
         $slideVideo = array_rand($videoLen, 1);
        return $this->templating->renderResponse(
            'GizmoBundle:Page:home.html.twig',
            ['slide_video'=>$videoLen[$slideVideo]]
        );
    }

    //{cateogory}/{title}
    public function pageAction($category, $title,  Request $request){
        $url_title = str_replace('_',' ', $category);
        $url_category = str_replace('_',' ', $title);

        return $this->templating->renderResponse(
            'GizmoBundle:Page:page.html.twig',
            array('url_category'=>$url_category,'url_title'=> $url_title)
        );
    }

}