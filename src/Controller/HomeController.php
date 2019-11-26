<?php

namespace App\Controller;


use App\Entity\BiceaAdmin;
use App\Entity\User;
use App\Repository\OffreRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    # ----data of login form


    /**
     * @Route("/", name ="home")
     */

    public function home(Request $request, OffreRepository $repository)
    {
        $offres = $repository->findAll();
        return $this->render('home/home.html.twig', [
            'offres' => $offres
        ]);

    }

    /**
     * @Route("/details/{id}", name ="details")
     */

    public function details($id,Request $request, OffreRepository $repository)
    {
        $offre = $repository->find($id);
        return $this->render('home/details.html.twig', [
            'offre' => $offre
        ]);

    }








}
