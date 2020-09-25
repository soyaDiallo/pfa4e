<?php

namespace App\Controller;

use App\Entity\Demande;
use App\Form\DemandeEntrepriseType;
use App\Form\DemandeLaureatType;
use App\Form\DemandeSecretaireStatusType;
use App\Form\DemandeDirecteurStatusType;
use App\Form\DemandeEtablStatusType;
use App\Repository\DemandeRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtablissementRepository;
use App\Repository\DiplomeRepository;
use App\Repository\LaureatRepository;
use Endroid\QrCode\QrCode;
use setasign\Fpdi\Fpdi;

/**
 * @Route("/demande")
 * @IsGranted({"ROLE_LAUREAT", "ROLE_ENTREPRISE", "ROLE_SECRETAIRE", "ROLE_ETABLISSEMENT", "ROLE_DIRECTEUR"})
 */
class DemandeController extends AbstractController
{
    const ETAT_PROCESS = 2;
    const ETAT_NOT_VALIDE = 0;
    const ETAT_VALIDE = 1;

    /**
     * @Route("/", name="demande_index", methods={"GET"})
     */
    public function index(DemandeRepository $demandeRepository): Response
    {
      // Setting appropriate user type
      switch ($this->getUser()) {
        case $this->isGranted('ROLE_LAUREAT'):
          $demandes = $demandeRepository->findByLaureat(['laureat_id' => $this->getUser()]);      
          break;
        case $this->isGranted('ROLE_ENTREPRISE'):
          $demandes = $demandeRepository->findByEntreprise(['entreprise_id' => $this->getUser()]);      
          break;
        case $this->isGranted(["ROLE_SECRETAIRE"]):
          // get demandes already validate by Secretaire
          $demandes = $demandeRepository->findByEtablissement(['etablissement_id' => $this->getUser()->getEtablissement()]);
          break; 
        case $this->isGranted(["ROLE_DIRECTEUR"]):
          // get demandes already validate by Secretaire
          $demandes = $demandeRepository->findDemandeDirecteur($this->getUser()->getEtablissement(), self::ETAT_VALIDE);
          //dd($demandes);
          break; 
        case $this->isGranted(["ROLE_ETABLISSEMENT"]):
          // get demandes already validate by Secretaire and Directeur
          $demandes = $demandeRepository->findDemandeEtablissment($this->getUser(), self::ETAT_VALIDE);
          break;                          
        default:
          $demandes = null;
          break;
      }
       //dd($demandes);
      return $this->render('demande/index.html.twig', [
        'demandes' => $demandes
      ]);
    }

    /**
     * @Route("/new", name="demande_new", methods={"GET","POST"})
     * @IsGranted({"ROLE_LAUREAT", "ROLE_ENTREPRISE"})
     */
    public function new(Request $request): Response {
    	// Setting appropriate user type
        switch ($this->getUser()) {
          case $this->isGranted('ROLE_LAUREAT'):
            $demande = new Demande();
            $form = $this->createForm(DemandeLaureatType::class, $demande);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
              $demande->setLaureat($this->getUser());
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($demande);
              $entityManager->flush();
              return $this->redirectToRoute('demande_index');
            }

            return $this->render('demande/laureat_new.html.twig', [
              'demande' => $demande,
              'form' => $form->createView(),
            ]);   
            break;
          case $this->isGranted('ROLE_ENTREPRISE'):
            $demande = new Demande();
            $form = $this->createForm(DemandeEntrepriseType::class, $demande);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
              $demande->setEntreprise($this->getUser());
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($demande);
              $entityManager->flush();
              return $this->redirectToRoute('demande_index');
            }

