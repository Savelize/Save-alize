<?php

namespace Site\SavalizeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Site\SavalizeBundle\Entity\Admin;
use Site\SavalizeBundle\Form\AdminAccountType;

use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * AdminAccount controller.
 *
 */
class AdminAccountController extends Controller
{
    /**
     * Lists all AdminAccount entities.
     *
     */
    public function NewProductsApprovelAction()
    {
        $em = $this->getDoctrine()->getManager();
        $NotConfirmedProducts = $em->getRepository('SiteSavalizeBundle:Product')->findByConfirmed(0);
        $NotConfirmedBrands = $em->getRepository('SiteSavalizeBundle:Brand')->findByConfirmed(0);
        $productSelectTag = array();
        foreach ($NotConfirmedProducts as $prod) {
            $productSelectTag[$prod->getId()] = $prod->getName();
        }
        $brandSelectTag = array();
        foreach ($NotConfirmedBrands as $brand) {
            $brandSelectTag[$brand->getId()] = $brand->getName();
        }
        $collectionConstraint = new Collection(array(
                                'Products' => new NotBlank(),
        ));
        $data = array();
        
        $formBuilder = $this->createFormBuilder($data, array(
                                'validation_constraint' => $collectionConstraint,
                            ))
                            ->add('Products', 'choice', array(
                                'choices' => $productSelectTag,
                                'multiple' => true,
                                'attr' => array('class' => 'input-large','size'=>"20%",)
                                )
                                    )
                    ;
        $productForm = $formBuilder->getForm();
        
        $collectionConstraint = new Collection(array(
                                'Brands' => new NotBlank(),
        ));
        $data = array();
        $formBuilder = $this->createFormBuilder($data, array(
                                'validation_constraint' => $collectionConstraint,
                            ))
                            ->add('Brands', 'choice', array(
                                'choices' => $brandSelectTag,
                                'multiple' => true,
                                'attr' => array('class' => 'input-large','size'=>"20%",)
                                )
                                    )
                    ;
        $brandForm = $formBuilder->getForm();
        return $this->render('SiteSavalizeBundle:AdminAccount:NewProductsApprovel.html.twig', array('productForm' => $productForm->createView(),'brandForm' => $brandForm->createView()));
    }
    public function productApprovalSubmitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $prodRepo = $em->getRepository('SiteSavalizeBundle:Product');
        $NotConfirmedProducts=$prodRepo->findByConfirmed(0);
        $productSelectTag = array();
        foreach ($NotConfirmedProducts as $prod) {
            $productSelectTag[$prod->getId()] = $prod->getName();
        }
        $collectionConstraint = new Collection(array(
                                'Products' => new NotBlank(),
        ));
        $data = array();
        $formBuilder = $this->createFormBuilder($data, array(
                                'validation_constraint' => $collectionConstraint,
                            ))
                            ->add('Products', 'choice', array(
                                'choices' => $productSelectTag,
                                'multiple' => true,
                                'attr' => array('class' => 'input-large','size'=>"20%",)
                                )
                                    )
                    ;
        $productForm = $formBuilder->getForm();
        $request = $this->getRequest();
        $productForm->bindRequest($request);
        if ($productForm->isValid())
        {
            $data = $productForm->getData();
            $type=$request->get("submit");
            if($type=="Approve")
                $flag=1;
            else
                $flag=2;
            
            foreach ($data["Products"] as $prodID)
                {
                    $product=$prodRepo->find($prodID);
                    $product->setConfirmed($flag);
                }
                $em->flush();
                return $this->redirect($this->generateUrl('admin_New_ProductsApprovel'));
        }
        return new Response("data is not valid");
    }
    public function brandApprovalSubmitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $brandRepo=$em->getRepository('SiteSavalizeBundle:Brand');
        $NotConfirmedBrands = $brandRepo->findByConfirmed(0);
        $brandSelectTag = array();
        foreach ($NotConfirmedBrands as $brand) {
            $brandSelectTag[$brand->getId()] = $brand->getName();
        }
        $collectionConstraint = new Collection(array(
                                'Brands' => new NotBlank(),
        ));
        $data = array();
        $formBuilder = $this->createFormBuilder($data, array(
                                'validation_constraint' => $collectionConstraint,
                            ))
                            ->add('Brands', 'choice', array(
                                'choices' => $brandSelectTag,
                                'multiple' => true,
                                'attr' => array('class' => 'input-large','size'=>"20%",)
                                )
                                    )
                    ;
        $brandForm = $formBuilder->getForm();
        $request = $this->getRequest();
        $brandForm->bindRequest($request);
        $data = $brandForm->getData();
        
        if ($brandForm->isValid())
        {
            $data = $brandForm->getData();
            $type=$request->get("submit");
            if($type=="Approve")
                $flag=1;
            else
                $flag=2;
            
            foreach ($data["Brands"] as $brandID)
                {
                    $brand=$brandRepo->find($brandID);
                    $brand->setConfirmed($flag);
                }
                $em->flush();
                return $this->redirect($this->generateUrl('admin_New_ProductsApprovel'));
        }
        return new Response("data is not valid");
    }
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteSavalizeBundle:Admin')->findAll();

        return $this->render('SiteSavalizeBundle:AdminAccount:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new AdminAccount entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new AdminAccount();
        $form = $this->createForm(new AdminAccountType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('adminaccount_show', array('id' => $entity->getId())));
        }

        return $this->render('SiteSavalizeBundle:Admin:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new AdminAccount entity.
     *
     */
    public function newAction()
    {
        $entity = new AdminAccount();
        $form   = $this->createForm(new AdminAccountType(), $entity);

        return $this->render('SiteSavalizeBundle:AdminAccount:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminAccount entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:AdminAccount')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminAccount entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteSavalizeBundle:AdminAccount:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing AdminAccount entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:AdminAccount')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminAccount entity.');
        }

        $editForm = $this->createForm(new AdminAccountType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteSavalizeBundle:AdminAccount:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing AdminAccount entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:AdminAccount')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdminAccount entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AdminAccountType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('adminaccount_edit', array('id' => $id)));
        }

        return $this->render('SiteSavalizeBundle:AdminAccount:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminAccount entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteSavalizeBundle:AdminAccount')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AdminAccount entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('adminaccount'));
    }

    /**
     * Creates a form to delete a AdminAccount entity by id.
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
    
    /* admin personal settings */
    public function personaladminsettingsAction(){
        $request = $this->getRequest();
        $successMessage = false;
        $data = array();
        $session = $request->getSession();
        $id = $session->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $obj = $em->getRepository('SiteSavalizeBundle:Admin')->find($id);
        
        $collectionConstraint = new Collection(array(
                    'First_Name' => new NotBlank(),
                    'Last_Name' => new NotBlank(),
                    'Username' => new NotBlank(),
                    'Email' => array(new Email(), new NotBlank()),
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
        $uid = $obj->getUser()->getId();
        $data['First_Name']= $obj->getUser()->getFname();
        $data['Last_Name']= $obj->getUser()->getLname();
        $data['Username']= $obj->getUser()->getUsername();
        $data['Email']= $obj->getUser()->getEmail();
        $picturename= $obj->getUser()->getPicture();
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('First_Name')
                ->add('Last_Name')
                ->add('Username')
                ->add('Email', 'email', array('attr' => array('class' => 'email')))
                ->add('upload_your_photo','file', array('required' => false))
                ;
        $form = $formBuilder->getForm();
        if ($request->getMethod() == 'POST') {
           
                //fill the form data from the request
                $form->bindRequest($request);
                //check if the form values are correct
                if ($form->isValid()) {
                    $postdata = $form->getData();
                    $em->getRepository('SiteSavalizeBundle:User')->updateFirstName($uid,$postdata['First_Name']);
                    $em->getRepository('SiteSavalizeBundle:User')->updateLastName($uid,$postdata['Last_Name']);
                    $em->getRepository('SiteSavalizeBundle:User')->updateUsername($uid,$postdata['Username']);
                    $em->getRepository('SiteSavalizeBundle:User')->updateEmail($uid,$postdata['Email']);
                    if($postdata['upload_your_photo']){
                        $imgext = $postdata['upload_your_photo']->guessExtension();
                        $picturename = $postdata['Username'].".".$imgext;
                        $path = '/opt/lampp/htdocs/Save-alize/web/img/usersimgs';
                        $postdata['upload_your_photo']->move($path,$picturename);
                        $em->getRepository('SiteSavalizeBundle:User')->updatePicture($uid,$picturename);
                    }
                    $obj->getUser()->setUpdatedAt(new \DateTime());
                    $em->flush();
                    $successMessage = true;
                    //return $this->redirect($this->generateUrl('contact_success', array('name' => $data['name'])));
                }
            }
        return $this->render('SiteSavalizeBundle:AdminAccount:personaladminsettings.html.twig', array('form' => $form->createView(), 'successMessage' => $successMessage));
    }
    
    /* admin change-password settings */
    public function passwordadminsettingsAction(){
        $request = $this->getRequest();
        $successMessage = false;
        $diffpasswd = false;
        $wrongpasswd = false;
        $data = array();
        $session = $request->getSession();
        $id = $session->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $obj = $em->getRepository('SiteSavalizeBundle:Admin')->find($id);
        $uid = $obj->getUser()->getId();
        $passwd= $obj->getUser()->getPassword();
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
                            $em->getRepository('SiteSavalizeBundle:User')->updatePassword($uid,$hashpasswd);
                            $obj->getUser()->setUpdatedAt(new \DateTime());
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
        return $this->render('SiteSavalizeBundle:AdminAccount:passwordadminsettings.html.twig', array('form' => $form->createView(), 'successMessage' => $successMessage, 'diffpasswd' => $diffpasswd, 'wrongpasswd' => $wrongpasswd));
    }
    
     public function displayAdminChartDatesOnlyAction() {
        $session = $this->getRequest()->getSession();
        $userID = $session->get('id');
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');

        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Admin');

        $result = $repository->dateRangeData($startDate, $endDate);

//        for ($i = 0; $i < count($result); $i++) {
//            $pb['price'] = $result[$i]['price'];
//            $pb['name'] = $result[$i]['name'];
//        }

        return new Response(json_encode($result));
    }
        public function displayAdminChartDatesProductAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $productID = $request->get('productID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Admin');
        $result = $repository->adminChartFiltersProductOnly($startDate, $endDate, $productID);

        return new Response(json_encode($result));
    }

    public function displayAdminChartDatesBrandAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $brandID = $request->get('brandID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Admin');
        $result = $repository->adminChartFiltersBrandOnly($startDate, $endDate, $brandID);

        return new Response(json_encode($result));
    }

    public function displayAdminChartProductBrandAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $brandID = $request->get('brandID');
        $productID = $request->get('productID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Admin');
        $result = $repository->adminChartFiltersBrandOnly($startDate, $endDate, $brandID);

        return new Response(json_encode($result));
    }

    public function displayAdminChartProductBrandCategoryAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $brandID = $request->get('brandID');
        $productID = $request->get('productID');
        $categoryID = $request->get('categoryID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Admin');
        $result = $repository->adminChartFiltersProductBrandCategory($startDate, $endDate, $brandID, $productID, $categoryID);

        return new Response(json_encode($result));
    }

    public function displayAdminChartDatesCategoryAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $categoryID = $request->get('categoryID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Admin');
        $result = $repository->adminChartFilters($startDate, $endDate, $categoryID);
        for ($i = 0; $i < count($result); $i++) {
            $pb[$i]['price'] = $result[$i]['price'];
            $pb[$i]['products'] = $result[$i]['name'];
        }
        return new Response(json_encode($pb));
    }

    public function fromCategoryAdminAction() {
        $request = $this->container->get('request');
        $categoryID = $request->get('categoryID');
        $session = $this->getRequest()->getSession();
        $userID = $session->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $brandsOfCategory = $em->getRepository('SiteSavalizeBundle:ProductBrand')->productsandbrands($categoryID, $userID);

        $pb = array();
        for ($i = 0; $i < count($brandsOfCategory); $i++) {
            $pb['brands'][$i] = $brandsOfCategory[$i]->getBrand()->getName();
            $pb['products'][$i] = $brandsOfCategory[$i]->getProduct()->getName();
        }

        $pb['brands'] = \array_unique($pb['brands'], SORT_STRING);
        $pb['brands'] = \array_values($pb['brands']);

        return new Response(json_encode($pb));
    }
    
    
    
    
     public function displayReportChartPageAction() {
        $session = $this->getRequest()->getSession();
        $userID = $session->get('id');
        $role = $session->get('role');
        
        $repository = $this->getDoctrine()->getEntityManager();
        
        $result = $repository->getRepository('SiteSavalizeBundle:Category')->categoryAutocomplete();
        $brand = $repository->getRepository('SiteSavalizeBundle:Category')->brandAutocompleteAdmin();
        $product = $repository->getRepository('SiteSavalizeBundle:Category')->productAutocompleteAdmin();

        return $this->render('SiteSavalizeBundle:AdminAccount:admin_report.html.twig', array('categories' => json_encode($result), 'products' => json_encode($product), 'brands' => json_encode($brand)));
    }
}
