<?php

namespace App\Controller;

use App\Entity\Portion;
use App\Entity\Recipe;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController extends Controller
{
    /**
     * Get all recipes.
     *
     * @return Response
     *
     * @Route("/recipe/", name="recipe_index")
     * @Method("GET")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $recipes = $em->getRepository(Recipe::class)->findAll();

        return $this->render('recipe/index.html.twig', array(
            'recipes' => $recipes
        ));
    }

    /**
     * Create a new recipe entity.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/recipe/new", name="recipe_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $recipe = new Recipe();
        $form = $this->createForm('App\Form\RecipeType', $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recipe);
            $em->flush();

            return $this->redirectToRoute('recipe_show', array('id' => $recipe->getId()));
        }

        return $this->render('recipe/new.html.twig', array(
            'recipe' => $recipe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Show one recipe entity.
     *
     * @param Recipe $recipe
     * @return Response
     *
     * @Route("/recipe/{id}", name="recipe_show")
     * @Method("GET")
     */
    public function show(Recipe $recipe)
    {
        $deleteForm = $this->createDeleteForm($recipe);

        return $this->render('recipe/show.html.twig', array(
            'recipe' => $recipe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Display a form to edit an existing recipe entity.
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/recipe/{id}/edit", name="recipe_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Recipe $recipe)
    {
        $deleteForm = $this->createDeleteForm($recipe);
        $editForm = $this->createForm('App\Form\RecipeType', $recipe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recipe_edit', array('id' => $recipe->getId()));
        }

        return $this->render('recipe/edit.html.twig', array(
            'recipe' => $recipe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Delete an recipe entity.
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/recipe/{id}", name="recipe_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request, Recipe $recipe)
    {
        $form = $this->createDeleteForm($recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($recipe);
            $em->flush();
        }

        return $this->redirectToRoute('recipe_index');
    }

    /**
     * Create a form to delete an recipe entity.
     *
     * @param Recipe $recipe
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Recipe $recipe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('recipe_delete', array('id' => $recipe->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}