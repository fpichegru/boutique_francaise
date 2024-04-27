<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class,[
                'label' => "Votre mot de passe actuel",
                 'attr' => [
                      'placeholder' => "Indiquez votre mot de passe actuel"
                ],
                 'mapped' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                 'type' => PasswordType::class,
                 'constraints' => [
                    new Length([
                        'min' => 8,
                        'max' => 30
                    ])],
                 'first_options'  => [
                    'label' => 'Votre nouveau mot de passe', 
                    //Très important fait le mapping,  permet de faire lien avec notre propriété dans l'entité
                    'hash_property_path' => 'password', 
                    'attr' => [
                        'placeholder' => "Choisissez votre nouveau mot de passe"
                ]],
                 'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',  
                    'attr' => [
                      'placeholder' => "Confirmez votre nouveau mot de passe"
                ]],

                // signifie n'essai pas de faire le lien entre le champs et l'entité que je te donne , plainPassword n'existe pas dans l'entité "User", je lui force la main. 
                 'mapped' => false,
            ])
            ->add('submit',SubmitType::class, [
                'label' => "Mettre à jour mon mot de passe",
                   'attr' => [
                    'class' => "btn btn-success"
                ]

            ])
            // je veux que tu ajoutes un écouteur dans le formulaire
            // a quel moment je veux ecouter , qu'est ce que je veux faire 
            // on SUBMIT je veux comparer les Mots de Passe
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
               
               
            


                //on va chercher le formulaire , pour récupérer la saisie du Mot de passe
                $form = $event->getForm();

                 // 1. Récupérer le mot de passe saisi par l'utilisateur
                $actualPwd = $form->get('actualPassword')->getData();

                 // 2. Récupérer le mot de passe actuel en BDD
            
                 $user = $form->getConfig()->getOptions()['data'];

                 $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
                 $isValid = $passwordHasher->isPasswordValid(
                    $user,
                    $actualPwd
                 );
                //  $actualPwdBDD=$user->getPassword();

                //  dd($isValid);

                    
                // 3. Si ils sont différents , envoyer une erreur
                if( !$isValid){
                    $form->get('actualPassword')->addError(new FormError(message:"Votre mot de passe actuel n'est pas conforme . Veuillez vérifier votre saisie."));
                } 

            })       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,

            // par défaut tu le mets à null, si il n'est pas envoyé dans un autre formulaire
            'passwordHasher' => null
        ]);
    }
}
