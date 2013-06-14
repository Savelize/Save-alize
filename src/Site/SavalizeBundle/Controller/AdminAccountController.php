<?php

namespace Site\SavalizeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Site\SavalizeBundle\Entity\AdminAccount;
use Site\SavalizeBundle\Form\AdminAccountType;

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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SiteSavalizeBundle:AdminAccount')->findAll();

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

        return $this->render('SiteSavalizeBundle:AdminAccount:new.html.twig', array(
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
        //$session = $request->getSession();
        //$id = $session->get('id');
        $id = 3;
        $em = $this->getDoctrine()->getEntityManager();
        $obj = $em->getRepository('SiteSavalizeBundle:Admin')->find($id);
        
        $collectionConstraint = new Collection(array(
                    'First_Name' => new NotBlank(),
                    'Last_Name' => new NotBlank(),
                    'Username' => new NotBlank(),
                    'Email' => array(new Email(), new NotBlank())
                        ));
        $uid = $obj->getUser()->getId();
        $data['First_Name']= $obj->getUser()->getFname();
        $data['Last_Name']= $obj->getUser()->getLname();
        $data['Username']= $obj->getUser()->getUsername();
        $data['Email']= $obj->getUser()->getEmail();
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('First_Name')
                ->add('Last_Name')
                ->add('Username')
                ->add('Email', 'email', array('attr' => array('class' => 'email')))
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
        //$session = $request->getSession();
        //$id = $session->get('id');
        $id = 3;
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
}
