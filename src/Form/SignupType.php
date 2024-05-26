<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SignupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a username',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 180,
                        'minMessage' => 'Username must be at least 3 characters long',
                        'maxMessage' => 'Username cannot be longer than 180 characters',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Password',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Password must be at least 6 characters long',
                        'max' => 4096,
                    ]),
                    new Assert\Regex(
                        [
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                            'message' => 'Password must contain at least one digit, one uppercase letter, and one lowercase letter',
                        ]
                    )
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm Password',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please confirm your password',
                    ]),
                ],
            ]);

        // builder event listener to check if the passwords match
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $password = $form->get('plainPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();
            if ($password !== $confirmPassword) {
                // add violation to the form
                $form->get('confirmPassword')->addError(new FormError('Passwords do not match'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
