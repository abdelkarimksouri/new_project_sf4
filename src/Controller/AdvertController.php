<?php

namespace App\Controller;


use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Media;
use App\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AdvertController extends Controller
{

    public function index(Environment $twig, Request $request)
    {
        // Et modifiez le 2nd argument pour injecter notre liste
        return $this->render('Advert/index.html.twig', [
        ]);
    }

    public function view(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Advert::class);
        $advert = $repository->findOneById(intval($id));

        if (!$advert instanceof Advert) {
            $advert = [
                'title'   => 'Recherche développpeur Symfony2',
                'id'      => $id,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()
            ];
        }

        return $this->render('Advert/view.html.twig', ['advert' => $advert]);
    }

    public function menu()
    {
        $em = $this->getDoctrine()->getManager();
        $listAdverts = $em->getRepository(Advert::class)->findAll();

        return $this->render('Advert/menu.html.twig', [
            'listAdverts' => $listAdverts
        ]);
    }

    public function add(Request $request)
    {
        $advert = new Advert();
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Advert::class);
        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

        if ($request->isMethod('POST')) {
//dump($request);
            $form->handleRequest($request);
//            dump($form);
//            dump($form->getData());
//            die;
            if ($form->isValid() && $form->isSubmitted()) {
//                $advert->getImage()->upload();
                $em->persist($advert);
//                dump($advert);
//                die;
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

                return $this->redirectToRoute('oc_platform_home');
            }
        }

        return $this->render('Advert/add.html.twig', [
                'form' => $form->createView()
            ]
        );
    }

    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository(Advert::class)->find($id);

        $listCategories = $em->getRepository(Category::class)->findAll();
        if ($annonce instanceof Advert)
        {
            foreach ($listCategories as $category)
            {
                $annonce->addCategory($category);
            }
        }

        $em->flush();

        return $this->render('Advert/edit.html.twig');
    }
    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Advert::class);
        $advert = $repo->find($id);
        if ($advert instanceof Advert)
        {
            foreach ($advert->getCategories() as $category)
            {
                $advert->removeCategory($category);
            }
        }
        $em->remove($advert);
        $em->flush();

        return $this->render('Advert/index.html.twig');
    }

}