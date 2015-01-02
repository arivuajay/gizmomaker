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

class UpdateUserType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('name',null,['attr' => ['placeholder' => 'Name *']])
                ->add('email',null, ['attr'=> ['placeholder'=> 'Email *']])
                ->add('phone',null,['attr'=>['placeholder'=>'Phone *']])
                ->add('file',null,['label'=>'Profile Photo'])
             //   ->add('password', 'repeated', ['required'=>false,'first_name' => 'password',
             //   'first_options' => ['attr' => ['placeholder' => 'Password']],
             //   'second_name' => 'confirm_password',
              //  'second_options' => ['attr' => ['placeholder' => 'Repeat password']],
             //   'type' => 'password','error_bubbling'=>true,'invalid_message'=>'Passwords don\'t match'
            //])
        ->add('UpdateAccount','submit',['attr'=>['class'=>'btn btn-primary']]);


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults([
            'data_class'=>'Gizmo\GizmoBundle\Entity\User'
        ]);
    }

    public function getName(){
        return 'update_user';
    }
} 