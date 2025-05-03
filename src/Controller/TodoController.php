<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
#[Route('/todo')]
final class TodoController extends AbstractController
{
    #[Route('/', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        //pour la 1er visite je vais initiliser mon tableau todo
        if (!$session->has('todos')) {
            $todos=[
                'achat'=>'acheter clé usb',
'cours'=>'Finaliser mon cours',
'correction'=>'corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info',"la liste est initialisee");
        } 
        //si j'ai j'a mon tab todo je vais juste l'afficher
    
        return $this->render('todo/index.html.twig');
    }
    #[Route('/add/{name?test}/{content?test1}', name: 'todo.add')]
    public function todo_add(Request $request,  $name, $content): Response
    {
        $session=$request->getSession();
        if($session->has('todos')){
$todos=$session->get('todos');
if(isset($todos[$name])){
$this->addFlash('error',  "le todo d'id $name existe deja)");
}
else{
    $todos[$name]=$content;
    $session->set('todos', $todos);
   $this->addFlash('success',"le todo d'id $name a ete ajouté avec succès");
}

        }
        else{
$this->addFlash('error',"la liste todo n'est pas encore initialisée");
        }
        
    

        
        return $this->redirectToRoute('app_todo');
    }
   
   
   
   
   
   
    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function todo_update(Request $request,  $name, $content): Response
    {
        $session=$request->getSession();
        if($session->has('todos')){
$todos=$session->get('todos');
if(!isset($todos[$name])){
$this->addFlash('error',  "le todo d'id $name n'existe pas)");
}
else{
    $todos[$name]=$content;
    $session->set('todos', $todos);
   $this->addFlash('success',"le todo d'id $name a ete modifié avec succès");
}

        }
        else{
$this->addFlash('error',"la liste todo n'est pas encore initialisée");
        }
        
    

        
        return $this->redirectToRoute('app_todo');
    }
   
   
   
   
   
    #[Route('/delete/{name}', name: 'todo.delete')]
    public function todo_delete(Request $request,  $name): Response
    {
        $session=$request->getSession();
        if($session->has('todos')){
$todos=$session->get('todos');
if(!isset($todos[$name])){
$this->addFlash('error',  "le todo d'id $name n'existe pas)");
}
else{
   unset($todos[$name]);
    $session->set('todos', $todos);
   $this->addFlash('success',"le todo d'id $name a ete supprimé avec succès");
}

        }
        else{
$this->addFlash('error',"la liste todo n'est pas encore initialisée");
        }
        
    

        
        return $this->redirectToRoute('app_todo');
    }




    #[Route('/reset', name: 'todo.reset')]
    public function todo_reset(Request $request): Response
    {
        $session=$request->getSession();
    $session->remove('todos');
    

        
        return $this->redirectToRoute('app_todo');
    }
}
