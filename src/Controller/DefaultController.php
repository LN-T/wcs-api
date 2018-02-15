<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
//        $routes = $this->get('router')->getRouteCollection()->all();

        foreach ($this->get('router')->getRouteCollection()->all() as $name => $route) {
            $routes[] = [$name, $route];
        }

        return $this->render('default/index.html.twig', array(
            'routes' => $routes
        ));

    }

    /**
     * @Route("/getrecipe/", name="getrecipe")
     */
    public function getrecipe()
    {
        $search = $_POST['search'];
        $url = 'http://food2fork.com/api/search?key=' . '1934704d1230d8fef7f1d866cb96bc7c' . '&q=' . $search;
        $client = new Client();
        $google = $client->request("GET", $url);
        $google = json_decode($google->getBody()->getContents());

        $recipes = $google->recipes;
        $error = 'error';

        if($google->count != 0){
            return $this->render('default/getrecipe.html.twig', array(
                'recipes' => $recipes
            ));
        } else{
            return $this->render('default/getrecipe.html.twig', array(
                'error' => $error
            ));
        }

    }

    /**
     * @Route("/getingredient/{id}", name="getingredient")
     */
    public function getingredient($id)
    {
        !empty($_POST['pax']) ? $multiple = $_POST['pax'] : $multiple = 1;
        $url = 'http://food2fork.com/api/get?key=' . '1934704d1230d8fef7f1d866cb96bc7c' . '&rId=' . $id;
        $client = new Client();
        $google = $client->request("GET", $url);
        $google = json_decode($google->getBody()->getContents());
        $ingredients = $google->recipe->ingredients;

        foreach($ingredients as $key => $value){
            $quantity[$key] = explode(' ', $value);
        }
        foreach ($quantity as $key2 => $value2) {

            if(isset($value2[0])) {
                $first_value = $value2[0];
                $first_value_first = $first_value[0];
                $first_value_last = $first_value[strlen($first_value) - 1];
            }
            if(isset($value2[1])){
                $second_value = $value2[1];
                $second_value_first = $second_value[0];
                $second_value_last = $second_value[strlen($second_value)-1];
            }

            // 1 chiffre entier
            if (is_numeric($first_value)){
                if (is_numeric($second_value_first) && is_numeric($second_value_last)) {
                    $quantity[$key2] = $first_value + $second_value_first / $second_value_last;
                }else {
                    $quantity[$key2] = $first_value;
                }
            }
            // 1 fraction
            elseif (!is_numeric($first_value) && is_numeric($first_value_first) && is_numeric($first_value_last) ){
                $quantity[$key2] = $first_value_first/$first_value_last;
            }
            // autres cas
            else{
                $quantity[$key2] = '';
            }

        }

        $all = array_combine($ingredients, $quantity);

        return $this->render('default/getingredient.html.twig', array(
            'ingredients' => $all,
            'multiple' => $multiple,
        ));
    }
}


