<?php

namespace App\Controller;

use App\Entity\Ingredient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IngredientController extends Controller
{
    /**
     * Get all ingredients.
     * @return Response
     *
     * @Route("/ingredient", name="ingredient_index")
     * @Method("GET")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $ingredients = $em->getRepository(Ingredient::class)->findAll();

        return $this->render('ingredient/index.html.twig', array(
            'ingredients' => $ingredients
        ));
    }

    /**
     * Create a new ingredient entity.
     *
     * @param Request $request
     * @param Ingredient $ingredient
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/ingredient/new", name="ingredient_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $ingredient = new Ingredient();
        $form = $this->createForm('App\Form\IngredientType', $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingredient);
            $em->flush();

            return $this->redirectToRoute('ingredient_show', array('id' => $ingredient->getId()));
        }

        return $this->render('ingredient/new.html.twig', array(
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ));
    }

    /**
     * Show one ingredient entity.
     *
     * @param Ingredient $ingredient
     * @return Response
     *
     * @Route("/ingredient/{id}", name="ingredient_show")
     * @Method("GET")
     */
    public function show(Ingredient $ingredient)
    {
        $deleteForm = $this->createDeleteForm($ingredient);

        return $this->render('ingredient/show.html.twig', array(
            'ingredient' => $ingredient,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Display a form to edit an existing ingredient entity.
     *
     * @param Request $request
     * @param Ingredient $ingredient
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/ingredient/{id}/edit", name="ingredient_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Ingredient $ingredient)
    {
        $deleteForm = $this->createDeleteForm($ingredient);
        $editForm = $this->createForm('App\Form\IngredientType', $ingredient);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ingredient_edit', array('id' => $ingredient->getId()));
        }

        return $this->render('ingredient/edit.html.twig', array(
            'ingredient' => $ingredient,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Delete an ingredient entity.
     *
     * @param Request $request
     * @param Ingredient $ingredient
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/ingredient/{id}", name="ingredient_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Ingredient $ingredient)
    {
        $form = $this->createDeleteForm($ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ingredient);
            $em->flush();
        }

        return $this->redirectToRoute('ingredient_index');
    }

    /**
     * Create a form to delete an ingredient entity.
     *
     * @param Ingredient $ingredient
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Ingredient $ingredient)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ingredient_delete', array('id' => $ingredient->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}