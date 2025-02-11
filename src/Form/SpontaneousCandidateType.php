<?php

namespace App\Form;

use App\Entity\SpontaneousCandidate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SpontaneousCandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class,[
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre prénom',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('lastName',TextType::class,[
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre nom',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('email',EmailType::class,[
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre email',
                    ]),
                ],
            ])
            ->add('phoneNumber',TextType::class,[
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectioner votre numéro de téléphone',
                    ]),
                ],
            ])
            ->add('ville',ChoiceType::class,[
                'label' => 'Adresse',
                'choices' => [
                'Selectioner votre ville' => null,
                'Paris' => 'Paris',
                'Lyon' => 'Lyon',
                'Marseille' => 'Marseille',
                'Bordeaux' => 'Bordeaux',
                'Lille' => 'Lille',
                'Toulouse' => 'Toulouse',
                'Nantes' => 'Nantes',
                'Strasbourg' => 'Strasbourg',
                'Montpellier' => 'Montpellier',
                'Rennes' => 'Rennes',
                'Autre' => 'Autre',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectioner votre ville',
                    ]),
                ],
            ])
            ->add('educationLevel',ChoiceType::class,[
                'label' => 'Niveau d\'études',
                'choices' => [
                    'Selectioner votre niveau d\'études' => null,
                'Niveau Bac' => 'Niveau Bac',
                'Licence' => 'Licence',
                'Master' => 'Master',
                'Bac+5' => 'Bac+5',
                'Autre' => 'Autre', 
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectioner votre niveau d\'études',
                    ]),
                ],
            ])
            ->add('experience',ChoiceType::class,[
                'label' => 'Expérience',
                'choices' => [
                'Selectioner votre experience' => null,
                'Moins de 3 ans' => 'Moins de 3 ans',
                'de 3 à 5 ans' => 'de 3 à 5 ans',
                'de 6 à 10 ans' => 'de 6 à 10 ans',
                'Plus de 10 ans' => 'Plus de 10 ans',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectioner votre experience',
                    ]),
                ],
            ])
            ->add('fonction',ChoiceType::class,[
                'label' => 'Fonction',
                'choices' => [
                    'Selectioner votre domaine' => null,

                    'Développeur Web' => 'Développeur Web',
                    'Développeur Mobile' => 'Développeur Mobile',
                    'Designer' => 'Designer',
                    'Chef de projet' => 'Chef de projet',
                    'Technicien' => 'Technicien',
                    'Commercial' => 'Commercial',
                    'Testeur' => 'Testeur',
                    'Autre' => 'Autre'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectioner votre experience',
                    ]),
                ],
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
            'data_class' => SpontaneousCandidate::class,
        ]);
    }
}
