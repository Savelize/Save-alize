<?php

namespace Site\SavalizeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
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

    public function historyDateSelectionAction() {
        $request = $this->container->get('request');
        $start = $request->get('start');
        $end = $request->get('end');

        $start = gmdate("Y-m-d H:i:s", $start);
        $end = gmdate("Y-m-d H:i:s", $end);

        $repository = $this->getDoctrine()->getEntityManager();


        $result = $repository->getRepository('SiteSavalizeBundle:History')->getMonthlyPurchases($start, $end);
        $resultArr = array();
        for ($i = 0; $i < count($result); $i++) {
            $myrepository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Product');
            $x = $result[$i]->getProductBrand()->getId();
            $productResult[$i] = $myrepository->find($x);
            $boughtAt[$i] = $result[$i]->getBaughtAt()->format('Y-m-d');

            $resultArr[$i] = ['title' => $productResult[$i]->getName(), 'data' => ['product' => $productResult[$i]->getName(),
                    'price' => (string) $result[$i]->getPrice()],
                'start' => $boughtAt[$i]];
        }

        return new Response(json_encode($resultArr), 200, array('Content-Type: application/json'));
    }

    public function usrhistoryAction() {
        $resultArr = $this->historyDateSelectionAction();
        return $this->render('SiteSavalizeBundle:Customer:page4.html.twig', array('monthlydata' => $resultArr));
    }

    public function shownotificationAction(Request $request, $id, $page) {
        $maxResults = 2;
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SiteSavalizeBundle:UserNotification');
        $count = $repo->count($id);
        $notfCount = $count['0']['notfCount'];
        //  echo $notfCount;
        // calculate the last page number
        $lastPageNumber = (int) ($notfCount / $maxResults);
        if (($notfCount % $maxResults) > 0) {
            $lastPageNumber++;
        }
        $notifications = $repo->showNotifications($id, $page, $maxResults);
        if (!$em) {
            throw $this->createNotFoundException('Unable to find Customer entity.');
        }
        return $this->render('SiteSavalizeBundle:Customer:notification.html.twig', array(
                    'notifications' => $notifications));
    }

    public function displayDummyChartAction() {
        $startDate = "2013-06-01";
        $endDate = "2013-06-30";

        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:History');
        $result = $repository->dateRangeData($startDate, $endDate);
//
//        for ($i = 0; $i < count($result); $i++) {
//            $resultArr[$i] = ['productName' => $result[$i]->getName(),
//                'productPrice' => $result[$i]->getPrice()];
//        }

        return new Response(json_encode($result));
     
    }

    public function displayEnteryChartPageAction() {

        $repository = $this->getDoctrine()->getEntityManager()->getRepository('SiteSavalizeBundle:Category');
        $result = $repository->categoryAutocomplete();
        return $this->render('SiteSavalizeBundle:Customer:chart_trial.html.twig', array('categories' => json_encode($result)));
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
                return $this->render('SiteSavalizeBundle:Customer:msgToUser.html.twig', array('msg' => "Thank u " . $data['name'] . " for contacting us"));
            }
        }
        return $this->render('SiteSavalizeBundle:Customer:contact.html.twig', array('form' => $form->createView()));
    }

    /* user personal settings */

    public function personalusersettingsAction() {
        //$request = $this->getRequest();
        $collectionConstraint = new Collection(array(
                    'First_Name' => new NotBlank(),
                    'Last_Name' => new NotBlank(),
                    'Username' => new NotBlank(),
                    'Password' => new NotBlank(),
                    'Confirm_password' => new NotBlank(),
                    'Email' => array(new Email(), new NotBlank()),
                    'Country' => new NotBlank(),
                    'City' => new NotBlank(),
                    'Region' => new NotBlank(),
                    'Age' => new NotBlank(),
                    'Salary' => new NotBlank()
                ));
        $data = array();
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('First_Name')
                ->add('Last_Name')
                ->add('Username')
                ->add('Password', 'password')
                ->add('Confirm_password', 'password')
                ->add('Email', 'email', array('attr' => array('class' => 'email')))
                ->add('Country')
                ->add('City')
                ->add('Region')
                ->add('Age')
                ->add('Salary')
        ;
        $form = $formBuilder->getForm();
        return $this->render('SiteSavalizeBundle:Customer:personalusersettings.html.twig', array('form' => $form->createView()));
    }
    
    /* user change-password settings */
    public function passwordusersettingsAction(){
        return $this->render('SiteSavalizeBundle:Customer:passwordusersettings.html.twig');
    }
    /* user linked-account settings */
    public function linkedusersettingsAction(){
        return $this->render('SiteSavalizeBundle:Customer:linkedusersettings.html.twig');
    }

}
