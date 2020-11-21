<?php


namespace App\View;


use App\Repository\BooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Pagination;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;

class Home extends AbstractController
{
    /** @var BooksRepository */
    private $booksRepository;

    /**
     * Home constructor.
     * @param BooksRepository $booksRepository
     */
    public function __construct(
        BooksRepository $booksRepository
    )
    {
        $this->booksRepository  = $booksRepository;
    }

    /**
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function displayHome(): Response
    {
        return $this->bookList();
    }

    /**
     * @param int $page
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function bookList(int $page = 1)
    {
        $entriesPerPage = 25;
        $count = $this->booksRepository->countAll();

        $pagination = new Pagination();
        $pagination
            ->setCurrentPage($page)
            ->setTotalEntries((int) $count)
            ->setEntriesPerPage($entriesPerPage);
        $paginationResult = $pagination->getLimits();
        $browse = $pagination->browse();

        $books = $this->booksRepository->findWithLimit($paginationResult['limit'], $paginationResult['offset'], 'DESC');

        $params = [
            'books' => $books,
            'pagination' => $browse,
            'totalEntries' => $paginationResult['maxPage']
        ];

        return $this->render('home.html.twig',$params);
    }

    /**
     * @return Response
     */
    public function notFound()
    {
        return $this->render('not_found.html.twig');
    }
}