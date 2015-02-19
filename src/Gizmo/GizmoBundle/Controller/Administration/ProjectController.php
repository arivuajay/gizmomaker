<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/17/14
 * Time: 5:04 PM
 */

namespace Gizmo\GizmoBundle\Controller\Administration;


use Doctrine\ORM\EntityRepository;
use Gizmo\GizmoBundle\Entity\Project;
use Gizmo\GizmoBundle\Entity\User;
use Gizmo\GizmoBundle\Form\ProjectType;
use Gizmo\GizmoBundle\Service\ProjectManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectController {
    /**
     * @var EngineInterface
     */
    private $templating;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityRepository
     */
    private $projectRepo;

    /**
     * @var EntityRepository
     */
    private $userRepo;
    /**
     * @var ProjectManager
     */
    private $projectManager;

    /**
     * @var Router
     */
    private $router;


    private $validator;

    public function __construct(EngineInterface $templating, FormFactoryInterface $formFactory,EntityRepository $projectRepo, EntityRepository $userRepo, ProjectManager $projectManager, Router $router, ValidatorInterface $validator){
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->projectRepo = $projectRepo;
        $this->projectManager = $projectManager;
        $this->router = $router;
        $this->userRepo = $userRepo;
        $this->validator = $validator;

    }
    /*
     * TODO : implement paging...
     */
    public function indexAction(Request $request){

        $project = new Project();
        $form = $this->formFactory->create(new ProjectType(), $project);

        $form->handleRequest($request);
        if($form->isValid()){
            if($this->projectManager->saveProject($project)){
                //add flash message
                $request->getSession()->getFlashBag()->add(
                    'flash_good',
                    'New Project : "'.$project->getName().'" saved successfully.'
                );
            }else{ 
                $request->getSession()->getFlashBag()->add(
                    'flash_good',
                    'Error occurred while removing the project : "'.$project->getName().'".'
                );
            }

        }

        $projects = $this->projectRepo->findAll();
        $inventors = $this->userRepo->findAll();
        return $this->templating->renderResponse(
            'GizmoBundle:Administration/Project:index.html.twig',
            [
                'form'=>$form->createView(),
                'projects'=>$projects,
                'inventors'=>$inventors
            ]
        );
    }


    public function removeAction(Request $request, Project $project){

        if($this->projectManager->removeProject($project)){
            $request->getSession()->getFlashBag()->add(
                'flash_good',
                'Project : "'.$project->getName().'" removed successfully.'
            );
        }else{
            $request->getSession()->getFlashBag()->add(
                'flash_good',
                'Error occurred while removing the project : "'.$project->getName().'".'
            );
        }

        return new RedirectResponse($this->router->generate('administration_project_index'));
    }

    public function inlineEditAction(Request $request, Project $project){
        $attribute = $request->get('name');
        $value   = $request->get('value');

        $this->projectManager->changeAttribute($project,['attr'=>$attribute,'val'=>$value]);

        //validate the new value

        $errors = $this->validator->validate($project);

        if(!count($errors)){
            $this->projectManager->saveProject($project);
            return new Response('ok',200);
        }else{
            return new Response('error!',422);
        }

    }

    public function editAction(Request $request, Project $project){




        return $this->templating->renderResponse(
            'GizmoBundle:Administration/Project:edit.html.twig',
            [
                'project'=>$project
            ]
        );
    }
} 