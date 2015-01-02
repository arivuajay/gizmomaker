<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/10/14
 * Time: 3:46 AM
 */

namespace Gizmo\GizmoBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use Gizmo\GizmoBundle\Entity\Project;
use Gizmo\GizmoBundle\Entity\ProjectSlide;
use Symfony\Component\HttpFoundation\Request;


class ProjectManager {

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;

    }

    public function saveProject(Project $project){

        try {
            if (!$project->getId()) {
                //set a project code
                $project->setCode(uniqid());

            }

            $this->em->persist($project);
            $this->em->flush();
            return true;
        }catch(\Exception $e){
            return false;
        }

    }


    public function saveSlide(Request $request, Project $project){

        if($request->get('type') == 'photo'){
            $files = $request->files;
            foreach($files as $file){
                $newSlide = new ProjectSlide();
                $newSlide->setFile($file);
                $newSlide->setType('photo');
                $project->getSlides()->add($newSlide);
                $newSlide->setProject($project);
                $this->em->persist($newSlide);
                $this->em->flush();
               return ['success'=>true,'slide_url'=>$newSlide->getWebPath()];
            }
        }else{
            //video?
            $newSlide = new ProjectSlide();
            $newSlide->setValue($request->get('value'));
            $newSlide->setType('video');
            $project->getSlides()->add($newSlide);
            $newSlide->setProject($project);
            $this->em->persist($newSlide);
            $this->em->flush();
            return ['success'=>true];
        }


    }

    public function removeProject(Project $project){
       // try{
            $this->em->remove($project);
            $this->em->flush();
            return true;
       // }catch(\Exception $e){
            //echo $e->getMessage();exit;

      //  }
    }

    public function changeAttribute(Project $project, array $attributes){

        if(method_exists($project,'set'.ucfirst($attributes['attr']))){
            $method = 'set'.ucfirst($attributes['attr']);

            if($method == 'setUser'){
                $user = $this->em->getRepository('GizmoBundle:User')->find($attributes['val']);
                if($user){
                    $project->setUser($user);
                }
            }elseif($method == 'setIsPublished'){
                $published_status = $attributes['val'] == 'yes'?false:true;
                $project->setIsPublished($published_status);
            }
            elseif($method == 'setCreatedAt'){
                $project->setCreatedAt($attributes['val']);
            }
            else{
                $project->$method($attributes['val']);
            }

            $this->em->flush();
            return true;
        }
        return false;
    }


    

} 