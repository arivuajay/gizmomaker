<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/17/14
 * Time: 3:14 PM
 */

namespace Gizmo\GizmoBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('name',null,['attr' => ['placeholder' => 'Project Name *']])
                ->add('name2',null, ['attr'=> ['placeholder'=> 'Project Name 2 *']])
                ->add('User','entity',[
                    'class'=>'GizmoBundle:User',
                    'property'=>'name',
                    'attr'=> ['title' => 'Select project owner']
                ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults([
            'data_class'=>'Gizmo\GizmoBundle\Entity\Project'
        ]);
    }

    public function getName(){
        return 'project';
    }
} 