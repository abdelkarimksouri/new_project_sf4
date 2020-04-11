<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Drug;
use App\Entity\Pharmacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FSevestre\BooleanFormType\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


class DrugType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('drugName', TextType::class)
            ->add('bareCode', TextType::class)
            ->add('description', TextType::class)
            ->add('price', MoneyType::class, [
                'invalid_message' => 'integer or float are required'
            ])
            ->add('isDeleted', ChoiceType::class, [
                'choices' => [1, '1', true, 'true', 'on', 'yes'],
            ])
            ->add('expiredAt', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => 'false',
                'format' => 'YYYY-MM-dd',
                'attr' => ['data-date-format' => 'YYYY-MM-DD']
            ])
//            ->add('pharmacy', CollectionType::class, [
//                'entry_type' => PharmacyType::class,
//                'allow_add' => true,
//                'allow_delete' => true,
//            ]);
          ->add('pharmacy', EntityType::class, [
                'class'         => Pharmacy::class,
                'choice_label'  => 'generatedName',
                'multiple'      => true,
            ])
        ;

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Drug::class,
            'csrf_protection' => false
        ]);
    }

}
