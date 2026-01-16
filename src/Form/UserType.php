<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Dom\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

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
            ->add('password',TextType::class, [
                'label' => 'Mot de passe',
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
