<?php


namespace App\Controller;


use App\Entity\Books as BooksEntity;
use App\Repository\BooksRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookController
{
    /** @var BooksRepository */
    private $booksRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var string */
    private $title;

    /** @var int */
    private $isbn;

    /** @var string */
    private $author;

    /** @var bool */
    private $borrowed;

    /**
     * BookController constructor.
     * @param BooksRepository $booksRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        BooksRepository $booksRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->booksRepository  = $booksRepository;
        $this->entityManager    = $entityManager;
    }

    /**
     * @param string $title
     * @param string $isbn
     * @param string $author
     * @param bool $borrowed
     */
    public function create(string $title,string $isbn, string $author, bool $borrowed)
    {
        $findBook = $this->booksRepository->findOneBy(['isbn' => $isbn]);

        if(!$findBook) {
            $bookEntry = new BooksEntity();

            $bookEntry->setTitle($title);
            $bookEntry->setIsbn($isbn);
            $bookEntry->setAuthor($author);
            $bookEntry->setBorrowed($borrowed);

            $this->entityManager->persist($bookEntry);
            $this->entityManager->flush();
            return true;
        }
        return false;
    }

    /**
     * @param int $isbn
     * @return BooksEntity|null
     */
    public function read(int $isbn)
    {
        return $this->booksRepository->findOneBy(['isbn' => $isbn]);
    }

    /**
     * @param mixed $title
     * @return BookController
     */
    public function setTitle($title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param mixed $isbn
     * @return BookController
     */
    public function setIsbn($isbn): self
    {
        $this->isbn = $isbn;
        return $this;
    }

    /**
     * @param mixed $author
     * @return BookController
     */
    public function setAuthor($author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @param mixed $borrowed
     * @return BookController
     */
    public function setBorrowed($borrowed): self
    {
        $this->borrowed = $borrowed;
        return $this;
    }

    /**
     * @param int $isbn
     * @return bool
     */
    public function update(int $isbn): bool
    {
        $book = $this->booksRepository->findOneBy(['isbn' => $isbn]);

        if(!$book) {
            return false;
        }

        $hasChanged = false;

        if(!empty($this->title)) {
            $book->setTitle($this->title);
            $hasChanged = true;
        }

        if(!empty($this->isbn)) {
            $book->setIsbn($this->isbn);
            $hasChanged = true;
        }

        if(!empty($this->author)) {
            $book->setAuthor($this->author);
            $hasChanged = true;
        }


        if(!empty($this->borrowed)) {
            $book->setBorrowed($this->borrowed);
            $hasChanged = true;
        }

        if ($hasChanged) {
            $this->entityManager->flush();
            return true;
        }

        return false;
    }

    /**
     * @param int $isbn
     */
    public function delete(int $isbn)
    {
        $book = $this->booksRepository->findOneBy(['isbn' => $isbn]);

        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }
}