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

class UserType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('name',null,['attr' => ['placeholder' => 'Name *']])
                ->add('email',null, ['attr'=> ['placeholder'=> 'Email *']])
                ->add('password',null,['attr'=>['placeholder'=>'Password *']])
                ->add('phone',null,['attr'=>['placeholder'=>'Phone *']]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults([
            'data_class'=>'Gizmo\GizmoBundle\Entity\User'
        ]);
    }

    public function getName(){
        return 'user';
    }
} 