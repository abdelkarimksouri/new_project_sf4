<?php

namespace App\Controller;


use App\Entity\Advert;
use App\Entity\Media;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AdvertController extends Controller
{

    public function index(Environment $twig)
    {
        // Notre liste d'annonce en dur
        $listAdverts = [
            [
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()
            ],
            [
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()
            ],
            [
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime()
            ]
        ];

        // Et modifiez le 2nd argument pour injecter notre liste
        return $this->render('Advert/index.html.twig', [
            'listAdverts' => $listAdverts
        ]);
    }

    public function view(Request $request, $id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Advert::class);
        $advert = $repository->findOneById(intval($id));
//        dump($advert->getImage());die;
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
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = [
            ['id' => 1, 'title' => 'Recherche développeur Symfony'],
            ['id' => 2, 'title' => 'Mission de webmaster'],
            ['id' => 3, 'title' => 'Offre de stage webdesigner']
        ];

        return $this->render('Advert/menu.html.twig', [
            'listAdverts' => $listAdverts
        ]);
    }

    public function add(Request $request)
    {
        $advert = new Advert();
        $advert->setTitle('test');
        $advert->setContent("voila mon 1ere test statique pour enregistrer unn nouveau annonce");
        $advert->setAuthor('Abdelkarim');
        $advert->setPublished(true);

        $image = new Media();
        $image->setFilePath('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setFileSize("25");
        $image->setLabel("Red light");
        $image->setIsDeleted(false);
        $image->setIsTreated(true);
        $image->setFileType('jpg');
        $image->setUploadedAt(new \DateTime());

        $advert->setImage($image);
        $em = $this->getDoctrine()->getManager();

        $em->persist($advert);
        $em->flush();

        return $this->render('Advert/add.html.twig');
    }

    public function edit(Request $request)
    {
        return $this->render('Advert/edit.html.twig');
    }
    public function delete(Request $request)
    {
        return $this->render('Advert/delete.html.twig');
    }

}