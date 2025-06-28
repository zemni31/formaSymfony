<?php

namespace App\Controller;


use App\Entity\Personne;
use App\Form\PersonneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use App\Service\MailerService;

#[Route('/personne')]
final class PersonneController extends AbstractController
{
    #[Route('/',name:'personne.list')]
    public function index(ManagerRegistry $doctrine):Response{
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render('personne/index.html.twig', [
       'personnes' => $personnes,
    'isPaginated' => false,
]);
    }
    #[Route('/alls/{page?1}/{nbre?10}', name: 'personne.list.alls')]
    public function indexalls(EntityManagerInterface $entityManager, $page, $nbre): Response
    {
    $repository = $entityManager->getRepository(Personne::class);

    // Compter les personnes avec createQuery sur EntityManager (pas sur le repository)
    $query = $entityManager->createQuery('SELECT COUNT(p.id) FROM App\Entity\Personne p');
    $nbpersonne = $query->getSingleScalarResult();

    $nbpage = ceil($nbpersonne / $nbre);
    $personnes = $repository->findBy([], ['age' => 'DESC'], $nbre, ($page - 1) * $nbre);

    return $this->render('personne/index.html.twig', [
        'personnes' => $personnes,
        'isPaginated' => true,
        'page' => $page,
        'nbpage' => $nbpage,
        'nbre' => $nbre,
    ]);
      }


    

    #[Route('/edit/{id<\d+>?0}', name: 'personne.edit')]
    public function editPersonne(ManagerRegistry $doctrine, 
    Request $request, 
    Personne $personne = null, 
    SluggerInterface $slugger,
     #[Autowire('%kernel.project_dir%/public/uploads/images')] string $ImagesDirectory,
    MailerService $mailer): Response
    {
         $new = false;
        if (!$personne) {
            $new = true;
            $personne = new Personne();
            

        }

        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest(($request));
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                
                try {
                    $photo->move($ImagesDirectory, $newFilename);
                } catch (FileException $e) {
                    
                }

                
                $personne->setImage($newFilename);
            }
        $manager=$doctrine->getManager();
        $manager->persist($personne);
        $manager->flush();
        if($new){
         $message =  " a été ajoutée avec succès";
         $mailmessage = "
<html>
  <body>
    <h3>✔️ Ajout confirmée</h3>
    <p>Les informations de la personne <strong>" . $personne->getFirstname() . " " . $personne->getname() . "</strong> ont été ajoutée avec succès.</p>
    <p>Merci.</p>
  </body>
</html>";
$subject="Création de cpmpte - Confirmation";

        } else {         
            $message= " a été éditée avec succès";
                     $mailmessage= "
<html>
  <body>
    <h3>✔️ Modification confirmée</h3>
    <p>Les informations de la personne <strong>" . $personne->getFirstname() . " " . $personne->getname() . "</strong> ont été modifiées avec succès.</p>
    <p>Merci.</p>
  </body>
</html>";
$subject="Modification des informations - Confirmation";
        }
        $this->addFlash('success', $personne->getName() . $message);
        
        
$mailer->sendEmail('mahdizamni83@gmail.com', $mailmessage,$subject);

        return $this->redirectToRoute('personne.list.alls');
        }
        else{
        return $this->render('personne/add-personne.html.twig', [
            'form' => $form->createView()
        ]);}
    }
     #[Route('/delete/{id<\d+>}', name:'personne.delete')]
public function deletePersonne(Personne $personne=null ,ManagerRegistry $doctrine): RedirectResponse
     {if ($personne){
         $manager= $doctrine->getManager();
         $manager->remove($personne);
         $manager->flush();
         $this->addFlash('success','Personne supprimée avec succès');
     }
         else{
                $this->addFlash('error','Personne non trouvée');
         }
    return $this->redirectToRoute('personne.list.alls');
     }
    #[Route('/update/{id<\d+>}/{name}/{firstname}/{age}', name:'personne.update')]
    public function updatePersonne(Personne $personne=null ,ManagerRegistry $doctrine,$name,$firstname,$age): RedirectResponse
    {if ($personne){
        $personne->setName($name);
        $personne->setFirstname($firstname);
        $personne->setAge($age);
        $manager= $doctrine->getManager();
        $manager->persist($personne);
        $manager->flush();
        $this->addFlash('success','Personne modifié avec succès');
    }
    else{
        $this->addFlash('error','Personne non trouvée');
    }
        return $this->redirectToRoute('personne.list.alls');
    }
    #[Route('/{id<\d+>}',name:'personne.detail')]
    public function detail(Personne $personne = null):Response{

        if(!$personne){
          $this->addFlash('error',"Personne  non trouvé");
          return $this->redirectToRoute('personne.list.alls');
        }
        return $this->render('personne/detail.html.twig',['personne'=>$personne]);
    }
}