<?php

namespace Site\SavalizeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Site\SavalizeBundle\Entity\Company;
use Site\SavalizeBundle\Form\CompanyType;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

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
        $collectionConstraint = new Collection(array(
            'Name' => new NotBlank(),
            'Username' => new NotBlank(),
            'Password' => new NotBlank(),
            'Confirm_password' => new NotBlank(),
            'Email' => array(new Email(), new NotBlank()),
            'Telephone' => array(),
            'Country' => array(),
            'City' => array(),
            'Region' => array(),
            'upload_your_photo' => array()
            
        ));
        $data = array();
        $formBuilder = $this->createFormBuilder($data, array(
                    'validation_constraint' => $collectionConstraint,
                ))
                ->add('Name')
                ->add('Username')
                ->add('Password', 'password')
                ->add('Confirm_password', 'password')
                ->add('Email', 'email', array('attr' => array('class' => 'email')))
                ->add('Telephone','text',array('required' => false))
                ->add('Country','text',array('required' => false))
                ->add('City','text',array('required' => false))
                ->add('Region','text',array('required' => false))
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
        return $this->render('SiteSavalizeBundle:Company:personalcompanysettings.html.twig', array('form' => $form->createView()));
    }
}
