<?php


namespace App\View;


use App\Controller\BookController;
use App\Form\BookForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateAndEdit extends AbstractController
{
    /** @var BookController */
    private $bookController;

    /** @var TranslatorInterface */
    protected $translator;

    /**
     * CreateAndEdit constructor.
     * @param BookController $bookController
     * @param TranslatorInterface $translator
     */
    public function __construct(
        BookController $bookController,
        TranslatorInterface $translator
    )
    {
        $this->bookController = $bookController;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createBook(Request $request): Response
    {
        $info = '';

        $form = $this->createForm(BookForm::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $bookData = $form->getData();

            $result = $this->bookController->create(
                $bookData['title'],
                $bookData['isbn'],
                $bookData['author'],
                $bookData['borrowed']
            );
            $info = $this->translator->trans('book.added');
            
            if (!$result) {
                $info = $this->translator->trans('book.exists');
            }
        }

        $params = [
            'bookForm' => $form->createView(),
            'info'     => $info,
            'edit'     => false
        ];

        return $this->render('create_edit.html.twig',$params);
    }

    /**
     * @param $isbn
     * @param Request $request
     * @return Response
     */
    public function editBook($isbn, Request $request): Response
    {
        $info = '';

        $book = $this->bookController->read($isbn);

        $form = $this->createForm(BookForm::class);
        $form->setData([
            'title'    => $book->getTitle(),
            'isbn'     => $book->getIsbn(),
            'author'   => $book->getAuthor(),
            'borrowed' => $book->getBorrowed()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $bookData = $form->getData();

            $this->bookController->setTitle($bookData['title']);
            $this->bookController->setIsbn($bookData['isbn']);
            $this->bookController->setAuthor($bookData['author']);
            $this->bookController->setBorrowed($bookData['borrowed']);

            $result = $this->bookController->update($isbn);
            $info = $this->translator->trans('book.added');

            if (!$result) {
                $info = $this->translator->trans('book.exists');
            }
        }

        $params = [
            'bookForm' => $form->createView(),
            'info'     => $info,
            'edit'     => true
        ];

        return $this->render('create_edit.html.twig',$params);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function deleteBook(Request $request): Response
    {
        $request->setMethod('POST');
        $isbn = $request->get('isbn');

        $book = $this->bookController->read($isbn);

        $this->bookController->delete($isbn);

        $params = [
            'book' => $book
        ];

        return $this->render('@widget/delete_message.html.twig',$params);
    }
}