<?php


namespace App\View;


use App\Controller\BookController;
use App\Form\BookForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class CreateAndEdit extends AbstractController
{
    /** @var BookController */
    private $bookController;

    /** @var TranslatorInterface */
    protected $translator;

    public function __construct(
        BookController $bookController,
        TranslatorInterface $translator
    )
    {
        $this->bookController = $bookController;
        $this->translator = $translator;
    }

    public function createBook(Request $request)
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
            'bookForm'  => $form->createView(),
            'info'      => $info
        ];

        return $this->render('create.html.twig',$params);
    }
}