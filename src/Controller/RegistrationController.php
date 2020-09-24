<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\DirecteurPedagogique;
use App\Entity\Entreprise;
use App\Entity\Etablissement;
use App\Entity\Laureat;
use App\Entity\Secretaire;
use App\Form\RegistrationFormType;
use App\Form\EtablissementRegistrationFormType;
use App\Form\LaureatRegistrationFormType;
use App\Form\SecretaireRegistrationFormType;
use App\Form\DirecteurPedagogiqueRegistrationFormType;
use App\Form\EntrepriseRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $accountType = $request->query->get('accountType');

        if($accountType == null){
            return $this->redirectToRoute('home');
        }

        $renderPage = 'registration/register.html.twig';

        switch ($accountType) {
            case 'directeur_pedagogique':
                $user = new DirecteurPedagogique();
                $form = $this->createForm(DirecteurPedagogiqueRegistrationFormType::class, $user);
                $role = ['ROLE_DIRECTEUR'];
                $renderPage  = 'registration/register-directeur.html.twig';
                break;
            case 'laureat':
                $user = new Laureat();
                $form = $this->createForm(LaureatRegistrationFormType::class, $user);
                $role = ['ROLE_LAUREAT'];
                $renderPage  = 'registration/register-laureat.html.twig';
                break;
            case 'etablissement':
                $user = new Etablissement();
                $form = $this->createForm(EtablissementRegistrationFormType::class, $user);
                $role = ['ROLE_ETABLISSEMENT'];
                $renderPage = 'registration/register-etablissment.html.twig';
                break;
            case 'entreprise':
                $user = new Entreprise();
                $form = $this->createForm(EntrepriseRegistrationFormType::class, $user);
                $role = ['ROLE_ENTREPRISE'];
                $renderPage  = 'registration/register-entreprise.html.twig';
                break;
            case 'secretaire':
                $user = new Secretaire();
                $form = $this->createForm(SecretaireRegistrationFormType::class, $user);
                $role = ['ROLE_SECRETAIRE'];
                break;                            
            default:
                return $this->redirectToRoute('home');
                break;
        }

        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setEmail($form->get('email')->getData());

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setDeleted(false);
            
            $user->setRoles($role);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render($renderPage, [
            'registrationForm' => $form->createView(),
        ]);
    }
}