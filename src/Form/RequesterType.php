<?php

namespace App\Form;

use App\Entity\Requester;
use App\Repository\BloodGroupRepository;
use App\Repository\RequesterRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\BloodGroup;
use App\Entity\User;
use App\Form\BloodGroupType;
use App\Form\UserType;

class RequesterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('firstName')
            ->add('lastName')
//            ->add('bloodGroup', BloodGroupType::class, [
//                'label' => false
//            ])
//            ->add('bloodGroup', EntityType::class,[
//                'class' => BloodGroup::class,
//                'query_builder'=> function (RequesterRepository $repo)
//                {
//                    return $repo->findBloodGroupName();
//                },
//                'choice_label' => 'name'
//            ])

            ->add('bloodGroup', EntityType::class, [
                'class' => 'App\Entity\BloodGroup',
                'choice_label'=>'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Requester::class,
        ]);
    }
}
