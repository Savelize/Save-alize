<?php

namespace Site\SavalizeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Site\SavalizeBundle\Entity\Company;
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
        $data = array();
        //$session = $request->getSession();
        //$id = $session->get('id');
        $id = 1;
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
            'upload_your_photo' => array()
            
        ));
        $data['Name']= $obj->getName();
        $data['Username']= $obj->getUsername();
        $data['Email']= $obj->getEmail();
        $data['Telephone']= $obj->getTelephone();
        $data['Country']= $obj->getCountry();
        $data['City']= $obj->getCity();
        $data['Region']= $obj->getRegion();
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
                    //return $this->redirect($this->generateUrl('contact_success', array('name' => $data['name'])));
                }
            }
        return $this->render('SiteSavalizeBundle:Company:personalcompanysettings.html.twig', array('form' => $form->createView()));
    }
    
    /* company change-password settings */
    public function passwordcompanysettingsAction(){
        $request = $this->getRequest();
        $data = array();
        $em = $this->getDoctrine()->getEntityManager();
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
        return $this->render('SiteSavalizeBundle:Company:passwordcompanysettings.html.twig', array('form' => $form->createView()));
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
                //return $this->redirect($this->generateUrl('contact_success',array('name' => $data['name'])));
                return $this->render('SiteSavalizeBundle:Company:msgToUser.html.twig', array('msg' =>"Thank u ".$data['name']." for contacting us"));
            }
        
        return $this->render('SiteSavalizeBundle:Company:contact.html.twig', array('form' => $form->createView()));
    }
}
