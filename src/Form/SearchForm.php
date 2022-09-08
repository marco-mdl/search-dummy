<?php

namespace App\Form;

use App\Entity\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('excludedAssortments')
            ->add(
                'size',
                ChoiceType::class,
                [
                    'choices' => range(1, 500),
                    'choice_label' => function ($choice) {
                        return $choice;
                    },
                ]
            )
            ->add('page')
            ->add('productGroup')
            ->add('term');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Search::class,
                'allow_extra_fields' => true,
            ]
        );
    }
}
