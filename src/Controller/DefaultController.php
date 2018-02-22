<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        if (isset($_POST['type'])) {
            if ($_POST['type'] == 'food') {
                return $this->redirectToRoute('food_index');
            }
            if ($_POST['type'] == 'drink') {
                return $this->redirectToRoute('drink_index');
            }
        } else {
            return $this->render('default/index.html.twig');
        }
    }

    /**
     * @Route("/food", name="food_index")
     */
    public function food()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/drink", name="drink_index")
     */
    public function drink()
    {
        return $this->render('default/index.html.twig');
    }



}


