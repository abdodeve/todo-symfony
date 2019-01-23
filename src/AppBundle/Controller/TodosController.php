<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


use AppBundle\Entity\Todo ;

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
     * @Method("POST")
     */
    public function insertAction(Request $request)
    {
         // get data from request
        $data = json_decode($request->getContent());

        // Add new record
        $todo = new \AppBundle\Entity\Todo();
        $todo->setTitle($data->title);
        $todo->setDescription($data->description);

        // Save to database
        $em = $this->getDoctrine()->getManager();
        $em->persist($todo);
        $em->flush();

        return $this->json([ 'success' => true,
                             'inserted_todo' => $todo->getId()
                           ]);
    }

}
