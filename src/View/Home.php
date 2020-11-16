<?php


namespace App\View;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Home extends AbstractController
{
    public function displayHome(): Response
    {
        $params = [];

        return $this->render('home.html.twig',$params);
    }
}