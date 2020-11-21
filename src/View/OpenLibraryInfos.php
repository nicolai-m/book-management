<?php


namespace App\View;


use App\Controller\OpenLibraryApiController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class OpenLibraryInfos extends AbstractController
{
    /** @var OpenLibraryApiController */
    private $openLibraryApiController;

    /**
     * OpenLibraryInfos constructor.
     * @param OpenLibraryApiController $openLibraryApiController
     */
    public function __construct(
        OpenLibraryApiController $openLibraryApiController
    )
    {
        $this->openLibraryApiController = $openLibraryApiController;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getBookByIsbn(Request $request)
    {
        $request->setMethod('POST');
        $isbn = $request->get('isbn');

        $book = $this->openLibraryApiController->getBookByIsbn($isbn);

        $params = [
            'infos' => $book
        ];

        return $this->render('openlibrary_search.html.twig',$params);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getBookByTitle(Request $request)
    {
        $request->setMethod('POST');
        $title = $request->get('title');


        $book = $this->openLibraryApiController->getBookByTitle($title);

        $params = [
            'infos' => $book
        ];

        return $this->render('openlibrary_search.html.twig',$params);
    }
}