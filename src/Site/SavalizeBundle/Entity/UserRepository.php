<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    function updateFirstName($id,$First_Name){
        $query = $this->getEntityManager()
                        ->createQuery("
                            UPDATE SiteSavalizeBundle:User u
                            SET u.fname = :First_Name 
                            WHERE u.id = :uid"
                        )->setParameters(array('uid'=>$id,'First_Name'=>$First_Name));
        $query->execute();
    }
    
    function updateLastName($id,$Last_Name){
        $query = $this->getEntityManager()
                        ->createQuery("
                            UPDATE SiteSavalizeBundle:User u
                            SET u.lname = :Last_Name 
                            WHERE u.id = :uid"
                        )->setParameters(array('uid'=>$id,'Last_Name'=>$Last_Name));
        $query->execute();
    }
    
    function updateUsername($id,$Username){
        $query = $this->getEntityManager()
                        ->createQuery("
                            UPDATE SiteSavalizeBundle:User u
                            SET u.username = :Username 
                            WHERE u.id = :uid"
                        )->setParameters(array('uid'=>$id,'Username'=>$Username));
        $query->execute();
    }
    
    function updatePassword($id,$Password){
        $query = $this->getEntityManager()
                        ->createQuery("
                            UPDATE SiteSavalizeBundle:User u
                            SET u.password = :Password 
                            WHERE u.id = :uid"
                        )->setParameters(array('uid'=>$id,'Password'=>$Password));
        $query->execute();
    }
    
    function updateEmail($id,$Email){
        $query = $this->getEntityManager()
                        ->createQuery("
                            UPDATE SiteSavalizeBundle:User u
                            SET u.email = :Email 
                            WHERE u.id = :uid"
                        )->setParameters(array('uid'=>$id,'Email'=>$Email));
        $query->execute();
    }
}
