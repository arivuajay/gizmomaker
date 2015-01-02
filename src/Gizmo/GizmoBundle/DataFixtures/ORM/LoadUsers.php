<?php


/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 10/20/14
 * Time: 11:02 AM
 */
namespace Gizmo\GizmoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsers  extends  AbstractFixture  implements OrderedFixtureInterface,ContainerAwareInterface{

    private $container;

    public function setContainer(ContainerInterface $container = null){
        $this->container = $container;
    }
    public function load(ObjectManager $manager){
        $superAdmin = new \Gizmo\GizmoBundle\Entity\User();
        $superAdmin -> setEmail('admin@mr.md');
        $superAdmin->setName('Super Admin');

        $encoder = $this->container
            ->get('security.encoder_factory')
            ->getEncoder($superAdmin);
        $superAdmin->setPassword($encoder->encodePassword('gizmoRocks123!', $superAdmin->getSalt()));
        $superAdmin->setIsActive(true);
        $superAdmin->addRole($this->getReference('super-admin'));


        $manager->persist($superAdmin);
        $manager->persist($superAdmin);
        $manager->flush();


    }

    public function getOrder(){
        return 2;
    }
}

