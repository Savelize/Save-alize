<?php

namespace Site\SavalizeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
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
        $collectionConstraint = new Collection(array(
            'First_Name' => new NotBlank(),
            'Last_Name' => new NotBlank(),
            'Username' => new NotBlank(),
            'Password' => new NotBlank(),
            'Confirm_password' => new NotBlank(),
            'Email' => array(new Email(), new NotBlank()),
            'upload_your_photo' => array()
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
                ->add('upload_your_photo','file', array('required' => false))
        ;
        $form = $formBuilder->getForm();
        //check if this is the user posted his data
        if ($request->getMethod() == 'POST') {
                //fill the form data from the request
                $form->bindRequest($request);
                //check if the form values are correct
                if ($form->isValid()) {
                    //$fdata = $form->getData();
                    return $this->redirect($this->generateUrl('site_savalize_homepage'));
                }
            }
        return $this->render('SiteSavalizeBundle:AdminAccount:personaladminsettings.html.twig', array('form' => $form->createView()));
    }
    
    /* admin change-password settings */
    public function passwordadminsettingsAction(){
        return $this->render('SiteSavalizeBundle:AdminAccount:passwordadminsettings.html.twig');
    }
}
