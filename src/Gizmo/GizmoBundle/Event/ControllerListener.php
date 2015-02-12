<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/14/14
 * Time: 3:30 PM
 */

namespace Gizmo\GizmoBundle\Event;


use Gizmo\GizmoBundle\Entity\Repository\ProjectRepository;
use Gizmo\GizmoBundle\Service\ContentManager;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Templating\EngineInterface;

class ControllerListener {

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var ContentManager
     */
    private $contentManager;

    /**
     * @var ProjectRepository
     */
    private  $projectRepo;
    public function __construct(EngineInterface $templating, ContentManager $contentManager, ProjectRepository $projectRepo){
        $this->templating = $templating;
        $this->contentManager = $contentManager;
        $this->projectRepo = $projectRepo;
    }

    public function preExecute(FilterResponseEvent $event){
        //result returned by the controller
        $currentResponse = $event->getResponse();

        /* @var $request  \Symfony\Component\HttpFoundation\Request */
        $request =  $event->getRequest();

        $template = $request->get('_template');

        $route = $request->get('_route');

        $section = $route == 'gizmo_all_page'?'all':'articles';

        $category = $request->get('category');

        $title = $request->get('title');

        if($route == 'administration_project_edit' || stripos($route,'administration_') === false ){
            //we need to process the html to replace custom tags
            //for e.g: top menu items with items from the cms
            //top 10 articles etc..

            $modifiedContent = $this->contentManager->replaceCustomTags($currentResponse->getContent(), $category, $title, $section);

            $selected_ids = [];
            $randomProjects = $this->projectRepo->getPublishedRandomProjects(5,false,$selected_ids);

            $homeSliderProjects = [];

            if($route == 'gizmo_home') {
               $homeSliderProjects = $this->projectRepo->getPublishedRandomProjects(5,true, $selected_ids);
            }

            $modifiedContent = $this->contentManager->replaceRandomProjectTags($modifiedContent,$randomProjects,$homeSliderProjects);

            $currentResponse->setContent($modifiedContent);

            $event->setResponse($currentResponse);

        }
    }



}