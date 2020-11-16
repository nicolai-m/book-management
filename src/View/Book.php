<?php


namespace App\View;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Book extends AbstractController
{
    public function displayBook(string $isbn): Response
    {


        $params['book'] = [
            'title'     => 'Test Buch',
            'isbn'      => $isbn,
            'author'    => 'asd',
            'borrowed'  => '1'
        ];

        return $this->render('book.html.twig',$params);
    }
}