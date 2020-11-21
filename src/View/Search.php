<?php


namespace App\View;


use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Search extends AbstractController
{
    /** @var BooksRepository */
    private $booksRepository;

    /**
     * Search constructor.
     * @param BooksRepository $booksRepository
     */
    public function __construct(
        BooksRepository $booksRepository
    )
    {
        $this->booksRepository  = $booksRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function autocomplete(Request $request): Response
    {
        $request->setMethod('POST');
        $searchValue = $request->get('search_value');

        $result = $this->booksRepository->bookSearch($searchValue);

        $params = [
            'books' => $result,
        ];

        return $this->render('autocomplete_book.html.twig',$params);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function book(Request $request): Response
    {
        $request->setMethod('POST');
        $searchValue = $request->get('search');

        $result = $this->booksRepository->bookSearch($searchValue);

        $params = [
            'books' => $result,
        ];

        return $this->render('search_book.html.twig',$params);
    }
}