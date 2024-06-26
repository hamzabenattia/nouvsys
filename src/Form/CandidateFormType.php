<?php

namespace App\Form;

use App\Entity\Candidate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CandidateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstName',TextType::class,[
                'label' => 'Prénom',
                'mapped' => false,
                'disabled' => true,
            ])
            ->add('lastName',TextType::class,[
                'label' => 'Nom',
                'mapped' => false,
                'disabled' => true,

            ])
            ->add('email',EmailType::class,[
                'label' => 'Email',
                'mapped' => false,
                'disabled' => true,

            ])
            ->add('phoneNumber',TextType::class,[
                'label' => 'Téléphone',
                'mapped' => false,
                'disabled' => true,

            ])
            
            ->add('cvFile',VichFileType::class,[
                'label' => 'Télécharger votre CV',
                'required' => true,
                'allow_delete' => true,
                'download_uri' => true,
                'download_label' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez télécharger votre CV',
                    ]),
                ],
            ])
            ->add('message',TextareaType::class,[
                'label' => 'Message',
                'required' => false,
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Votre message doit contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J\'accepte les termes et conditions ',
'constraints' => [
    new IsTrue([
        'message' => 'Vous devez accepter nos conditions.',
    ]),
],
])
            ->add('submit',SubmitType::class,[
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'bg-[#18629C] text-white px-4 py-2 rounded-lg cursor-pointer',
                ],
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,

        ]);
    }
}
