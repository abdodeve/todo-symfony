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
     * @Route("todos/insert", name="insertTodo")
     * @Method("POST")
     */
    public function insertAction(Request $request)
    {
         // get data from request
        $data = json_decode($request->getContent());

        // get EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Add new record
        $todo = new Todo();
        $todo->setTitle($data->title);
        $todo->setDescription($data->description);

        // Save to database
        $entityManager->persist($todo);
        $entityManager->flush();

        return $this->json([ 'success' => true,
                             'inserted_todo' => $todo->getId()
                           ]);
    }

    /**
     * @Route("todos/update/{id}", name="updateTodo")
     * @Method("POST")
     */
    public function updateAction(Request $request, $id)
    {
        // get data from request
        $data = json_decode($request->getContent());

        // get EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Find record
        $todo = $entityManager->getRepository(Todo::class)->find($id);

        // Check if exist
        if (!$todo)
            return $this->json(['error'=>'not_found']);

        $todo->setTitle($data->title);
        $todo->setDescription($data->description);
        $entityManager->flush();


        return $this->json([ 'success' => true,
            'updated_todo' => $todo->getDescription()
        ]);

    }


    /**
     * @Route("todos/single/{id}", name="singleTodo")
     * @Method("POST")
     */
    public function singleAction(Request $request, $id)
    {
        // get EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Find record
        $todo = $entityManager->getRepository(Todo::class)->find($id);

        // Check if not exist
        if (!$todo)
            return $this->json(['error'=>'not_found']);

        $todo = [
                  'id'=>$todo->getId(),
                  'title'=>$todo->getTitle(),
                  'description'=>$todo->getDescription()
                ] ;

        return $this->json([ 'success' => true,
                             'single_todo' => $todo
                          ]);
    }

    /**
     * @Route("todos/delete/{id}", name="insertTodos")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        // get EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Find record
        $todo = $entityManager->getRepository(Todo::class)->find($id);

        // Check if not exist
        if (!$todo) {
            return $this->json(['error'=>'not_found']);
        }

        // Get the ID
        $todo_id = $todo->getId() ;

        // Delete
        $entityManager->remove($todo);
        $entityManager->flush();

        return $this->json([ 'success' => true,
            'delete_todo' => $todo_id
        ]);
    }

}
