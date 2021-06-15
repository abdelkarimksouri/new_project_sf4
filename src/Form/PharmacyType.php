<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use App\Entity\Drug;
use App\Entity\Pharmacy;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\MediaType;

class PharmacyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('generatedName', TextType::class)
            ->add('isActive', ChoiceType::class, [
                'choices' => [1, '1', true, 'true', 'on', 'yes'],
//                'empty_value' => [0, '0', false, 'false', 'off', 'no']
            ])
            ->add('isNight', ChoiceType::class, [
                'choices' => [1, '1', true, 'true', 'on', 'yes'],
//                'empty_value' => [0, '0', false, 'false', 'off', 'no']
            ])
            ->add('isHoliday', ChoiceType::class, [
                'choices' => [1, '1', true, 'true', 'on', 'yes'],
//                'empty_value' => [0, '0', false, 'false', 'off', 'no']
            ])

            ->add('address', AddressType::class)
            ->add('user', UserType::class)
            ->add('logo', MediaType::class)
        ;
    }

//
//    public function onPostSetData(FormEvent $event)
//    {
//        dump("hererer");die;
//        if ($event->getData() && $event->getData()->getId()) {
//            $form = $event->getForm();
//            // unset($form['user']);
//        }
//    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pharmacy::class,
            'csrf_protection' => false
        ]);
    }

}
