<?php

namespace App\Controller;

use App\Entity\Portion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PortionController extends Controller
{
    /**
     * Get all portions.
     *
     * @return Response
     *
     * @Route("/portion/", name="portion_index")
     * @Method("GET")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $portions = $em->getRepository(Portion::class)->findAll();

        return $this->render('portion/index.html.twig', array(
            'portions' => $portions
        ));
    }

    /**
     * Create a new portion entity.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/portion/new", name="portion_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $portion = new Portion();

        $form = $this->createForm('App\Form\PortionType', $portion);
        $form->handleRequest($request); // pb

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($portion);
            $em->flush();

            return $this->redirectToRoute('portion_show', array('id' => $portion->getId()));
        }


        return $this->render('portion/new.html.twig', array(
            'portion' => $portion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Show one portion entity.
     *
     * @param Portion $portion
     * @return Response
     *
     * @Route("/portion/{id}", name="portion_show")
     * @Method("GET")
     */
    public function show(Portion $portion)
    {
        $deleteForm = $this->createDeleteForm($portion);

        return $this->render('portion/show.html.twig', array(
            'portion' => $portion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Display a form to edit an existing portion entity.
     *
     * @param Request $request
     * @param Portion $portion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/portion/{id}/edit", name="portion_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Portion $portion)
    {
        $deleteForm = $this->createDeleteForm($portion);
        $editForm = $this->createForm('App\Form\PortionType', $portion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('portion_edit', array('id' => $portion->getId()));
        }

        return $this->render('portion/edit.html.twig', array(
            'portion' => $portion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Delete a portion entity.
     *
     * @param Request $request
     * @param Portion $portion
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/portion/{id}", name="portion_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Portion $portion)
    {
        $form = $this->createDeleteForm($portion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($portion);
            $em->flush();
        }

        return $this->redirectToRoute('portion_index');
    }

    /**
     * Create a form to delete a portion entity.
     *
     * @param Portion $portion
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Portion $portion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('portion_delete', array('id' => $portion->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}