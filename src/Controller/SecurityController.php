<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class SecurityController extends AbstractFOSRestController
{

    /**
     * @Route("/login_admin", name="login_admin")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        dump($error);
        dump($lastUsername);
//        $form = $this->get('form.factory')->create(UserType::class, $user);
        $form = $this->get('form.factory')
            ->createNamedBuilder(null)
            ->add('_username', null, ['label' => 'Email'])
            ->add('_password', PasswordType::class, ['label' => 'Mot de passe'])
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $plainPassword = $form->getData()->getPassword();
            $em = $this->getDoctrine()->getManager();
            $existUser = $em->getRepository(User::class)->findOneBy([
                'email' => $form->getData()->getEmail()
            ]);
            dump("herer");die;
            if ($existUser instanceof User) {
                if ($form->isSubmitted() && $form->isValid()) {
                 if ($encoder->isPasswordValid($existUser, $plainPassword)) {
                     return $this->render('bundles/EasyAdminBundle/page/content.html.twig', [
                         'user' => $existUser
                     ]);
                 }
                }
            }
        }

        return $this->render('bundles/EasyAdminBundle/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/logout", name="logout")
     * @throws \RuntimeException
     */
    public function logout()
    {
        throw new \RuntimeException('This should never be called directly.');
    }
}
