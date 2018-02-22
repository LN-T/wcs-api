<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DrinkController extends Controller
{
    /**
     * @var
     */
    private $api_drink;

    /**
     * DefaultController constructor.
     */
    public function __construct()
    {
        $this->api_drink = $_SERVER['API_DRINK'];
    }

    /**
     * @Route("/drink/recipe/{search}", name="drink_recipe")
     */
    public function getdrink($search)
    {
//        dump($search);die();
        // for ingredient request
        $ingredients = explode(" ", strtolower($search));
        // for recipe request

        // #1 : search by recipe
        $recipe = implode("-", $ingredients);
        $url = 'http://addb.absolutdrinks.com/drinks/' . $recipe .  '?apiKey=' . $this->api_drink;
        $client = new Client();
        $google = $client->request("GET", $url);
        $google = json_decode($google->getBody()->getContents(), true);

        if (($google['totalResult']) > 0) {
            $drinks = $google['result'];
        } else {

            // #2 : multiple search by ingredients
            foreach ($ingredients as $key => $value){

                $url = 'http://addb.absolutdrinks.com/quickSearch/ingredients/' . $value .  '/?apiKey=' . $this->api_drink;
                $client = new Client();
                $google = $client->request("GET", $url);
                $google = json_decode($google->getBody()->getContents(), true);
                // if one ingredient doesn't exist, stop search
                if(($google['totalResult'] == 0)){
                    $error = 'error';
                    return $this->render('drink/drink.html.twig', array(
                        'error' => $error
                    ));
                }

                $ingredients[$key] = $google['result'];
                $recipe_id[$key] = array_column($ingredients[$key], 'id');

                // get recipes for each term of search, combine unique results in array
                foreach ($recipe_id[$key] as $key2 => $value2){
                    $url = 'http://addb.absolutdrinks.com/drinks/with/' . $value2 .  '/?apiKey=' . $this->api_drink;
                    $client = new Client();
                    $google = $client->request("GET", $url);

                    $google = json_decode($google->getBody()->getContents(), true);
                    $recipe_id[$key][$key2] = $google['result'];
                }

                $recipes_all[$key] = array_unique(array_column(array_merge(...$recipe_id[$key]), 'id'));

            }

            // if number of ingredients == number of duplicates recipes, then display it
            $duplicate_count = array_count_values(array_merge(...$recipes_all));
            foreach ($duplicate_count as $key => $value){
                if ($value === count($ingredients)){
                    $recipes_duplicate[] = $key;
                }
            }
            foreach ($recipes_duplicate as $key3 => $value3){
                $url = 'http://addb.absolutdrinks.com/drinks/' . $value3 .  '/?apiKey=' . $this->api_drink;
                $client = new Client();
                $google = $client->request("GET", $url);

                $google = json_decode($google->getBody()->getContents(), true);
                $drinks_results[] = $google['result'];
            }
            $drinks = array_merge(...$drinks_results);
        }

        return $this->render('drink/drink.html.twig', array(
            'drinks' => $drinks
        ));

    }

    /**
     * @Route("/drink/ingredient/{id}", name="drink_ingredient")
     */
    public function getingredient($id)
    {
        $url = 'http://addb.absolutdrinks.com/drinks/' . $id .'/?apiKey=' . $this->api_drink;

        $client = new Client();
        $google = $client->request("GET", $url);
        $google = json_decode($google->getBody()->getContents(), true);
        $drinks = $google['result'];

        return $this->render('drink/ingredient.html.twig', array(
            'drinks' => $drinks
        ));
    }
}


