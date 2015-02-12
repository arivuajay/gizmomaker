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


class LoadUserRoles  extends  AbstractFixture  implements OrderedFixtureInterface{

    public function load(ObjectManager $manager){
        $superAdminRole = new \Gizmo\GizmoBundle\Entity\Role();
        $superAdminRole->setName('Super Admin');
        $superAdminRole->setRole('ROLE_SUPER_ADMIN');

        $userRole = new \Gizmo\GizmoBundle\Entity\Role();
        $userRole->setName('Member');
        $userRole -> setRole('ROLE_MEMBER');


        $manager->persist($userRole);
        $manager->persist($superAdminRole);
        $manager->flush();

        $this->addReference('super-admin',$superAdminRole);
        $this->addReference('member',$userRole);


    }

    public function getOrder(){
        return 1;
    }
}

