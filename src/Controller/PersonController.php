<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class PersonController extends AbstractController
{
    #[Route('{maVar}<\d+>', name: 'test')]
    public function test($maVar): Response
    {
        return new Response("<h1>Bonjour $maVar</h1>");
    }

    #[Route('/person', name: 'app_person')]
    public function index(): Response
    {
       return $this->render('person/index.html.twig',['prenom'=>'Rihab','nom'=>'Zemni'] );}

       #[Route('/template', name: 'template')]
       public function template(): Response
       {
          return $this->render('template.html.twig');
   
       }

    //#[Route('/hello/{name}/{firstname}', name: 'app_hello')]
    public function hello(SessionInterface $session,$name,$firstname): Response
    {
       // $session->set('section', "GL/RT");
      /*   dump($request); */
       return $this->render('person/hello.html.twig',[
        "name" => $name,
        "firstname" => $firstname,
          

       ] );

    }
    #[Route('/multi/{entier1<\d+>?2}/{entier2<\d+>?2}',
    name:'multiplication'
   )]
    public function multiplication($entier1,$entier2){
$resultat=$entier1*$entier2;

        return new Response("<h1>le resultat de la multiplication de $entier1 et $entier2 est $resultat</h1>");
    }

}
