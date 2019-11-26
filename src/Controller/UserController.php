<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    const login = 'loginUser';
    const password = 'pwd';
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserRepository $userRepository, ObjectManager $manager)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form ->isSubmitted() && $form->isValid()){

            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Compte créer avec succés veuillez vous connectez! '
            );
            return $this->redirectToRoute('login');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, UserRepository $userRepository)
    {
        $user = new User();

        if ($request->getMethod() == 'POST'){
            $user = $userRepository->findOneBy(array($user::loginUser => $request->get(self::login)));
            if ($user == Null ){
                $this->addFlash('warning', 'Ce compte n\'existe pas veuillez vous inscrire');

                return $this->redirectToRoute("register");

            }else if ($user->getPassword() != $request->get(self::password)) {
                $this->addFlash('warning', 'Votre mot de passe est incorrect');

                return $this->redirectToRoute("login");
            }else if(self::login == "" or self::password == ""){
                $this->addFlash('warning', 'Vous devez remplir tous les champs');
            } else{
                $session = $request->getSession();
                $session->set('user', $user);
                return $this->redirectToRoute('home');
            }

        }
        return $this->render('user/login.html.twig');
    }

    /**
     * @Route("/logout", name ="logout")
     */

    public function logout(Request $request)
    {
        $session = $request->getSession();
        $session->invalidate(); //here we can now clear the session.
        return $this ->redirectToRoute('login');
    }


}
