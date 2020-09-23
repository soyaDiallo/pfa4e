<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtablissementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Etablissement;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/list-etablissement", name="list_etablissement")
     */
    public function etablissments(EtablissementRepository $EtablissementRepository): Response
    {
        return $this->render('home/list-etablissement.html.twig', [
            'listEtablissements' => $EtablissementRepository->findAll(),
        ]);
    }

}
