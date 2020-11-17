<?php


namespace App\View;


use App\Controller\BookController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class Book extends AbstractController
{
    /** @var BookController */
    private $bookController;

    public function __construct(BookController $bookController)
    {
        $this->bookController = $bookController;
    }

    public function displayBook(string $isbn)
    {
        $book = $this->bookController->read($isbn);

        if(empty($book)) {
            return $this->redirectToRoute('app_not_found');
        }

        $params['book'] = [
            'title'     => $book->getTitle(),
            'isbn'      => $book->getIsbn(),
            'author'    => $book->getAuthor(),
            'borrowed'  => $book->getBorrowed()
        ];

        return $this->render('book.html.twig',$params);
    }
}