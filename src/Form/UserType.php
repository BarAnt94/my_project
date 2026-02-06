<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\Wallet;
use App\Form\WalletType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname',TextType::class, [
                'label' => 'Prénom',
                'constraints'=>new Length(null, min:2, max:25), 
            ])
            ->add('lastname',TextType::class, [
                'label' => 'Nom',
                'constraints'=>new Length(null, min:2, max:25),
            ])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'label' => 'Mot de passe',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints'=>new Length(null, min:8, max:255),
            ])
            ->add('birthday',DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
            ])
            ->add('telephone',TextType::class, [
                'label' => 'Numéro de téléphone',
                'constraints'=>new Length(null, min:10, max:15),
            ])
            ->add('email',EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'constraints'=>new Length(null, min:5, max:255),
            ])
            ->add('wallets', CollectionType::class, [
                'entry_type' => WalletType::class, 
                'allow_add' => true,              
                'allow_delete' => true,      
                'by_reference' => false,         
                'label' => 'Argent dans le portefeuille',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
