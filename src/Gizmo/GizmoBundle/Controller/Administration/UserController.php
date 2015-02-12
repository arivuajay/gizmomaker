<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/17/14
 * Time: 5:04 PM
 */

namespace Gizmo\GizmoBundle\Controller\Administration;


use Doctrine\ORM\EntityRepository;
use Gizmo\GizmoBundle\Entity\User;
use Gizmo\GizmoBundle\Form\UpdateUserType;
use Gizmo\GizmoBundle\Form\UserType;
use Gizmo\GizmoBundle\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController {
    /**
     * @var EngineInterface
     */
    private $templating;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;


    /**
     * @var Router
     */
    private $router;

    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var EntityRepository
     */
    private $userRepo;

    public function __construct(EngineInterface $templating, FormFactoryInterface $formFactory,UserManager $userManager, EntityRepository $userRepo, Router $router){
        $this->templating = $templating;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->userRepo = $userRepo;
        $this->userManager = $userManager;
    }

    /*
     * TODO : implement paging...
     */
    public function indexAction(Request $request){

        $user = new User();
        $form = $this->formFactory->create(new UserType(), $user);

        $form->handleRequest($request);
        if($form->isValid()) {
            if ($this->userManager->saveUser($user)) {
                //add flash message
                $request->getSession()->getFlashBag()->add(
                    'flash_good',
                    'New User : "' . $user->getName() . '" saved successfully.'
                );
            } else {

                $request->getSession()->getFlashBag()->add(
                    'flash_good',
                    'Error occurred while adding the user : "' . $user->getName() . '".'
                );
            }

        }
        $users = $this->userRepo->findAll();

        return $this->templating->renderResponse(
            'GizmoBundle:Administration/User:index.html.twig',
            [
                'form'=>$form->createView(),
                'users'=>$users
            ]
        );
    }


    public function removeAction(Request $request, User $user){

        if($this->userManager->removeUser($user)){
            $request->getSession()->getFlashBag()->add(
                'flash_good',
                'User : "'.$user->getName().'" removed successfully.'
            );
        }else{
            $request->getSession()->getFlashBag()->add(
                'flash_good',
                'Error occurred while removing the user : "'.$user->getName().'".'
            );
        }

        return new RedirectResponse($this->router->generate('administration_user_index'));
    }


    public function editAction(Request $request, User $user){

        //temporary unset password
        $tempPass = $user->getPassword();

        $form = $this->formFactory->create(new UpdateUserType(), $user);

        $form->handleRequest($request);

        if($form->isValid()) {
            $data= $request->get('update_user');
            //reload old password
            if($tempPass && (is_null($data['password']) || is_null($data['password']['password']))){
                $user->setPassword($tempPass);
            }
            $this->userManager->saveUser($user);
            return new RedirectResponse($this->router->generate('administration_user_index'));
        }
        return $this->templating->renderResponse(
            'GizmoBundle:Administration/User:edit.html.twig',
            [
                'form'=>$form->createView(),
                'user'=>$user
            ]
        );
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