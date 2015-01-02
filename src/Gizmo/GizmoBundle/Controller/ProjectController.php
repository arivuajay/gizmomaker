<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/14/14
 * Time: 1:40 PM
 */

namespace Gizmo\GizmoBundle\Controller;


use Gizmo\GizmoBundle\Entity\Project;
use Gizmo\GizmoBundle\Entity\Repository\ProjectRepository;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;


class ProjectController {

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var Router
     */
    private $router;

    private $paginator;

    private $projectRepo;

    public function __construct(
        EngineInterface $templating,
        ProjectRepository $projectRepo,
        Router $router,
        $paginator,$em)
    {
        $this->templating = $templating;
        $this->router = $router;
        $this->paginator = $paginator;
        $this->projectRepo = $projectRepo;
        $this->em = $em;
    }

    public function viewAction(Request $request, Project $project){

        return $this->templating->renderResponse(
            'GizmoBundle:Project:view.html.twig',
            ['project'=>$project]
        );
    }

    public function infiniteProjectsScrollAction(Request $request){
       // $this->fixDB();die('ok');
        $projects  = $this->projectRepo->getProjectsPaged($this->paginator,$request->query->get('page',1),100);

        if($request->isXmlHttpRequest()){
            //send partial content...
            return $this->templating->renderResponse(
                'GizmoBundle:Partials:infinite_scroll.html.twig',
                ['projects'=>$projects]
            );

        }

        return $this->templating->renderResponse(
            'GizmoBundle:Project:infinite_scroll.html.twig',
            ['projects'=>$projects]
        );
    }


    private function fixDB(){
        //fix project slides

        //fix video
        $slides = $this->em->getRepository('GizmoBundle:ProjectSlide')->findAll();

        foreach($slides as $slide){
            if($slide->getType() != 'photo' && $slide->getType() != 'image'){
                $slide->setType('video');

            }else{
                $slide->setPath($slide->getValue());
                $slide->setType('photo');
                $slide->setValue(Null);
            }
        }

        $this->em->flush();



    }
} 