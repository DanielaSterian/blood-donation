<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['forUser'] == true) {
            $builder
                ->add('email')
                ->add('firstName')
                ->add('lastName')
            ;
        }
        if($options['forAdmin'] == true) {
            $builder
                ->add('roles')
            ;
        }

//        $builder
//            ->add('save', SubmitType::class, [
//                'attr' => ['class' => 'btn btn-lg btn-danger'],
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'forUser' => false,
            'forAdmin' => false,
        ]);
    }
}
