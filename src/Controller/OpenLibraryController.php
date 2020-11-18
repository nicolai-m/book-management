<?php


namespace App\Controller;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenLibraryController
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
     * @param int $isbn
     * @return string
     */
    protected function isbnUrl(string $isbn): string
    {
        return 'https://openlibrary.org/isbn/' . $isbn . '.json';
    }

    /**
     * @param string $query
     * @return array|false
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
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
     * @param string $isbn
     * @return array|false
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getBookByIsbn(string $isbn)
    {

        $response = $this->client->request(
            'GET',
            $this->isbnUrl($isbn),

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

        $result[$content['isbn_13'][0]] = [
            'title' => $content['title'],
            'isbn' => $content['isbn_13'][0],
            'author' => $author
        ];

        return $result;
    }

    public function getAuthorByOlId(string $olKey)
    {

        $response = $this->client->request(
            'GET',
            'http://openlibrary.org/' . $olKey . '.json',

        );

        $statusCode = $response->getStatusCode();
        if($statusCode !== 200) {
            return false;
        }

        $content = $response->toArray();

        return $content['name'];
    }
}