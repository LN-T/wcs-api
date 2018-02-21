<?php

namespace App\Controller;

use App\Kernel;
use GuzzleHttp\Client;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @var
     */
    private $api_absolut;

    /**
     * DefaultController constructor.
     */
    public function __construct()
    {
        $this->api_absolut = $_SERVER['API_ABSOLUT'];
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
//        $routes = $this->get('router')->getRouteCollection()->all();

//        foreach ($this->get('router')->getRouteCollection()->all() as $name => $route) {
//            $routes[] = [$name, $route];
//        }

        return $this->render('default/index.html.twig', array(
//            'routes' => $routes
        ));

    }

    /**
     * @Route("/getdrink/", name="getdrink")
     */
    public function getdrink()
    {
        $api_absolut = $_SERVER['API_ABSOLUT'];

        $search = $_POST['search'];

        $url = 'http://addb.absolutdrinks.com/quickSearch/drinks/' . $search .  '?apiKey=' . $this->api_absolut;

        $client = new Client();
        $google = $client->request("GET", $url);

        $google = json_decode($google->getBody()->getContents(), true);
        $drinks = $google['result'];
        $error = 'error';

        if($drinks != null){
            return $this->render('default/getdrink.html.twig', array(
                'drinks' => $drinks
            ));
        } else{
            return $this->render('default/getdrink.html.twig', array(
                'error' => $error
            ));
        }

    }

    /**
     * @Route("/getingredient/{id}", name="getingredient")
     */
    public function getingredient($id)
    {
        $url = 'http://addb.absolutdrinks.com/drinks/' . $id .'/?apiKey=' . $this->api_absolut;

        $client = new Client();
        $google = $client->request("GET", $url);
        $google = json_decode($google->getBody()->getContents(), true);
        $drinks = $google['result'];

        return $this->render('default/getingredient.html.twig', array(
            'drinks' => $drinks
        ));
    }
}


