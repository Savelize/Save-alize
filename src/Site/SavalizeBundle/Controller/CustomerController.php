<?php

//

namespace Site\SavalizeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;
use Site\SavalizeBundle\Entity\Customer;
use Site\SavalizeBundle\Entity\History;
use Site\SavalizeBundle\Entity\UserNotification;
use Site\SavalizeBundle\Entity\Product;
use Site\SavalizeBundle\Entity\ProductBrand;
use Site\SavalizeBundle\Entity\Brand;

/**
 * UserAccount controller.
 *
 */
class CustomerController extends Controller {

    /**
     * Lists all UserAccount entities.
     *
     */
    public function getProductAction() {
        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getEntityManager();

        $customerId = $session->get('id');
        $catId = $request->get("catId");
        $data = array();

        $customerRep = $em->getRepository("SiteSavalizeBundle:Customer");
        $customer = $customerRep->find($customerId);

        $historyRep = $em->getRepository("SiteSavalizeBundle:History");
        $history = $historyRep->findByCustomer($customer);

        $catRep = $em->getRepository("SiteSavalizeBundle:Category");
        $cat = $catRep->find($catId);

        foreach ($history as $row) {
            $product = $row->getProductBrand()->getProduct();
            if ($product->getCategory() == $cat) {

                $data[] = $product->getName();
            }
        }


        $productRep = $em->getRepository("SiteSavalizeBundle:Product");
        $products = $productRep->findBy(array('category' => $cat, 'confirmed' => 1));
        foreach ($products as $product) {
            $data[] = $product->getName();
        }
        $data = \array_unique($data, SORT_STRING);
        $data = \array_values($data);
        return new Response(json_encode($data));
    }

    public function getBrandsAction() {
        $data = array();
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getEntityManager();
        $session = $request->getSession();

        $customerId = $session->get('id');
        $customerRep = $em->getRepository("SiteSavalizeBundle:Customer");
        $customer = $customerRep->find($customerId);

        $historyRep = $em->getRepository("SiteSavalizeBundle:History");
        $history = $historyRep->findByCustomer($customer);

        foreach ($history as $row) {
            $brand = $row->getProductBrand()->getBrand();
            $data[] = $brand->getName();
        }

        $brandRep = $em->getRepository("SiteSavalizeBundle:Brand");
        $brands = $brandRep->findBy(array('confirmed' => 1));
        foreach ($brands as $brand) {
            $data[] = $brand->getName();
        }
        $data = \array_unique($data, SORT_STRING);
        $data = \array_values($data);
        return new Response(json_encode($data));
    }

