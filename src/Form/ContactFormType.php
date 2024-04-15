<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),

                ],
            ])
            ->add('numero',TelType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre numéro de téléphone',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Votre numéro de téléphone doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),

                ],
            ])
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre email',
                    ]),
                ],
            ])
            ->add('message',TextareaType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre message',
                    ]),
                    

                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
