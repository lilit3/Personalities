<?php

namespace App\Form;

use App\Entity\Personality;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('code')
            ->add('description')
            ->add('content')
            ->add('img_type')
            ->add('publish')
            ->add('slide')
            ->add('avatar')
            ->add('role')
            ->add('author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personality::class,
        ]);
    }
}
