<?php

namespace Site\SavalizeBundle\Controller;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\SavalizeBundle\Entity\Customer;
use Site\SavalizeBundle\Entity\Company;
use Site\SavalizeBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function getUserIDByUserName($username)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $userRep = $em->getRepository("SiteSavalizeBundle:User");
        $user=$userRep->findOneByUsername($username);
        if($user)
        {
            $customerRep = $em->getRepository("SiteSavalizeBundle:Customer");
            $customer=$customerRep->findOneByUser($user);
            if($customer)
            {
                return $customer->getId();
            }
            $adminRep = $em->getRepository("SiteSavalizeBundle:Admin");
            $admin=$adminRep->findOneByUser($user);
            if($admin)
            {
                return $admin->getId();
            }
        }
        $CompanyRep = $em->getRepository("SiteSavalizeBundle:Company");
        $company=$CompanyRep->findOneByUsername($username);
        if($company)
        {
            return $company->getId();
        }
        return null;
    }
    
    public function getUserTypeByUserName($username)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $userRep = $em->getRepository("SiteSavalizeBundle:User");
        $user=$userRep->findOneByUsername($username);
        if($user)
        {
            $customerRep = $em->getRepository("SiteSavalizeBundle:Customer");
            $customer=$customerRep->findOneByUser($user);
            if($customer)
            {
                return "customer";
            }
            $adminRep = $em->getRepository("SiteSavalizeBundle:Admin");
            $admin=$adminRep->findOneByUser($user);
            if($admin)
            {
                return "admin";
            }
        }
        $CompanyRep = $em->getRepository("SiteSavalizeBundle:Company");
        $company=$CompanyRep->findOneByUsername($username);
        if($company)
        {
            return "company";
        }
        return null;
    }
    public function indexAction()
    {
        //echo $this->getUserIDByUserName("admin");
        //exit();
        $session = $this->getRequest()->getSession();
        
        if($session->get('userName')!=null)
        {
            $userType=$this->getUserTypeByUserName($session->get('userName'));
            if($userType=="customer")
                return $this->redirect($this->generateUrl('user_addProduct'));
            else if($userType=="company")
                return $this->redirect($this->generateUrl('site_personal_company_settings'));
            elseif ($userType=="admin")
                return $this->redirect($this->generateUrl('site_personal_admin_settings'));
        }
            
         
        
        $userType=array("User","Company");
        $collectionConstraint = new Collection(array(
            'User_Name' => new NotBlank(),
            'Name' => new NotBlank(),
            'Email' => array(new NotBlank(),new Email()),
            'Password' => new NotBlank(),
            'Confirm_Password' => new NotBlank(),
            'User_Type' => new NotBlank()
        ));
        $data = array();
        //create the form
        
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('User_Name', null, array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Name', null, array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Email', "email", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Password',"password", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Confirm_Password', "password", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('User_Type', 'choice', array('required' => true,'choices'=>$userType,'attr' => array('class' => 'input-block-level')))
        ;
        $SignUpform = $formBuilder->getForm();
        //-------------- sign in form----------------
         $collectionConstraint = new Collection(array(
            'User_Name' => new NotBlank(),
            'Password' => new NotBlank()
        ));
        $data = array();
        //create the form
        
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('User_Name', null, array('required' => true,'attr' => array('class' => 'span2','placeholder' => 'user name')))
                ->add('Password', "password", array('required' => true,'attr' => array('class' => 'span2','placeholder' => 'password')))
        ;
        $SignInform = $formBuilder->getForm();
        return $this->render('SiteSavalizeBundle:Default:index.html.twig', array('signUpform' => $SignUpform->createView(),'SignInform' => $SignInform->createView()));
    }
    public function signInAction()
    {
        $request = $this->getRequest();
        $collectionConstraint = new Collection(array(
            'User_Name' => new NotBlank(),
            'Password' => new NotBlank()
        ));
        $data = array();
        //create the form
        
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('User_Name', null, array('required' => true,'attr' => array('class' => 'span2','placeholder' => 'user name')))
                ->add('Password', "password", array('required' => true,'attr' => array('class' => 'span2','placeholder' => 'password')))
        ;
        $SignInform = $formBuilder->getForm();
        $SignInform->bindRequest($request);
        if ($SignInform->isValid())
        {
            $data = $SignInform->getData();
            $em = $this->getDoctrine()->getEntityManager();
            
            $userRep = $em->getRepository("SiteSavalizeBundle:User");
            $user=$userRep->findOneByUsername($data['User_Name']);
            $companyrRep = $em->getRepository("SiteSavalizeBundle:Company");
            $Company=$companyrRep->findOneByUsername($data['User_Name']);
            if($user)
            {
                $pass_crypt =$user->getPassword();
                $pass=$data['Password'];
                if ($pass_crypt == \crypt($pass, $pass_crypt))
                {
                    $session = $this->getRequest()->getSession();
                    $session->set('userName',$data['User_Name']);
                    $adminRep = $em->getRepository("SiteSavalizeBundle:Admin");
                    $admin=$adminRep->findOneByUser($user);
                    if($admin)
                        return $this->redirect($this->generateUrl('site_personal_admin_settings'));
                    else
                        return $this->redirect($this->generateUrl('user_addProduct'));
                    
                }
                else
                {
                    $msg="Username or Password is wroung ";
                    return $this->render('SiteSavalizeBundle:Default:error.html.twig', array("msg"=>$msg));
                }
            }
            else if($Company)
            {
                $pass_crypt =$Company->getPassword();
                $pass=$data['Password'];
                echo \crypt($pass, $pass_crypt)."</br>";
                echo "$pass_crypt";
                if ($pass_crypt == \crypt($pass, $pass_crypt))
                {
                    $session = $this->getRequest()->getSession();
                    $session->set('userName',$data['User_Name']);
                    return $this->redirect($this->generateUrl('site_personal_company_settings'));
                }
                else
                {
                    $msg="Username or Password is wroung company";
                    return $this->render('SiteSavalizeBundle:Default:error.html.twig', array("msg"=>$msg));
                }
            }
            else
            {
                $msg="Username or Password is wroung not all";
                return $this->render('SiteSavalizeBundle:Default:error.html.twig', array("msg"=>$msg));                
            }
        }
        else
        {
            return $this->redirect($this->generateUrl('site_savalize_homepage'));
        }
    }
    
    public function signOutAction()
    {
        $session = $this->getRequest()->getSession();
        $session->clear();
        $session->remove("userName");
        return $this->redirect($this->generateUrl('site_savalize_homepage'));
    }

        public function signUpAction()
    {
     $userType=array("User","Company");
        $request = $this->getRequest();
        $collectionConstraint = new Collection(array(
            'User_Name' => new NotBlank(),
            'Name' => new NotBlank(),
            'Email' => array(new NotBlank(),new Email()),
            'Password' => new NotBlank(),
            'Confirm_Password' => new NotBlank(),
            'User_Type' => new NotBlank()
        ));
        $data = array();
        //create the form
        
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('User_Name', null, array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Name', null, array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Email', "email", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Password',"password", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Confirm_Password', "password", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('User_Type', 'choice', array('required' => true,'choices'=>$userType,'attr' => array('class' => 'input-block-level')))
        ;
        $SignUpform = $formBuilder->getForm();
        $SignUpform->bindRequest($request);
        if ($SignUpform->isValid())
            {
                $data = $SignUpform->getData();
                
                /*
                echo $data['User_Name']."<br>";
                echo $data['Email']."<br>";
                echo $data['Password']."<br>";
                echo $data['Repeat_Password']."<br>";
                echo $data['User_Type'];
                 * 
                 */
                if($data['Password']!=$data['Confirm_Password'])
                {
                    $msg="Password Miss match";
                    return $this->render('SiteSavalizeBundle:Default:error.html.twig', array("msg"=>$msg));
                }
                else
                {
                    $em = $this->getDoctrine()->getEntityManager();
                    $customerRep = $em->getRepository("SiteSavalizeBundle:User");
                            $userexist=$customerRep->findOneByUsername($data['User_Name']);
                            $companyrRep = $em->getRepository("SiteSavalizeBundle:Company");
                            $Companyexist=$companyrRep->findOneByUsername($data['User_Name']);
                        if($userexist||$Companyexist)
                        {
                            $msg="Username already exist";
                            return $this->render('SiteSavalizeBundle:Default:error.html.twig', array("msg"=>$msg));
                        }
                    if($data['User_Type']==0)
                        {
                                $user=new User();
                                $user->setUsername($data['User_Name']);
                                $user->setFname($data['Name']);
                                $pass_crypt = \crypt($data['Password']);
                                $user->setPassword($pass_crypt);
                                $user->setEmail($data['Email']);
                                $user->setCreatedAt(new \DateTime());
                                $user->setUpdatedAt(new \DateTime());
                                $em->persist($user);
                                $em->flush($user);
                                $customer=new Customer();
                                $customer->setUser($user);
                                $em->persist($customer);
                                $em->flush($customer);
                                $session = $this->getRequest()->getSession();
                                $session->set('userName',$data['User_Name']);
                                return $this->redirect($this->generateUrl('site_personal_user_settings'));
                        }
                        else
                        {
                                $company=new Company();
                                $company->setName($data['Name']);
                                $company->setUsername($data['User_Name']);
                                $company->setEmail($data['Email']);
                                $pass_crypt = \crypt($data['Password']);
                                $company->setPassword($pass_crypt);
                                $em->persist($company);
                                $em->flush($company);
                                $session = $this->getRequest()->getSession();
                                $session->set('userName',$data['User_Name']);
                                return $this->redirect($this->generateUrl('site_personal_company_settings'));
                     }
                }
            }else {
                return $this->redirect($this->generateUrl('site_savalize_homepage'));
            }
    }
    public function showOneReviewAction()
    {
    	return $this->render('SiteSavalizeBundle:Default:oneReview.html.twig', array());
    }
    public function showAllReviews()
    {
    	 $em=$this->getDoctrine()->getEntityManager();
    	 $categories = $em->getRepository('SiteSavalizeBundle:Category')->findAll();
    	 return $this->render('SiteSavalizeBundle:Default:allReviews.html.twig', array('cats' => $categories));
    }
}
