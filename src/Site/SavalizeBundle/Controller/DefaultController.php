<?php

namespace Site\SavalizeBundle\Controller;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\SavalizeBundle\Entity\User;
use Site\SavalizeBundle\Entity\Company;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $userType=array("User","Company");
        $collectionConstraint = new Collection(array(
            'User_Name' => new NotBlank(),
            'Email' => array(new NotBlank()),
            'Password' => new NotBlank(),
            'Repeat_Password' => new NotBlank(),
            'User_Type' => new NotBlank()
        ));
        $data = array();
        //create the form
        
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('User_Name', null, array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Email', "email", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Password',"password", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Repeat_Password', "password", array('required' => true,'attr' => array('class' => 'input-block-level')))
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
                ->add('Password', null, array('required' => true,'attr' => array('class' => 'span2','placeholder' => 'password')))
        ;
        $SignInform = $formBuilder->getForm();
        return $this->render('SiteSavalizeBundle:Default:index.html.twig', array('signUpform' => $SignUpform->createView(),'SignInform' => $SignInform->createView()));
    }
    public function signInAction()
    {
        
    }
    
    public function signUpAction()
    {
     $userType=array("User","Company");
        $request = $this->getRequest();
        $collectionConstraint = new Collection(array(
            'User_Name' => new NotBlank(),
            'Email'   => array(new NotBlank()),
            'Password' => new NotBlank(),
            'Repeat_Password' => new NotBlank(),
            'User_Type' => new NotBlank()
        ));
        $data = array();
        //create the form
        
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('User_Name', null, array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Email', "email", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Password',"password", array('required' => true,'attr' => array('class' => 'input-block-level')))
                ->add('Repeat_Password', "password", array('required' => true,'attr' => array('class' => 'input-block-level')))
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
                if($data['Password']!=$data['Repeat_Password'])
                {
                    echo"password dosent match";
                }
            }else {
                return $this->redirect($this->generateUrl('site_savalize_homepage'));
            }
            exit();
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
