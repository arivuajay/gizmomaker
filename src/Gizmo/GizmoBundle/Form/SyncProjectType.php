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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SyncProjectType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('name')
            ->add('pageTitle')
            ->add('shortDescription')
            ->add('fullDescription')
            ->add('isPublished','integer');


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults([
            'data_class'=>'Gizmo\GizmoBundle\Entity\Project',
            'csrf_protection' => false,
            'allow_extra_fields'=>true
        ]);
    }

    public function getName(){
        return 'project';
    }
} 