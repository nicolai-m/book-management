<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookForm extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'book.title'
            ])
            ->add('isbn', TextType::class, [
                'required' => true,
                'label' => 'book.isbn'
            ])
            ->add('author', TextType::class, [
                'required' => true,
                'label' => 'book.author'
            ])
            ->add('borrowed', CheckboxType::class, [
                'required' => false,
                'label' => 'book.borrowed.title'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}