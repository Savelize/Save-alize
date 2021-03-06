<?php

namespace Site\SavalizeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Site\SavalizeBundle\Entity\Company;
use Site\SavalizeBundle\Entity\Category;
use Site\SavalizeBundle\Entity\Brand;
use Site\SavalizeBundle\Entity\ProductBrand;
use Site\SavalizeBundle\Entity\Product;
use Site\SavalizeBundle\Entity\UserNotification;
use Site\SavalizeBundle\Form\CompanyType;

/**
 * Company controller.
 *
 */
class CompanyController extends Controller
{
    /**
     * Lists all Company entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteSavalizeBundle:Company')->findAll();

        return $this->render('SiteSavalizeBundle:Company:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Company entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Company();
        $form = $this->createForm(new CompanyType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('company_show', array('id' => $entity->getId())));
        }

        return $this->render('SiteSavalizeBundle:Company:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Company entity.
     *
     */
    public function newAction()
    {
        $entity = new Company();
        $form   = $this->createForm(new CompanyType(), $entity);

        return $this->render('SiteSavalizeBundle:Company:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Company entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteSavalizeBundle:Company:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $editForm = $this->createForm(new CompanyType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteSavalizeBundle:Company:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Company entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:Company')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CompanyType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('company_edit', array('id' => $id)));
        }

        return $this->render('SiteSavalizeBundle:Company:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Company entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteSavalizeBundle:Company')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Company entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('company'));
    }

    /**
     * Creates a form to delete a Company entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    public function page10Action(){
        return $this->render('SiteSavalizeBundle:Company:page10.html.twig');
    }
    
    /* company personal settings */
    public function personalcompanysettingsAction(){
        $request = $this->getRequest();
        $successMessage = false;
        $data = array();
        $session = $request->getSession();
        $id = $session->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $obj = $em->getRepository('SiteSavalizeBundle:Company')->find($id);
        $collectionConstraint = new Collection(array(
            'Name' => new NotBlank(),
            'Username' => new NotBlank(),
            'Email' => array(new Email(), new NotBlank()),
            'Telephone' => array(),
            'Country' => array(),
            'City' => array(),
            'Region' => array(),
            'upload_your_photo' => new Image (array(
                'maxSize' => '2048k',
                'mimeTypes' => array(
                    'image/jpeg',
                    'image/png',
                    'image/bmp',
                    'image/gif',
            ),
                'mimeTypesMessage' => 'Please upload a valid Image (jpg, jpeg , png , bmp or gif)'))
            
        ));
        $data['Name']= $obj->getName();
        $data['Username']= $obj->getUsername();
        $data['Email']= $obj->getEmail();
        $data['Telephone']= $obj->getTelephone();
        $data['Country']= $obj->getCountry();
        $data['City']= $obj->getCity();
        $data['Region']= $obj->getRegion();
        $picturename= $obj->getPicture();
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('Name')
                ->add('Username')
                ->add('Email', 'email', array('attr' => array('class' => 'email')))
                ->add('Telephone','text',array('required' => false))
                ->add('Country','text',array('required' => false))
                ->add('City','text',array('required' => false))
                ->add('Region','text',array('required' => false))
                ->add('upload_your_photo','file', array('required' => false))
                        ;
       $form = $formBuilder->getForm();
        if ($request->getMethod() == 'POST') {
           
                //fill the form data from the request
                $form->bindRequest($request);
                //check if the form values are correct
                if ($form->isValid()) {
                    $postdata = $form->getData();
                    $em->getRepository('SiteSavalizeBundle:Company')->updateName($id,$postdata['Name']);
                    $em->getRepository('SiteSavalizeBundle:Company')->updateUsername($id,$postdata['Username']);
                    $em->getRepository('SiteSavalizeBundle:Company')->updateEmail($id,$postdata['Email']);
                    $em->getRepository('SiteSavalizeBundle:Company')->updateTelephone($id,$postdata['Telephone']);
                    $em->getRepository('SiteSavalizeBundle:Company')->updateCountry($id,$postdata['Country']);
                    $em->getRepository('SiteSavalizeBundle:Company')->updateCity($id,$postdata['City']);
                    $em->getRepository('SiteSavalizeBundle:Company')->updateRegion($id,$postdata['Region']);
                    if($postdata['upload_your_photo']){
                        $imgext = $postdata['upload_your_photo']->guessExtension();
                        $picturename = $postdata['Username'].".".$imgext;
                        $path = '/opt/lampp/htdocs/Save-alize/web/img/usersimgs';
                        $postdata['upload_your_photo']->move($path,$picturename);
                        $em->getRepository('SiteSavalizeBundle:Company')->updatePicture($id,$picturename);
                    }
                    $obj->setUpdatedAt(new \DateTime());
                    $em->flush();
                    $successMessage = true;
                    //return $this->redirect($this->generateUrl('contact_success', array('name' => $data['name'])));
                }
            }
        return $this->render('SiteSavalizeBundle:Company:personalcompanysettings.html.twig', array('form' => $form->createView(), 'successMessage' => $successMessage));
    }
    
    /* company change-password settings */
    public function passwordcompanysettingsAction(){
        $request = $this->getRequest();
        $successMessage = false;
        $diffpasswd = false;
        $wrongpasswd = false;
        $data = array();
        $session = $request->getSession();
        $id = $session->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $obj = $em->getRepository('SiteSavalizeBundle:Company')->find($id);
        $passwd= $obj->getPassword();
        $collectionConstraint = new Collection(array(
                    'Old_password' => new NotBlank(),
                    'New_password' => new NotBlank(),
                    'Confirm_password' => new NotBlank()
                ));
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('Old_password','password')
                ->add('New_password','password')
                ->add('Confirm_password','password')
        ;
        $form = $formBuilder->getForm();
        if ($request->getMethod() == 'POST') {
           
                //fill the form data from the request
                $form->bindRequest($request);
                //check if the form values are correct
                if ($form->isValid()) {
                    $postdata = $form->getData();
                    
                    if($postdata['New_password'] == $postdata['Confirm_password']){
                        if ($passwd == \crypt($postdata['Old_password'],$passwd)){
                            $hashpasswd = \crypt($postdata['New_password']);
                            $em->getRepository('SiteSavalizeBundle:Company')->updatePassword($id,$hashpasswd);
                            $obj->setUpdatedAt(new \DateTime());
                            $em->flush();
                            $successMessage = true;
                        }
                        else {
                            $wrongpasswd = true;
                        }
                    }
                    else {
                        $diffpasswd = true;
                    }
                }
            }
        return $this->render('SiteSavalizeBundle:Company:passwordcompanysettings.html.twig', array('form' => $form->createView(), 'successMessage' => $successMessage, 'diffpasswd' => $diffpasswd, 'wrongpasswd' => $wrongpasswd));
    }
    public function contactAction() {
        //get the request object
        $request = $this->getRequest();
        $collectionConstraint = new Collection(array(
            'name' => new NotBlank(),
            'email' => array(new Email(), new NotBlank()),
            'subject' => array(),
            'message' => new NotBlank()
        ));
        $data = array();
        $data['subject'] = 'Contact For Support';
        //create the form
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('name')
                ->add('subject', null, array('required' => false))
                ->add('email', 'email', array('attr' => array('class' => 'email')))
                ->add('message', 'textarea',array('attr' => array('rows' => '10','cols' => '50')))
                ;
            $form = $formBuilder->getForm();
            //fill the form data from the request
            $form->bindRequest($request);
            //check if the form values are correct
            if ($form->isValid()) {
                $data = $form->getData();
                $to      = 'companyContact@savealize.com';
                $subject = $data['subject'];
                $message = $data['message'];
                $headers = 'From: '.$data['email']. "\r\n";

                mail($to, $subject, $message, $headers);
                return $this->render('SiteSavalizeBundle:Company:msgToUser.html.twig', array('msg' =>"Thank u ".$data['name']." for contacting us"));
            }
        
        return $this->render('SiteSavalizeBundle:Company:contact.html.twig', array('form' => $form->createView()));
    }

