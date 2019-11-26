<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OffreController extends AbstractController
{
    /**
     * @Route("/offre", name="offres")
     */
    public function offre(Request $request, OffreRepository $offreRepository, ObjectManager $manager, UserRepository $userRepository)
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);
        $userCurrent = $request->getSession()->get('user');
        if($form ->isSubmitted() && $form->isValid()){
            $offre->setCreatedAt(new \DateTime('now') );
            $offre->setUser($userCurrent);
            $manager->persist($offre);
            $manager->flush();
            $this->addFlash(
                'success',
                'Annonce crée avec succès '
            );
            return $this->redirectToRoute('offres');
        }
        $admin_id = $userRepository->find($userCurrent->getId());
        $offres = $offreRepository->findBy( array('User' => $admin_id));
        return $this->render('offre/offre.html.twig', [
            'form' => $form->createView(),
            'offres' => $offres
        ]);
    }

    /**
     * @Route("/edit/offre/{id}", name ="edit_offre")
     */
    public function edit_offre($id, OffreRepository $repository, Request $request, ObjectManager $manager){
        $offre = $repository->find($id);
        if ($offre != null) {
            $form = $this->createForm(OffreType::class,$offre);
            if($request->isMethod('POST'))
            {
                $form->handleRequest($request);
                if($form->isValid())
                {
                    $manager->flush();
                    $this->addFlash(
                        'info',
                        'Offre modifiée avec succes!'
                    );
                    return $this->redirectToRoute('offres');
                }
            }
            return $this ->render('offre/edit_offre.html.twig',[
                'offres'=>$offre,
                'form' =>$form->createView()
            ]);
        }else{
            $this->addFlash(
                'info',
                'Offre n\'existe plus!'
            );
            return $this->redirectToRoute('offres');
        }


    }

    /**
     * @Route("/delete/offre/{id}", name ="delete_offre")
     */
    public function delete_offre($id, OffreRepository $repository, ObjectManager $manager){
        $offre = $repository->find($id);
        if ($offre != null){
            $manager->remove($offre);
            $manager->flush();
            $this->addFlash(
                'info',
                'Offre supprimée avec succes!'
            );
            return $this ->redirectToRoute('offres');
        }else{
            $this->addFlash(
                'info',
                'Offre n\'existe plus!'
            );
            return $this ->redirectToRoute('offres');
        }

    }


}
