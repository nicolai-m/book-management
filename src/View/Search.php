<?php


namespace App\View;


use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class Search extends AbstractController
{
    /** @var BooksRepository */
    private $booksRepository;

    public function __construct(
        BooksRepository $booksRepository
    )
    {
        $this->booksRepository  = $booksRepository;
    }

    public function autocomplete(Request $request)
    {
        $request->setMethod('POST');
        $searchValue = $request->get('search_value');

        $result = $this->booksRepository->bookSearch($searchValue);

        $params = [
            'books' => $result,
            'debug' => false
        ];

        return $this->render('autocomplete_book.html.twig',$params);
    }

    public function book(Request $request)
    {
        $request->setMethod('POST');
        $searchValue = $request->get('search');

        $result = $this->booksRepository->bookSearch($searchValue);

        $params = [
            'books' => $result,
            'debug' => false
        ];

        return $this->render('search_book.html.twig',$params);
    }
}