<?php
namespace App\Form;

use App\Entity\Client;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('age', IntegerType::class)
            ->add('gender', ChoiceType::class, [
                'choices' => [0 => '', 1 => 'Homme', 2 => 'Femme'],
                'multiple' => false,
            ])
//            ->add('roles', ChoiceType::class, [
//                'multiple' => true,
//                'expanded' => true,
//                'choices' => user::USER_ROLES,
//                'invalid_message' => 'The role is not valid'
//            ])
//            ->add('saveAndAdd', SubmitType::class, ['label' => 'Save and Add'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true
        ]);
    }
}
