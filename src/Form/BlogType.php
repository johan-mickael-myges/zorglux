<?php

namespace App\Form;

use App\Entity\Blog;
use App\Enum\BlogConfidentiality;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Vich\UploaderBundle\Form\Type\VichImageType;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Add a title',
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Add short description',
                ],
            ])
            ->add('confidentiality', ChoiceType::class, [
                'label' => 'Share with',
                'choices' => [
                    'Everyone' => BlogConfidentiality::PUBLIC,
                    'Member only' => BlogConfidentiality::MEMBER,
                ],
            ])
            ->add('thumbnailFile', VichImageType::class, [
                'required' => true,
                'allow_delete' => true,
                'delete_label' => '...',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'content',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
            'sanitize_html' => true,
        ]);
    }
}
