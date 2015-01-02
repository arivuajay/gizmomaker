<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/23/14
 * Time: 7:29 PM
 */

namespace Gizmo\GizmoBundle\Controller\Api;


use Gizmo\GizmoBundle\Entity\Project;
use Gizmo\GizmoBundle\Form\SyncProjectType;
use Gizmo\GizmoBundle\Service\ProjectManager;
use Gizmo\GizmoBundle\Service\UserManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjectController {

    /**
     * @var ProjectManager
     */
    private  $projectManager;

    private $formFactory;

    public function __construct(ProjectManager $projectManager, UserManager $userManager, FormFactoryInterface $formFactory){
        $this->projectManager = $projectManager;
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
    }

    public function viewJsonAction(Request $request, Project $project){
        return new JsonResponse($project);
    }


    public function addSlideAction(Request $request, Project $project){
        $result   = $this->projectManager->saveSlide($request, $project);
        if(!$result){
            return new JsonResponse('error',422);
        }else{
            return new JsonResponse($result);
        }
    }

    public function syncAction(Request $request, Project $project){
        //access control on attributes

        $form = $this->formFactory->create(new SyncProjectType(), $project,['csrf_protection'   => false,'allow_extra_fields'=>true]);

        $form->handleRequest($request);

        if($form->isValid()){

            $this->projectManager->saveProject($project);

            return new JsonResponse($project);

        }else{

            $errors = $this->_formErrors($form);
            return new JsonResponse($errors,422);
        }


    }

    public function addAvatarAction(Request $request, Project $project){

        if($request = $this->userManager->addAvatar($project,$request)){
            return new JsonResponse($request['sync']);
        }else{
            return new JsonResponse('error',422);
        }
    }

    private function _formErrors($form){
        $errors = array();
        foreach ($form as $fieldName => $formField) {
            foreach ($formField->getErrors(true) as $error) {
                $errors[$fieldName] = $error->getMessage();
            }
        }
        return $errors;
    }

} 