          return $this->render('demande/entreprise_new.html.twig', [
            'demande' => $demande,
            'form' => $form->createView(),
          ]);      
          break;                            
        default:
          break;
        }
    }
    
    /**
     * @Route("/{id}/edit", name="validate_demande", methods={"GET","POST"})
     * @IsGranted({"ROLE_ETABLISSEMENT", "ROLE_SECRETAIRE", "ROLE_DIRECTEUR"})
    */
    public function edit(Request $request, Demande $demande, UserRepository $userRepository, \Swift_Mailer $mailer, DemandeRepository $demandeRepository,EtablissementRepository $etablissementRepository, DiplomeRepository $diplomeRepository,LaureatRepository $laureatRepository): Response {
        if(!$demande){
          $demande = new Demande();
        }

        // Setting appropriate user type
        switch ($this->getUser()) {
          case $this->isGranted('ROLE_SECRETAIRE'):
            $form = $this->createForm(DemandeSecretaireStatusType::class, $demande);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
              $demande->setDateValidationSecretaire(new \DateTime());
              $demande->setSecretaire($this->getUser());
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($demande);
              $entityManager->flush();
              // Sent Email 
              // if Secretaire say that diplome is not valide send to laureat message
              if($demande->getEtatSecretaire() == self::ETAT_NOT_VALIDE) {
                $email = $userRepository->getEmailLaureat($demande->getLaureat()->getId());
                $name = $userRepository->getNom($demande->getLaureat()->getId());
                $subject = 'Votre demande a été annulée';
                $message = 'Votre diplome a été annulé par Secretaire, car il ne respecte pas les régles de la direction !';
                $this->sentEmailValidation($email, $subject, $message, $name, $mailer);
              } elseif ($demande->getEtatSecretaire() == self::ETAT_VALIDE) {
                // To DO Later need to get Directeur Id
                // $email = $userRepository->getEmailLaureat($demande->getDirecteurPedagogique()->getId());
                // $subject = 'Deamnde Valider Par La Secretaire';
                // $message = 'cette Demande et Deja valider avec successe par la secraitaire il attend votre validation !';
                // $this->sentEmailValidation($email,$subject, $message, $mailer);
              }
              return $this->redirectToRoute('demande_index');
            }
            break;
          case $this->isGranted('ROLE_DIRECTEUR'):
            $form = $this->createForm(DemandeDirecteurStatusType::class, $demande);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
              $demande->setDateValidationDP(new \DateTime());
              $demande->setDirecteurPedagogique($this->getUser());
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($demande);
              $entityManager->flush();
              // Sent Email 
              // if Directeur say that diplome is not valide send to laureat message
              if($demande->getEtatDirecteurPd() == self::ETAT_NOT_VALIDE) {
                $email = $userRepository->getEmailLaureat($demande->getLaureat()->getId());
                $name = $userRepository->getNom($demande->getLaureat()->getId());
                $subject = 'Votre demande a été annulée';
                $message = 'Votre diplome a été annulé par Directeur, car il ne respecte pas les régles de la direction !';
                $this->sentEmailValidation($email, $subject, $message, $name, $mailer);
              } elseif ($demande->getEtatDirecteurPd() == self::ETAT_VALIDE) {
                $email = $userRepository->getEmailLaureat($demande->getEtablissement()->getId());
                $name = $userRepository->getNom($demande->getEtablissement()->getId());
                $subject = 'Deamnde Valider Par La Directeur';
                $message = 'cette Demande et Deja valider avec successe par la secraitaire et le Directeur pedagogique. yup !';
                $this->sentEmailValidation($email,$subject, $message, $name, $mailer);
              }
              return $this->redirectToRoute('demande_index');
            }
            break;
          case $this->isGranted('ROLE_ETABLISSEMENT'):
            $form = $this->createForm(DemandeEtablStatusType::class, $demande);
            $form->handleRequest($request);
            // dd($demande->getDiplome()->getDateObtention());
            if ($form->isSubmitted() && $form->isValid()) {
              $demande->setDateValidationDE(new \DateTime());

              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($demande);
              $entityManager->flush();
              if($demande->getEtatDirecteurGn() == self::ETAT_NOT_VALIDE) {
                $email = $userRepository->getEmailLaureat($demande->getLaureat()->getId());
                $name = $userRepository->getNom($demande->getLaureat()->getId());
                $subject = 'Votre demande a été annulée';
                $message = 'Votre diplome a été annulé par Etablissment, car il ne respecte pas les régles de la direction !';
                $this->sentEmailValidation($email, $subject, $message, $name, $mailer);
              } elseif ($demande->getEtatDirecteurGn() == self::ETAT_VALIDE) {
                $id = $demande->getId();
                $this->GenerateDiplome($demandeRepository, $id, $diplomeRepository, $laureatRepository, $userRepository);
                $email = $userRepository->getEmailLaureat($demande->getLaureat()->getId());
                $name = $userRepository->getNom($demande->getLaureat()->getId());
                $subject = 'Votre Demande a été valider avec succes';
                $message = 'cette Demande et valider avec Success Par votre Etablissment !';
                $this->sentEmailValidation($email,$subject, $message, $name, $mailer);
              }
              return $this->redirectToRoute('demande_index');
            }
            break;
          default:
            $demande = null;
            break;
        }
        return $this->render('demande/secretaire_new.html.twig', [
          'demande' => $demande,
          'form' => $form->createView(),
        ]);
    }

    private function sentEmailValidation($email, $subject, $template, $name, $mailer) {
      // L'envoi d'un Email au Laureat
      $message = (new \Swift_Message($subject))
          ->setFrom('diplome.verification@diploma.ma')
          // email de la secretaire
          ->setTo($email)
          ->setBody(
              $this->renderView(
                // templates/emails/registration.html.twig
                'emails/demande-validation.html.twig',
                ['message' => $template, 'name' => $name]
            ),
            'text/html'
          );
      $mailer->send($message);
    }

    public function GenerateDiplome($demandeRepository, $id, $diplomeRepository, $laureatRepository, $userRepository)
    {
        $date = new \DateTime('now');

        $demandeRepository->updateDemande($id,$date,1);

        $idEtab = $demandeRepository->getEtab($id);
        $idDiplome = $demandeRepository->getIdDiplome($id);

        $nameDiplome = $diplomeRepository->getNameDiplome($idDiplome);
        $codeDiplome = $diplomeRepository->getCode($idDiplome);
        $idLaureat = $demandeRepository->getIdLaureat($id);

        $getnom = $userRepository->getnom($idLaureat);
        $getprenom = $userRepository->getprenom($idLaureat);
        $getcin = $laureatRepository->getcin($idLaureat);

        // Create a QR code
        $qrCode = new QrCode($getnom.' '.$getprenom."\n".'Cin/sejour: '.$getcin."\n".'Code diplome: '.$codeDiplome);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);
        $qrCode->setEncoding('UTF-8');
        $source1 = $this->getParameter('QrCode_directory');
        $qrCode->writeFile($source1.'/QRcode'.$id.'.png');

        // la generation de QR code au niveau de pdf
        $pdf = new FPDI();
        $source = $this->getParameter('diplome_directory');
        $pdf->setSourceFile($source.'/'.$nameDiplome);
        $template = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($template);
        $pdf->Image($source1.'/QRcode'.$id.'.png',6,270,20,20);
        $source2 = $this->getParameter('Autentifier_directory');
        $fichier = 'diplome'.$id.'L'.$idLaureat.'.pdf';
        $pdf->Output($source2.'/'.$fichier,'F');

        $diplomeRepository->updateDiplome($idDiplome,$fichier,$date);

    }
    

    // /**
    //  * @Route("/{id}/modify", name="demande_edit", methods={"GET","POST"})
    //  */
    // public function modify(Request $request, Demande $demande): Response
    // {
    //   $form = $this->createForm(DemandeType::class, $demande);
    //   $form->handleRequest($request);

    //   if ($form->isSubmitted() && $form->isValid()) {
    //     $this->getDoctrine()->getManager()->flush();
    //     return $this->redirectToRoute('demande_index');
    //   }

    //   return $this->render('demande/edit.html.twig', [
    //     'demande' => $demande,
    //     'form' => $form->createView(),
    //   ]);
    // }

    /**
     * @Route("/{id}", name="demande_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Demande $demande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($demande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('demande_index');
    }
}
