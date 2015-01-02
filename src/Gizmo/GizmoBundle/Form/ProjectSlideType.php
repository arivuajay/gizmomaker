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

class ProjectSlideType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('file','file')
                ->add('type')
                ->add('value');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults([
            'data_class'=>'Gizmo\GizmoBundle\Entity\ProjectSlide'
        ]);
    }

    public function getName(){
        return 'project_slide';
    }
} 