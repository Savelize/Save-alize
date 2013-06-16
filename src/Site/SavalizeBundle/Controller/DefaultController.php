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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Site\SavalizeBundle\Entity\ProductComment;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //echo $this->getUserIDByUserName("admin");
        //exit();
        $session = $this->getRequest()->getSession();
        
        if($session->get('userName')!=null)
        {
            $userType=$session->get("role");
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
                ->add('Password',"password", array('required' => true,'attr' => array('class' => 'input-block-level','oninput'=>'check(this)')))
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
                    $customerRep = $em->getRepository("SiteSavalizeBundle:Customer");
                    $customer=$customerRep->findOneByUser($user);
                    if($admin)
                    {
                        $session->set('id',$admin->getId());
                        $session->set('role',"admin");
                        return $this->redirect($this->generateUrl('site_personal_admin_settings'));
                    }
                    else if($customer)
                    {
                        $session->set('id',$customer->getId());
                        $session->set('role',"customer");
                        return $this->redirect($this->generateUrl('user_addProduct'));
                    }
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
                if ($pass_crypt == \crypt($pass, $pass_crypt))
                {
                    $session = $this->getRequest()->getSession();
                    $session->set('userName',$data['User_Name']);
                    $session->set('id',$Company->getId());
                    $session->set('role',"company");
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
                                $company->setCreatedAt(new \DateTime());
                                $company->setUpdatedAt(new \DateTime());
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
    	$request = $this->container->get('request');
    	$session = $this->getRequest()->getSession();
    	$picName = $request->get('name');
    	$customerId = $session->get('id');
    	$em=$this->getDoctrine()->getEntityManager();
    	$allLikesAndRatings = $em->getRepository('SiteSavalizeBundle:ProductRating')->getAllRatingsAndLikes($picName);
    	$allLikes = $allLikesAndRatings[0]['likesCount'];
    	$username = $session->get('userName');
    	//$username = 'samarhassan';
    	$productBrandObj = $em->getRepository('SiteSavalizeBundle:ProductBrand')->findOneByPicture($picName);
    	$customerObj = $em->getRepository('SiteSavalizeBundle:User')->findOneByUsername($username);
    	$customerId = $customerObj->getId();
	//$productBrandId = 2;
	$pbId = $productBrandObj->getId();
    	$userLikeAndRating = $em->getRepository('SiteSavalizeBundle:ProductRating')->getUserLikeAndRating($pbId, $customerId);
    	/*if ($userLikeAndRating[0]['userLike'])
    	{$userLike = 1;}
    	else
    	{$userLike = 0;}*/
    	//echo "pb".$pbId;
    	//echo "c".$customerId;
	//print_r($userLikeAndRating);
	//exit;
    	$userLike = ($userLikeAndRating[0]['userLike'])? 1 : 0;
    	$userRating = $userLikeAndRating[0]['userRating'];
	
    	//echo round(6.5);
    	//exit;
    	//return new Response($allLiksAndRatings[0]['likesCount']);	
    	//return new Response($userLike);
    	//return new Response($pbId);
    	return $this->render('SiteSavalizeBundle:Default:oneReview.html.twig', array('customerId'=>$customerId, 'pbId'=>$pbId,'username'=>$username,'picName'=>$picName, 'allLikes'=>$allLikes, 'userLike'=>$userLike, 'userRating'=>$userRating));
    }
    public function updateLikeAction()
    {
    	$request = $this->container->get('request');
    	$userLikeVal = $request->get('userLikeVal');
    	$session = $this->getRequest()->getSession();
    	$id = $request->get('id');
    	$em=$this->getDoctrine()->getEntityManager();
    	$customerObj = $em->getRepository('SiteSavalizeBundle:Customer')->find($id);
    	if($userLikeVal == 1)
    	$result = $em->getRepository('SiteSavalizeBundle:ProductRating')->updateLike($customerObj, 0);
    	else
    	{$result = $em->getRepository('SiteSavalizeBundle:ProductRating')->updateLike($customerObj, 1);}
    	return new Response (json_encode($result));
    }
    public function addCommentAction()
    {
    	$request = $this->container->get('request');
    	$comment = $request->get('userComment');
    	$customerId = $request->get('customerId');
    	$pbId = $request->get('pbId');
    	//$em=$this->getDoctrine()->getEntityManager();
    	//$product_brand = $em->getRepository('')
    	//$result = $em->getRepository('SiteSavalizeBundle:ProductComment')->addComment($customerId);
    	//$pcId = $result[0]['pcId'];
    	$em=$this->getDoctrine()->getEntityManager();
    	$customer = $em->getRepository('SiteSavalizeBundle:Customer')->find($customerId);
    	$pb = $em->getRepository('SiteSavalizeBundle:ProductBrand')->find($pbId);
    	$pc = new ProductComment();
    	$pc->setComment($comment);
    	$pc->setCustomer($customer);
    	$pc->setProductBrand($pb);
    	
    	$em->persist($pc);
    	$em->flush($pc);
    	return new Response(json_encode($pcId));
    }
    public function showCommentsAction($pbId)
    {
    	$em=$this->getDoctrine()->getEntityManager();
    	$allComments = $em->getRepository('SiteSavalizeBundle:ProductComment')->findAll();
    	$comments=array();
    	$pic=array();
    	foreach($allComments as $comment)
    	{
    		$pic[]=$comment->getCustomer()->getUser()->getPicture();
    		$comments[]=$comment->getComment(); 		
    	}
    	//echo $pbId;
    	//print_r($comments);
    	//exit;
    	return $this->render('SiteSavalizeBundle:Default:allComments.html.twig', array('comments'=>$comments, 'pic'=>$pic));
    }
    public function showAllReviewsAction()
    {
    	 $em=$this->getDoctrine()->getEntityManager();
    	 $categories = $em->getRepository('SiteSavalizeBundle:Category')->findAll();
    	 for($i=0; $i<count($categories); $i++){
    	 	$catID = $categories[$i]->getId();
    	 	$pbs[$i] = $em->getRepository('SiteSavalizeBundle:ProductBrand')->getFirstPBfromCategory($catID);	
    	 }    	 
    	 $result = $this->getBrandsOfCategoryAction();
    	 $allBrands = $em->getRepository('SiteSavalizeBundle:Brand')->findAll();
    	 
    	 for($i=0; $i<count( $allBrands); $i++)
    	 {
    	 	$allBrandNames[$i] = $allBrands[$i]->getName();
       	 }
    	// print_r($allBrands);
    	 return $this->render('SiteSavalizeBundle:Default:allReviews.html.twig', array('categories' => $categories, 'pbs'=> $pbs,
    	 'allBrands'=>json_encode($allBrandNames), 'brands'=>$result));
    }
    public function getBrandsOfCategoryAction()
    {
    	$request = $this->container->get('request');
    	$catId = $request->get('catId');
    	$em=$this->getDoctrine()->getEntityManager();
    	$brandsOfCategory = $em->getRepository('SiteSavalizeBundle:ProductBrand')->getBrandsOfCategory($catId);
    	$brands=array();
	for($i=0; $i<count($brandsOfCategory); $i++)
	{
		$brands[$i] = $brandsOfCategory[$i]->getName();
	}
	return new Response (json_encode($brands));
    }
    public function getBrandPicturesAction()
    {
    	$request = $this->container->get('request');
    	$brandSearched = $request->get('brandSearched');
    	$em=$this->getDoctrine()->getEntityManager();
    	$brandPictures = $em->getRepository('SiteSavalizeBundle:ProductBrand')->getBrandPictures($brandSearched);
    	for($i=0; $i<count($brandPictures); $i++)
	{
		$brandPics[$i] = $brandPictures[$i]->getPicture();
	}
    	return new Response (json_encode($brandPics));
    	
}
    
    public function getpictureAction(){
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $session = $request->getSession();
        $id = $session->get('id');
        $role = $session->get('role');
        if ($role == 'company'){
            $obj = $em->getRepository('SiteSavalizeBundle:Company')->find($id);
            $picname = $obj->getPicture();
        }
        elseif ($role == 'admin') {
            $obj = $em->getRepository('SiteSavalizeBundle:Admin')->find($id);
            $picname = $obj->getUser()->getPicture();
        }
        elseif ($role == 'customer'){
            $obj = $em->getRepository('SiteSavalizeBundle:Customer')->find($id);
            $picname = $obj->getUser()->getPicture();
        }
        if(!$picname)
            $picname="anonymous.jpg";
        return new Response($picname);
    }
}