    public function addProductAction() {
        $session = $this->getRequest()->getSession();
        $username = $session->get('userName');

        $em = $this->getDoctrine()->getEntityManager();
        $userRep = $em->getRepository("SiteSavalizeBundle:User");
        $user = $userRep->findOneByUsername($username);
        if ($user) {
            $customerRep = $em->getRepository("SiteSavalizeBundle:Customer");
            $customer = $customerRep->findOneByUser($user);
            if ($customer) { {
                    $CategoryRep = $em->getRepository("SiteSavalizeBundle:Category");
                    $categories = $CategoryRep->findAll();


                    $catSelectTag = array();
                    foreach ($categories as $cat) {
                        $catSelectTag[$cat->getId()] = $cat->getName();
                    }
                    $collectionConstraint = new Collection(array(
                                'Categories' => new NotBlank(),
                                'Product' => new NotBlank(),
                                'Brand' => new NotBlank(),
                                'Price' => array(new NotBlank(), new Type("integer")),
                                'Quantity' => array(new NotBlank(), new Type("integer")),
                                'Date' => array(new NotBlank(), new Date()),
                            ));

                    $data = array(
                        'Date' => new \DateTime()
                    );
                    //create the form

                    $formBuilder = $this->createFormBuilder($data, array(
                                'validation_constraint' => $collectionConstraint,
                            ))
                            ->add('Categories', 'choice', array('choices' => $catSelectTag, 'attr' => array('class' => 'input-large')))
                            ->add('Product', null, array('required' => true, 'attr' => array('class' => 'input-large', 'id' => 'product')))
                            ->add('Brand', null, array('required' => true, 'attr' => array('class' => 'input-large')))
                            ->add('Price', "integer", array('required' => true, 'attr' => array('class' => 'input-large')))
                            ->add('Quantity', "integer", array('required' => true, 'attr' => array('class' => 'input-large')))
                            ->add('Date', "date", array('required' => true, 'attr' => array('class' => '')))

                    ;
                    $addproductForm = $formBuilder->getForm();
                    $request = $this->getRequest();
                    if ($request->getMethod() == 'POST') {
                        //fill the form data from the request 
                        $addproductForm->bindRequest($request);
                        if ($addproductForm->isValid()) {
                            $data = $addproductForm->getData();
                            $productRep = $em->getRepository("SiteSavalizeBundle:Product");
                            $product = $productRep->findOneByName($data['Product']);
                            $catRep = $em->getRepository("SiteSavalizeBundle:Category");
                            $cat = $catRep->find($data['Categories']);
                            if (!$product) {
                                $product = new Product();
                                $product->setConfirmed(0);
                                $product->setIsDeleted(0);
                                $product->setName($data['Product']);
                                $product->setCategory($cat);
                                $em->persist($product);
                                $em->flush($product);
                            }
                            $brandRep = $em->getRepository("SiteSavalizeBundle:Brand");
                            $brand = $brandRep->findOneByName($data['Brand']);
                            if (!$brand) {
                                $brand = new Brand();
                                $brand->setName($data['Brand']);
                                $brand->setCompany(null);
                                $brand->setConfirmed(0);
                                $brand->setIsDeleted(0);
                                $em->persist($brand);
                                $em->flush($brand);
                            }
                            $ProductbrandRep = $em->getRepository("SiteSavalizeBundle:ProductBrand");
                            $Productbrand = $ProductbrandRep->findOneBy(array("brand" => $brand, "product" => $product));
                            if (!$Productbrand) {
                                $Productbrand = new ProductBrand();
                                $Productbrand->setBrand($brand);
                                $Productbrand->setCategory($cat);
                                $Productbrand->setPicture("anonymos.jpg");
                                $Productbrand->setProduct($product);
                                $em->persist($Productbrand);
                                $em->flush($Productbrand);
                            }
                            $history = new History();
                            $history->setBaughtAt($data['Date']);
                            $history->setCustomer($customer);
                            $history->setPrice($data['Price']);
                            $history->setProductBrand($Productbrand);
                            $history->setQuantity($data['Quantity']);
                            $em->persist($history);
                            $em->flush($history);
                        } else {
                            return $this->render('SiteSavalizeBundle:Customer:msgToUser.html.twig', array("msg" => "form is not valid"));
                        }
                        return $this->render('SiteSavalizeBundle:Customer:addProducts.html.twig', array('form' => $addproductForm->createView(), 'sucess' => true));
                    }
                    return $this->render('SiteSavalizeBundle:Customer:addProducts.html.twig', array('form' => $addproductForm->createView(), 'sucess' => false));
                }
            }
            return $this->render('SiteSavalizeBundle:Default:error.html.twig', array("msg" => "you are not authorized"));
        }
    }

    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteSavalizeBundle:Customer')->findAll();

