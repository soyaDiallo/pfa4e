<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Sendmail;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Sendmail $sendmail)
    {
        $sendmail->call("aichasidibe615@gmail.com");

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
