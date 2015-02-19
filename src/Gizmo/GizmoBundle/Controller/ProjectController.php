<?php

/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/14/14
 * Time: 1:40 PM
 */

namespace Gizmo\GizmoBundle\Controller;

use Doctrine\ORM\EntityManager;
use Gizmo\GizmoBundle\Entity\Project;
use Gizmo\GizmoBundle\Entity\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    EngineInterface $templating, ProjectRepository $projectRepo, Router $router, $paginator, $em) {
        $this->templating = $templating;
        $this->router = $router;
        $this->paginator = $paginator;
        $this->projectRepo = $projectRepo;
        $this->em = $em;
    }

    public function viewAction($code , $name2) {
        $project = $this->em->getRepository('GizmoBundle:Project')->findOneBy(array('code' => $code));
        
        if (!$project) {
            throw $this->createNotFoundException(
                'No project found'
            );
        }

        return $this->templating->renderResponse(
                        'GizmoBundle:Project:view.html.twig', ['project' => $project]
        );
    }

    public function infiniteProjectsScrollAction(Request $request) {
        // $this->fixDB();die('ok');
        $projects = $this->projectRepo->getProjectsPaged($this->paginator, $request->query->get('page', 1), 100);

        if ($request->isXmlHttpRequest()) {
            //send partial content...
            return $this->templating->renderResponse(
                            'GizmoBundle:Partials:infinite_scroll.html.twig', ['projects' => $projects]
            );
        }

        return $this->templating->renderResponse(
                        'GizmoBundle:Project:infinite_scroll.html.twig', ['projects' => $projects]
        );
    }

    private function fixDB() {
        //fix project slides
        //fix video
        $slides = $this->em->getRepository('GizmoBundle:ProjectSlide')->findAll();

        foreach ($slides as $slide) {
            if ($slide->getType() != 'photo' && $slide->getType() != 'image') {
                $slide->setType('video');
            } else {
                $slide->setPath($slide->getValue());
                $slide->setType('photo');
                $slide->setValue(Null);
            }
        }

        $this->em->flush();
    }

    public function updateAction(Request $request) {
        $state = $request->query->get('state');
        $model = $this->em->getRepository('GizmoBundle:Project')->find($request->query->get('code'));
        if ($state == 1) {
            $cnt = $model->getLikeCnt();
            $model->setLikeCnt($cnt + 1);
        } elseif ($state == 0) {
            $cnt = $model->getDislikeCnt();
            $model->setDislikeCnt($cnt + 1);
        }


        $this->em->persist($model);
        $this->em->flush();

        return new RedirectResponse($this->router->generate('project_view', array('name2'=>$model->getName2URL(),"code" => $model->getCode())));
    }
}
