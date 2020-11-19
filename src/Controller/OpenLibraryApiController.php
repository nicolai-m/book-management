<?php


namespace App\Controller;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

class OpenLibraryApiController
{
    /** @var HttpClientInterface */
    private $client;

    /**
     * OpenLibraryController constructor.
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    protected function openLibraryUrl($olKey): string
    {
        return 'http://openlibrary.org/' . $olKey . '.json';
    }

    /**
     * @return string
     */
    protected function searchUrl(): string
    {
        return 'http://openlibrary.org/search.json';
    }

    protected function authorUrl($olId): string
    {
        return 'http://openlibrary.org/authors/' . $olId . '.json';
    }

    /**
     * @param string $isbn
     * @return string
     */
    protected function isbnUrl(string $isbn): string
    {
        return 'https://openlibrary.org/isbn/' . $isbn . '.json';
    }

    protected function normalize($string)
    {
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace ( '/[^a-z0-9 ]/i', '', $string );
        return  strtolower($string);
    }

    protected function normalizeIsbn($string)
    {
        return preg_replace("#[^\d\w]#i", "",trim($this->normalize($string)));
    }

    /**
     * @param string $query
     * @return array|false
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function searchBook(string $query)
    {
        $body = [
            'q'   => $query,
        ];

        $response = $this->client->request(
            'GET',
            $this->searchUrl(),
            [
                'query' => $body,
            ]
        );

        $statusCode = $response->getStatusCode();
        if($statusCode !== 200) {
            return false;
        }

        $content = $response->toArray();

        return $content;
    }

    /**
     * @param string $title
     * @return array|false
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getBookByTitle(string $title)
    {
        $title = $this->normalize($title);
        $searchResult = $this->searchBook($title);

        $i=0;
        $result = [];
        foreach ($searchResult['docs'] as $value)
        {
            $i++;

            if(!isset($value['edition_key'])) continue;
            $olKey = '/books/' . end($value['edition_key']);

            $result = $result + $this->getBookFromApi($this->openLibraryUrl($olKey));

            if($i === 30) break;
        }

        return $result;
    }

    /**
     * @param string $isbn
     * @return array|false
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getBookByIsbn(string $isbn)
    {
        $isbn = $this->normalizeIsbn($isbn);
        return $this->getBookFromApi($this->isbnUrl($isbn));
    }

    /**
     * @param string $url
     * @return array|false
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getBookFromApi(string $url)
    {
        $response = $this->client->request(
            'GET',
            $url
        );

        $statusCode = $response->getStatusCode();
        if($statusCode !== 200) {
            return false;
        }

        $content = $response->toArray();

        if (!empty($content['authors'])) {
            $author = $this->getAuthorByOlId($content['authors'][0]['key']);
        } else {
            $author = '';
        }

        if(!isset($content['isbn_13'])) {
            return [];
        }

        $result[$content['isbn_13'][0]] = [
            'title' => $content['title'],
            'isbn' => $content['isbn_13'][0],
            'author' => $author
        ];

        return $result;
    }



    /**
     * @param string $olKey
     * @return false|mixed
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAuthorByOlId(string $olKey)
    {

        $response = $this->client->request(
            'GET',
            $this->openLibraryUrl($olKey)
        );

        $statusCode = $response->getStatusCode();
        if($statusCode !== 200) {
            return false;
        }

        $content = $response->toArray();

        return $content['name'];
    }
}