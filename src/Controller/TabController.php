<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TabController extends AbstractController
{
    #[Route('/tab/{nb<\d+>?5}', name: 'tab')]
    public function index($nb): Response
    {

    $notes=[];
    for($i=0;$i<$nb;$i++){
        $notes[$i]=rand(0,20);
    }
        return $this->render('tab/index.html.twig', [
            'notes' => $notes,
        ]);
    }
    #[Route('/tab/users', name: 'tab_users')]
    public function users(): Response
    {
$users=[

['firstname'=>'Rihab','name'=>'Zemni','age'=>21],

['firstname'=>'Ahmed','name'=>'MK','age'=>31],

['firstname'=>'Med','name'=>'Mohammed','age'=>43],

];
return $this->render('tab/users.html.twig', [
            'users' => $users,
        ]);
}
}