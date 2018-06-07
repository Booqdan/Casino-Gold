<?php

namespace CasinoGoldBundle\Controller;

use CasinoGoldBundle\CasinoGoldBundle;
use CasinoGoldBundle\Entity\Cash;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Cash controller.
 *
 * @Route("cash")
 */
class CashController extends Controller
{
    /**
     * Lists all cash entities.
     *
     * @Route("/", name="cash_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cashes = $em->getRepository('CasinoGoldBundle:Cash')->findAll();

        return $this->render('cash/index.html.twig', array(
            'cashes' => $cashes,
        ));
    }

    /**
     * Creates a new cash entity.
     *
     * @Route("/new", name="cash_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cash = new Cash();
        $user = $this->getUser();
        $cash->setUser($user);

        $form = $this->createForm('CasinoGoldBundle\Form\CashType', $cash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cash);
            $em->flush();

            return $this->redirectToRoute('cash_show', array('id' => $cash->getId()));
        }

        return $this->render('cash/new.html.twig', array(
            'cash' => $cash,
            'form' => $form->createView()
        ));
    }

    /**
     * Finds and displays a cash entity.
     *
     * @Route("/{id}", name="cash_show")
     * @Method("GET")
     */
    public function showAction(Cash $cash)
    {
        return $this->render('cash/show.html.twig', array(
            'cash' => $cash,
        ));
    }

    /**
     * Displays a form to edit an existing cash entity.
     *
     * @Route("/{id}/edit", name="cash_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Cash $cash)
    {
        $deleteForm = $this->createDeleteForm($cash);
        $editForm = $this->createForm('CasinoGoldBundle\Form\CashType', $cash);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cash_edit', array('id' => $cash->getId()));
        }

        return $this->render('cash/edit.html.twig', array(
            'cash' => $cash,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a cash entity.
     *
     * @Route("/{id}", name="cash_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Cash $cash)
    {
        $form = $this->createDeleteForm($cash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cash);
            $em->flush();
        }

        return $this->redirectToRoute('cash_index');
    }

    /**
     * Creates a form to delete a cash entity.
     *
     * @param Cash $cash The cash entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Cash $cash)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cash_delete', array('id' => $cash->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
