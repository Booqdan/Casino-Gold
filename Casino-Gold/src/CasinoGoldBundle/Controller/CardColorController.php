<?php

namespace CasinoGoldBundle\Controller;

use CasinoGoldBundle\Entity\CardColor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Cardcolor controller.
 *
 * @Route("cardcolor")
 */
class CardColorController extends Controller
{
    /**
     * Lists all cardColor entities.
     *
     * @Route("/", name="cardcolor_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cardColors = $em->getRepository('CasinoGoldBundle:CardColor')->findAll();

        return $this->render('cardcolor/index.html.twig', array(
            'cardColors' => $cardColors,
        ));
    }

    /**
     * Creates a new cardColor entity.
     *
     * @Route("/new", name="cardcolor_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $cardColor = new Cardcolor();
        $form = $this->createForm('CasinoGoldBundle\Form\CardColorType', $cardColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cardColor);
            $em->flush();

            return $this->redirectToRoute('cardcolor_show', array('id' => $cardColor->getId()));
        }

        return $this->render('cardcolor/new.html.twig', array(
            'cardColor' => $cardColor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a cardColor entity.
     *
     * @Route("/{id}", name="cardcolor_show")
     * @Method("GET")
     */
    public function showAction(CardColor $cardColor)
    {
        $deleteForm = $this->createDeleteForm($cardColor);

        return $this->render('cardcolor/show.html.twig', array(
            'cardColor' => $cardColor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing cardColor entity.
     *
     * @Route("/{id}/edit", name="cardcolor_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, CardColor $cardColor)
    {
        $deleteForm = $this->createDeleteForm($cardColor);
        $editForm = $this->createForm('CasinoGoldBundle\Form\CardColorType', $cardColor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cardcolor_edit', array('id' => $cardColor->getId()));
        }

        return $this->render('cardcolor/edit.html.twig', array(
            'cardColor' => $cardColor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a cardColor entity.
     *
     * @Route("/{id}", name="cardcolor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, CardColor $cardColor)
    {
        $form = $this->createDeleteForm($cardColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($cardColor);
            $em->flush();
        }

        return $this->redirectToRoute('cardcolor_index');
    }

    /**
     * Creates a form to delete a cardColor entity.
     *
     * @param CardColor $cardColor The cardColor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CardColor $cardColor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cardcolor_delete', array('id' => $cardColor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
