<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/10/14
 * Time: 3:46 AM
 */

namespace Gizmo\GizmoBundle\Service;


use Doctrine\ORM\EntityManager;
use Gizmo\GizmoBundle\Entity\Project;
use Gizmo\GizmoBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class UserManager {
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $entityManager, EncoderFactory $encoderFactory
        , EventDispatcherInterface $dispatcher){
        $this->em = $entityManager;
        $this->encoderFactory = $encoderFactory;
    }

    /*
     *  encode password before save...
     */
    public function saveUser(User $user){
        try {
            $encoder = $this->encoderFactory->getEncoder($user);

            if($user->getPassword()){
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);
            }

            //for new users..
            if(!$user->getId()) {
                $role = $this->em->getRepository('GizmoBundle:Role')->findOneBy(array('role' => 'ROLE_MEMBER'));
                $user->addRole($role);
            }


            $this->em->persist($user);
            $this->em->flush();
            return true;

        }catch(\Exception $e){

            return false;
        }
    }

    public function removeUser($user){
        $this->em->remove($user);
        $this->em->flush();
        return true;
    }


    public function addAvatar(Project $project, Request $request){
        $file = $request->files->get('file');
        $user = $project->getUser();
        $user->setFile($file);
        $this->em->flush($user);
        return ['success'=>true,'sync'=>['avatar'=>$user->getAvatarWebPath()]];
    }



} 