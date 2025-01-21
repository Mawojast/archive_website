<?php

namespace App\Form;

use App\Data\ArchiveAreaDates;
use App\DTO\ArchiveSearchDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArchiveSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class,[
                'required' => true,
                'attr' => [
                    'placeholder' => 'Suche..',
                    'maxLength' => 64,
                    'minLength' => 3,
                    'autocomplete' => 'off'
                ]
            ])
            ->add('start_date', DateType::class,[
                'label' => 'von',
                'required' => true,
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'max' => $options['attr']['dateArea']['max']->format('Y-m-d'),
                    'min' => $options['attr']['dateArea']['min']->format('Y-m-d'),
                    'value' => $options['attr']['dateArea']['max']->format('Y-m-d')
                    
                ]
            ])
            ->add('end_date', DateType::class, [
                'label' => 'bis',
                'required' => true,
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'max' => $options['attr']['dateArea']['max']->format('Y-m-d'),
                    'min' => $options['attr']['dateArea']['min']->format('Y-m-d'),
                    'value' => $options['attr']['dateArea']['max']->format('Y-m-d')
                    
                ]
            ])
            ->add('order', ChoiceType::class, [
                'label' => 'zuerst',
                'choices' => [
                    'neuste' => 'latest',
                    'älteste' => 'oldest'
                ],
            ])
            ->add('option', ChoiceType::class, [
                'choices' => [
                    'Wörter' => 'word',
                    'Wortteile' => 'word-parts',
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => 'word-parts'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => 'form_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'archive_search_token',
            'method' => 'POST',
            'data_class' => ArchiveSearchDTO::class
        ]);
    }
}
