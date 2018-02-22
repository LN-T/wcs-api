<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FoodController extends Controller
{
    /**
     * @var
     */
    private $api_food;

    /**
     * DefaultController constructor.
     */
    public function __construct()
    {
        $this->api_food = $_SERVER['API_FOOD'];
    }

    /**
     * @Route("/food/recipe/{search}", name="food_recipe")
     */
    public function getrecipe($search)
    {
        $url = 'http://food2fork.com/api/search?key=' . $this->api_food . '&q=' . $search;
        $client = new Client();
        $google = $client->request("GET", $url);
        $google = json_decode($google->getBody()->getContents());

        $recipes = $google->recipes;
        $error = 'error';

        if($google->count != 0){
            return $this->render('food/recipe.html.twig', array(
                'recipes' => $recipes
            ));
        } else{
            return $this->render('food/recipe.html.twig', array(
                'error' => $error
            ));
        }

    }

    /**
     * @Route("/food/ingredient/{id}", name="food_ingredient")
     */
    public function getingredient($id)
    {
        !empty($_POST['pax']) ? $multiple = $_POST['pax'] : $multiple = 1;
        $url = 'http://food2fork.com/api/get?key=' . $this->api_food . '&rId=' . $id;
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

        return $this->render('food/ingredient.html.twig', array(
            'ingredients' => $all,
            'multiple' => $multiple,
        ));
    }
}