        return $this->render('SiteSavalizeBundle:Customer:index.html.twig', array(
                    'entities' => $entities,
                ));
    }

    /**
     * Creates a new UserAccount entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new UserAccount();
        $form = $this->createForm(new UserAccountType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('useraccount_show', array('id' => $entity->getId())));
        }

        return $this->render('SiteSavalizeBundle:Customer:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Displays a form to create a new UserAccount entity.
     *
     */
    public function newAction() {
        $entity = new UserAccount();
        $form = $this->createForm(new UserAccountType(), $entity);

        return $this->render('SiteSavalizeBundle:Customer:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                ));
    }

    /**
     * Finds and displays a UserAccount entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserAccount entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteSavalizeBundle:Customer:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing UserAccount entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $editForm = $this->createForm(new UserAccountType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SiteSavalizeBundle:Customer:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Edits an existing UserAccount entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteSavalizeBundle:Customer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new UserAccountType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('useraccount_edit', array('id' => $id)));
        }

        return $this->render('SiteSavalizeBundle:Customer:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                ));
    }

    /**
     * Deletes a UserAccount entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteSavalizeBundle:Customer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserAccount entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('useraccount'));
    }

    /**
     * Creates a form to delete a UserAccount entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }

    /* user history page 4 */
    /* for calendar from calendar */

    public function historyDateSelectionAction($userID) {
        $request = $this->container->get('request');
        $start = $request->get('start');
        $end = $request->get('end');

        $start = gmdate("Y-m-d H:i:s", $start);
        $end = gmdate("Y-m-d H:i:s", $end);

        $repository = $this->getDoctrine()->getEntityManager();
        $result = $repository->getRepository('SiteSavalizeBundle:History')->getMonthlyPurchases($start, $end, $userID);
        $resultArr = array();
        for ($i = 0; $i < count($result); $i++) {
            $myrepository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Product');
            $x = $result[$i]->getProductBrand()->getId();
            $productResult[$i] = $myrepository->find($x);
            $boughtAt[$i] = $result[$i]->getBaughtAt()->format('Y-m-d');

            $resultArr[$i] = array('title' => $productResult[$i]->getName(), 'data' => array('product' => $productResult[$i]->getName(),
                    'price' => (string) $result[$i]->getPrice()),
                'start' => $boughtAt[$i]);
        }

        return new Response(json_encode($resultArr), 200, array('Content-Type: application/json'));
    }

    /* calls the action and renders the twig */

    public function usrhistoryAction() {
        $session = $this->getRequest()->getSession();
        $userID = $session->get('id');

        $resultArr = $this->historyDateSelectionAction($userID);
        return $this->render('SiteSavalizeBundle:Customer:user_calendar.html.twig', array('monthlydata' => $resultArr));
    }

    public function shownotificationAction($page) {
        $maxResults = 2;
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SiteSavalizeBundle:UserNotification');
        $count = $repo->count(1);
        $notfCount = $count['0']['notfCount'];

        // calculate the last page number
        $lastPageNumber = (int) ($notfCount / $maxResults);
        if (($notfCount % $maxResults) > 0) {
            $lastPageNumber++;
        }
        $notifications = $repo->showNotifications(1, $page, $maxResults);
        if (!$em) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }
        return $this->render('SiteSavalizeBundle:Customer:notification.html.twig', array(
                    'notifications' => $notifications,
                    'page' => $page,
                    'lastPageNumber' => $lastPageNumber));
    }

    public function insertUserNotificationAction($title, $content, $type) {

        $em = $this->getDoctrine()->getEntityManager();
        $notification = new UserNotification();
        $notification->setTitle($title);
        $notification->setContent($content);
        $notification->setReleasedAt(new \DateTime());
        $em->persist($notification);
        $em->flush();
        exit;
    }

    // public function displaynotification($content){
    //     $em = $this->getDoctrine()->getEntityManager();
    //     $customer = $em->getRepository('SiteSavalizeBundle:Customer')->find(1);
    //     $notification = $em->getRepository('SiteSavalizeBundle:UserNotification')->find($notf_id);
    // }

    public function showNewProductDetailsAction() {
        $request = $this->container->get('request');
        $content = $request->get('content');
        $releasedat = $request->get('releasedat');
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('SiteSavalizeBundle:User')->find(1);
        $user_name = $user->getUsername();
        return $this->render('SiteSavalizeBundle:Customer:showewroductdetails.html.twig', array('username' => $user_name,
                    'content' => $content,
                    'releasedat' => $releasedat));
    }

    public function displayUserChartDatesProductAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $productID = $request->get('productID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:History');
        $result = $repository->userChartFiltersProductOnly($startDate, $endDate, $productID);

        return new Response(json_encode($result));
    }

    public function displayUserChartDatesBrandAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $brandID = $request->get('brandID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:History');
        $result = $repository->userChartFiltersBrandOnly($startDate, $endDate, $brandID);

        return new Response(json_encode($result));
    }

    public function displayUserChartProductBrandAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $brandID = $request->get('brandID');
        $productID = $request->get('productID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:History');
        $result = $repository->userChartFiltersBrandOnly($startDate, $endDate, $brandID);

        return new Response(json_encode($result));
    }

    public function displayUserChartProductBrandCategoryAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $brandID = $request->get('brandID');
        $productID = $request->get('productID');
        $categoryID = $request->get('categoryID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:History');
        $result = $repository->userChartFiltersProductBrandCategory($startDate, $endDate, $brandID, $productID, $categoryID);

        return new Response(json_encode($result));
    }

    public function displayUserChartDatesCategoryAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $categoryID = $request->get('categoryID');
        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:History');
        $result = $repository->userChartFilters($startDate, $endDate, $categoryID);
        for ($i = 0; $i < count($result); $i++) {
            $pb[$i]['price'] = $result[$i]['price'];
            $pb[$i]['products'] = $result[$i]['name'];
        }
        return new Response(json_encode($pb));
    }

    /* autocomplete of brands and products in user report */

    public function fromCategoryAction() {
        $request = $this->container->get('request');
        $categoryID = $request->get('categoryID');
        $em = $this->getDoctrine()->getEntityManager();
        $brandsOfCategory = $em->getRepository('SiteSavalizeBundle:ProductBrand')->productsandbrands($categoryID);
        $productsOfCategory = $em->getRepository('SiteSavalizeBundle:ProductBrand')->productsandbrands($categoryID);
        $pb = array();
        for ($i = 0; $i < count($brandsOfCategory); $i++) {
            $pb['brands'][$i] = $brandsOfCategory[$i]->getBrand()->getName();
            $pb['products'][$i] = $brandsOfCategory[$i]->getProduct()->getName();
        }
        return new Response(json_encode($pb));
    }

    public function displayUserChartDatesOnlyAction() {
        $request = $this->container->get('request');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');

        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:History');

        $result = $repository->dateRangeData($startDate, $endDate);

//        for ($i = 0; $i < count($result); $i++) {
//            $pb['price'] = $result[$i]['price'];
//            $pb['name'] = $result[$i]['name'];
//        }

        return new Response(json_encode($result));
    }

    public function displayEnteryChartPageAction() {
//        $session = $this->getRequest()->getSession();
//        $userID = $session->get('id');
//        
//        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Category');
        $result = $repository->categoryAutocomplete();
//        $brand = $repository->brandAutocomplete();
//        $product = $repository->productAutocomplete();
        $product = $this->getProductAction();
        $brand = $this->getBrandsAction();
        
        return $this->render('SiteSavalizeBundle:Customer:user_report.html.twig', array('categories' => json_encode($result), 'products' => $product, 'brands' => $brand));
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
                ->add('message', 'textarea', array('attr' => array('rows' => '10', 'cols' => '50')))
        ;
        $form = $formBuilder->getForm();
        //check if this is the user posted his data
        if ($request->getMethod() == 'POST') {
            //fill the form data from the request
            $form->bindRequest($request);
            //check if the form values are correct
            if ($form->isValid()) {
                $data = $form->getData();
                //return $this->redirect($this->generateUrl('contact_success',array('name' => $data['name'])));
                $to = 'customerContact@savealize.com';
                $subject = $data['subject'];
                $message = $data['message'];
                $headers = 'From: ' . $data['email'] . "\r\n";

                mail($to, $subject, $message, $headers);
                return $this->render('SiteSavalizeBundle:Customer:msgToUser.html.twig', array('msg' => "Thank u " . $data['name'] . " for contacting us"));
            }
        }
        return $this->render('SiteSavalizeBundle:Customer:contact.html.twig', array('form' => $form->createView()));
    }

    /* user personal settings */

    public function personalusersettingsAction() {
        $request = $this->getRequest();
        $successMessage = false;
        $data = array();
        $session = $request->getSession();
        $id = $session->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $obj = $em->getRepository('SiteSavalizeBundle:Customer')->find($id);
        $collectionConstraint = new Collection(array(
                    'First_Name' => new NotBlank(),
                    'Last_Name' => new NotBlank(),
                    'Username' => new NotBlank(),
                    'Email' => array(new Email(), new NotBlank()),
                    'Country' => new NotBlank(),
                    'City' => new NotBlank(),
                    'Region' => new NotBlank(),
                    'Age' => new NotBlank(),
                    'Salary' => new NotBlank(),
                    'upload_your_photo' => new Image(array(
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
        $data['First_Name'] = $obj->getUser()->getFname();
        $data['Last_Name'] = $obj->getUser()->getLname();
        $data['Username'] = $obj->getUser()->getUsername();
        $data['Email'] = $obj->getUser()->getEmail();
        $data['Country'] = $obj->getCountry();
        $data['City'] = $obj->getCity();
        $data['Region'] = $obj->getRegion();
        $data['Age'] = $obj->getAge();
        $data['Salary'] = $obj->getSalary();
        $picturename = $obj->getUser()->getPicture();
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('First_Name')
                ->add('Last_Name')
                ->add('Username')
                ->add('Email', 'email', array('attr' => array('class' => 'email')))
                ->add('Country')
                ->add('City')
                ->add('Region')
                ->add('Age')
                ->add('Salary')
                ->add('upload_your_photo', 'file', array('required' => false))
        ;
        $form = $formBuilder->getForm();
        if ($request->getMethod() == 'POST') {

            //fill the form data from the request
            $form->bindRequest($request);
            //check if the form values are correct
            if ($form->isValid()) {
                $postdata = $form->getData();
                $em->getRepository('SiteSavalizeBundle:User')->updateFirstName($uid, $postdata['First_Name']);
                $em->getRepository('SiteSavalizeBundle:User')->updateLastName($uid, $postdata['Last_Name']);
                $em->getRepository('SiteSavalizeBundle:User')->updateUsername($uid, $postdata['Username']);
                $em->getRepository('SiteSavalizeBundle:User')->updateEmail($uid, $postdata['Email']);
                $em->getRepository('SiteSavalizeBundle:Customer')->updateCountry($id, $postdata['Country']);
                $em->getRepository('SiteSavalizeBundle:Customer')->updateCity($id, $postdata['City']);
                $em->getRepository('SiteSavalizeBundle:Customer')->updateRegion($id, $postdata['Region']);
                $em->getRepository('SiteSavalizeBundle:Customer')->updateAge($id, $postdata['Age']);
                $em->getRepository('SiteSavalizeBundle:Customer')->updateSalary($id, $postdata['Salary']);
                if ($postdata['upload_your_photo']) {
                    $imgext = $postdata['upload_your_photo']->guessExtension();
                    $picturename = $postdata['Username'] . "." . $imgext;
                    $path = '/opt/lampp/htdocs/Save-alize/web/img/usersimgs';
                    $postdata['upload_your_photo']->move($path, $picturename);
                    $em->getRepository('SiteSavalizeBundle:User')->updatePicture($uid, $picturename);
                }
                $obj->getUser()->setUpdatedAt(new \DateTime());
                $em->flush();
                $successMessage = true;
                //$request->getSession()->getFlashBag()->add('successMessage', true);
            }
        }

        return $this->render('SiteSavalizeBundle:Customer:personalusersettings.html.twig', array('form' => $form->createView(), 'successMessage' => $successMessage));
    }

    /* user change-password settings */

    public function passwordusersettingsAction() {
        $request = $this->getRequest();
        $successMessage = false;
        $diffpasswd = false;
        $wrongpasswd = false;
        $data = array();
        $session = $request->getSession();
        $id = $session->get('id');
        $em = $this->getDoctrine()->getEntityManager();
        $obj = $em->getRepository('SiteSavalizeBundle:Customer')->find($id);
        $uid = $obj->getUser()->getId();
        $passwd = $obj->getUser()->getPassword();
        $collectionConstraint = new Collection(array(
                    'Old_password' => new NotBlank(),
                    'New_password' => new NotBlank(),
                    'Confirm_password' => new NotBlank()
                ));
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('Old_password', 'password')
                ->add('New_password', 'password')
                ->add('Confirm_password', 'password')
        ;
        $form = $formBuilder->getForm();
        if ($request->getMethod() == 'POST') {

            //fill the form data from the request
            $form->bindRequest($request);
            //check if the form values are correct
            if ($form->isValid()) {
                $postdata = $form->getData();

                if ($postdata['New_password'] == $postdata['Confirm_password']) {
                    if ($passwd == \crypt($postdata['Old_password'], $passwd)) {
                        $hashpasswd = \crypt($postdata['New_password']);
                        $em->getRepository('SiteSavalizeBundle:User')->updatePassword($uid, $hashpasswd);
                        $obj->getUser()->setUpdatedAt(new \DateTime());
                        $em->flush();
                        $successMessage = true;
                    } else {
                        $wrongpasswd = true;
                    }
                } else {
                    $diffpasswd = true;
                }
            }
        }
        return $this->render('SiteSavalizeBundle:Customer:passwordusersettings.html.twig', array('form' => $form->createView(), 'successMessage' => $successMessage, 'diffpasswd' => $diffpasswd, 'wrongpasswd' => $wrongpasswd));
    }

    /* user linked-account settings */

    public function linkedusersettingsAction() {
        $request = $this->getRequest();
        $data = array();
        $em = $this->getDoctrine()->getEntityManager();
        $collectionConstraint = new Collection(array(
                    'Choose_a_user' => new NotBlank(),
                    'Message' => array()
                ));
        $data['Choose_a_user'] = 'By username';
        $data['Message'] = 'to be send to the user (optional)';
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('Choose_a_user')
                ->add('Message', 'textarea', array('required' => false, 'attr' => array('cols' => '100', 'rows' => '10', 'style' => 'resize:none')))
        ;
        $form = $formBuilder->getForm();
        /*
          if ($request->getMethod() == 'POST') {

          //fill the form data from the request
          $form->bindRequest($request);
          //check if the form values are correct
          if ($form->isValid()) {
          $postdata = $form->getData();
          //return $this->redirect($this->generateUrl('contact_success', array('name' => $data['name'])));
          }
          }
         * 
         */
        return $this->render('SiteSavalizeBundle:Customer:linkedusersettings.html.twig', array('form' => $form->createView()));
    }

}