/*
    when company adds new product
*/
    public function viewProductAction(){
        $request = $this->getRequest();
        $session = $request->getSession();
        $company_id = $session->get('id');
        $role = $session->get('role');
        if ($role == 'company'){
        $em = $this->getDoctrine()->getEntityManager();
        $productsBrands = $em->getRepository('SiteSavalizeBundle:ProductBrand')->displayCompanyProducts($company_id);
        $brands = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Brand')->findByCompany(array('id' =>$company_id));

        $p = array();
        $b = array();

        for($i=0; $i<count($brands); $i++)
        {
            $b[$i]= $brands[$i]->getName();
        }
       
        for($i=0; $i<count($productsBrands); $i++)
        {
            $p[$i] = $productsBrands[$i]->getProduct()->getName();
        }

        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Category');
        $categories = $repository->categoryAutocomplete();
        }
        return $this->render('SiteSavalizeBundle:Company:newproduct.html.twig' , array(
                                                                                        'brands' => $b , 'products' => $p,
                                                                                        'categories' => $categories));
        
    }

    public function displayDataByAjaxAction(){
        $request = $this->getRequest();
        $session = $request->getSession();
        $company_id = $session->get('id');
        $role = $session->get('role');
        $req = $this->container->get('request');
        $category_id = $req->get('category_id');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:ProductBrand');
        $result = $repository->displayCategoryData($category_id);
        $brands = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Brand')->findByCompany(array('id' =>$company_id));
        $p = array();
        $b = array();

        for($i=0; $i<count($brands); $i++)
        {
            $b[$i]= $brands[$i]->getName();
        }
       
        for($i=0; $i<count($result); $i++)
        {
            $p[$i] = $result[$i]->getProduct()->getName();
        }
        return new Response(json_encode(array('brands' => $b , 'products' => $p)));
    }

    public function insertNewBrandAction(){
 // I NEED THE COMPANY_ID AS A PARAMETER
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $session = $request->getSession();
        $company_id = $session->get('id');
        $role = $session->get('role');
        if ($role == 'company'){
            $req = $this->container->get('request');
            $brand = $req->get('brand');
            $em = $this->getDoctrine()->getEntityManager();
            $repository = $em->getRepository('SiteSavalizeBundle:Brand');
            // check for the brand name in the database
            $brandDB = $repository->findOneByName($brand);
            $brandCompany = $em->getRepository('SiteSavalizeBundle:Company')->find($company_id);
            $companyName = $brandCompany->getName();
            // if not exists create a new brand with company = company_id
            if($brandDB == null){
                $brandDB = new Brand();
                $brandDB->setName($brand);
                $brandDB->setCompany($brandCompany);
                $brandDB->setConfirmed(1);
                $brandDB->setIsDeleted(0);
                $em->persist($brandDB);
                $title = $companyName.'new Brand';
                $content = 'check our new brand'. $brand .', its now available at the market ';
                $notification = new UserNotification();
                $notification->setTitle($title);
                $notification->setContent($content);
                $notification->setReleasedAt(new \DateTime());
                $em->persist($notification);

                $em->flush();
                $success = '<p class="alert alert-success"> data has been added successively</p>';
                return New Response($success);    
            }else{
                // if it already exists check on the company_id
                $brandCompany = $brandDB->getCompany();
                if(!$brandCompany){
                    $repo = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:ProductBrand');
                    $result = $repo->updateCompany($company_id, $brand);
                    $success = '<p class="alert alert-success"> data has been added successively</p>';
                    return New Response($success);
                }else{
                    // if the brand is assigned to a company ..
                    return New Response('<p class="alert alert-error"> this company is already assigned to another please contact us for more details</p>');

                }
            }
        }
    }
    
    public function insertNewProductAction(){
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $session = $request->getSession();
        $company_id = $session->get('id');
        $role = $session->get('role');

        $request = $this->container->get('request');
        $brand = $request->get('brand');
        $product = $request->get('product');
        $category = $request->get('category');
        $fileObject = $request->files->get('photoInput');
    if($brand && $product && $category && $fileObject){

        $em = $this->getDoctrine()->getEntityManager();        
        $repository = $em->getRepository('SiteSavalizeBundle:Product');
        $productDB = $repository->findOneByName($product);
        $repository = $em->getRepository('SiteSavalizeBundle:Brand');
        // check for the brand name in the database
        $brandDB = $repository->findOneByName($brand);
        $category = $em->getRepository('SiteSavalizeBundle:Category')->find($category);
        $brandCompany = $em->getRepository('SiteSavalizeBundle:Company')->find($company_id);
        $companyName = $brandCompany->getName();
        // if the product does not exists
        if((!$productDB) && (!$brandDB)){
        $imgext = $fileObject->guessExtension();
        $picturename = $brand ."." . $imgext;
        $dir = '/var/www/Save-alize/web/img/productImages';
        $fileObject->move($dir , $picturename);
            $productDB = new Product();
            $brandDB = new Brand();

            $brandDB->setName($brand);
            $brandDB->setCompany($brandCompany);
            $brandDB->setConfirmed(1);
            $brandDB->setIsDeleted(0);
            $em->persist($brandDB);

            $productDB->setName($product);
            $productDB->setCategory($category);
            $productDB->setIsDeleted(0);
            $productDB->setConfirmed(1);
            $em->persist($productDB);

            $product_brand = new ProductBrand();
            $product_brand->setProduct($productDB);
            $product_brand->setBrand($brandDB);
            $product_brand->setPicture($picturename);
            $em->persist($product_brand);

                $title = $companyName.'new Brand';
                $content = 'check our new Product,'. $brand .' it is available now in the market ';
                $notification = new UserNotification();
                $notification->setTitle($title);
                $notification->setContent($content);
                $notification->setReleasedAt(new \DateTime());
                $em->persist($notification);
                $em->flush();

            $success = '<p class="alert alert-success"> data has been added successively</p>';
            return New Response($success); 

        }else{
                // 7aga metkarara ....
                return New Response('<p class="alert alert-error"> this data already exists...</p>');
            }
        }else{

                return New Response('<p class="alert alert-error"> please enter the right data </p>');
            }       
    }
}

