<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TodosController extends Controller
{
    /**
     * @Route("todos/fetch", name="fetchTodos")
     */
    public function fetchAction(Request $request)
    {
        $todos = $this->getDoctrine()
        ->getRepository('AppBundle:Todo')
        ->findAll();
        
        $newTodos = [] ;
        foreach( $todos as $todo ) {
            $newTodos[] = [
                'id'=> $todo->getId(),
                'title'=> $todo->getTitle(),
                'description'=> $todo->getDescription()
            ] ;
        }

       return $this->json(['todos' => $newTodos]);
    }


    /**
     * @Route("todos/insert", name="insertTodos")
     */
    public function insertAction(Request $request)
    {
        // $todo = new \AppBundle\Entity\Todo();
        
        // $todo->setTitle($form['name']->getData());
        // $todo->setDescription($form['category']->getData());

        // $em = $this->getDoctrine()->getManager();
        // $em->persist($todo);
        // $em->flush();
        
        // $this->addFlash('notice', 'Todo Added');
        
        // return $this->redirectToRoute('todo_list');

        $content = $request->getContent();
        // var_dump($content);
        $tmp = $request->request->get('title');
        var_dump($tmp);
        die();

    }

}
