<?php


namespace App\View;


use App\Controller\OpenLibraryController;
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
    /** @var OpenLibraryController */
    private $openLibraryController;

    public function __construct(
        OpenLibraryController $openLibraryController
    )
    {
        $this->openLibraryController = $openLibraryController;
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

//        $isbn = '9780747575443';

        $book = $this->openLibraryController->getBookByIsbn($isbn);

        $params = [
            'infos' =>  $book

        ];

        return $this->render('openlibrary-search.html.twig',$params);
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


        $book = $this->openLibraryController->getBookByIsbn($title);

        $params = [
            'infos' =>  $book
        ];

        return $this->render('openlibrary-search.html.twig',$params);
    }
}