<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Entity\DirecteurPedagogique;
use App\Entity\Secretaire;
use App\Form\EtablissementType;
use App\Repository\DemandeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SecretaireRegistrationFormType;
use App\Form\DirecteurPedagogiqueRegistrationFormType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/etablissement")
 * @IsGranted({"ROLE_ETABLISSEMENT"})
 */
class EtablissementController extends AbstractController
{

    /**
     * @Route("/profile/{id}", name="etablissement_show", methods={"GET"})
     */
    public function show(Etablissement $etablissement): Response
    {
        return $this->render('etablissement/show.html.twig', [
            'etablissement' => $etablissement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etablissement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etablissement $etablissement): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['photoUrl']->getData(); 
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try{
                $file->move($this->getParameter('image_directory'),$fileName);
            }catch (FileException $e){
                dump($e);
            }

            $etablissement->setPhotoUrl($fileName);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le compte a été modifié avec succes');

            return $this->redirectToRoute('etablissement_show',array('id' => $etablissement->getId()));
        }

        return $this->render('etablissement/edit.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etablissement_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Etablissement $etablissement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etablissement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $etablissement->setDeleted(1);
            $entityManager->flush();
            return $this->redirectToRoute('app_logout');
        }

        return $this->redirectToRoute('etablissement_show',array('id' => $etablissement->getId()));
    }


    /**
 * @Route("/demande/{id}", name="etablissement_index2", methods={"GET"})
 */
    public function index2(DemandeRepository $demandeRepository,$id): Response
    {
        return $this->render('etablissement/profilDemande.html.twig', [
            'demandes' => $demandeRepository->getDiplomesEtab($id)
        ]);
    }

    /**
     * @Route("/profiletab/{id}", name="etablissement_profil", methods={"GET"})
    */
    public function profiletablissement(Etablissement $etablissement):Response
    {
        return $this->render('etablissement/profilEtab.html.twig',[
        'etablissement' => $etablissement
        ]);
    }

    /**
     * @Route("/profilapropos/{id}", name="etablissement_apropos", methods={"GET"})
    */
    public function profilapropos(etablissement $etablissement):Response
    {
        return $this->render('etablissement/profilApropos.html.twig',[
        'etablissement' => $etablissement
        ]);
    }

    // Désactiver compte etablissement
    /**
     * @Route("/profildesactiver/{id}", name="etablissement_desactiver", methods={"GET","POST"})
    */
    public function profildesactiver(etablissement $etablissement,Request $request):Response
    {
        $action=$request->request->get("desac");

        if($action)
        {
            // Exemple : 1 = compte désactiver
        $etablissement->setDeleted(1);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($etablissement);
        $entityManager->flush();


        return $this->render('etablissement/profilEtab.html.twig',[
            'etablissement' => $etablissement,
        ]);
        }
        return $this->render('etablissement/profilDesactiver.html.twig',[
            'etablissement' => $etablissement,
        ]);
    }

    /**
 * @Route("/profildemande/{id}", name="etablissement_demande", methods={"GET","POST"})
*/
    public function profildemande(Etablissement $etablissement,DemandeRepository $demandeRepository,$id):Response
    {

        return $this->render('etablissement/profildemande.html.twig',[
            'etablissement' => $etablissement,
            'demandes' => $demandeRepository->getDiplomesEtab($id)
        ]);
    }

    /**
     * @Route("/register-account", name="app_etablissement_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler): Response
    {
        $accountType = $request->query->get('accountType');

        if($accountType == null){
            return $this->redirectToRoute('home');
        }

        $etablissement = $this->getUser();

        switch ($accountType) {
            case 'directeur_pedagogique':
                $user = new DirecteurPedagogique();
                $form = $this->createForm(DirecteurPedagogiqueRegistrationFormType::class, $user);
                $role = ['ROLE_DIRECTEUR'];
                $renderPage  = 'etablissement/register/register-directeur.html.twig';
                break;
            case 'secretaire':
                $user = new Secretaire();
                $form = $this->createForm(SecretaireRegistrationFormType::class, $user);
                $role = ['ROLE_SECRETAIRE'];
                $renderPage  = 'etablissement/register/register-secretaire.html.twig';
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
            $user->setEtablissement($etablissement);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // We can Send Email To New Account 
            
            // return $guardHandler->authenticateUserAndHandleSuccess(
            //     $user,
            //     $request,
            //     $authenticator,
            //     'main' // firewall name in security.yaml
            // );
            $this->addFlash('success', 'Directeur Account was created Successfully!');
            return $this->redirect($request->getUri());
        }

        return $this->render($renderPage, [
            'registrationForm' => $form->createView(),
        ]);
    }
}
