<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query ;

use AppBundle\Entity\Todo ;
use AppBundle\Entity\TodoList ;
use AppBundle\Repository\TodoRepository ;

class TodoListController extends Controller
{
    
    /**
     * @Route("TodoList/fetch", name="TodoListFetch")
     * @Method("GET")
     */
    public function fetchAction(Request $request)
    {
        $todoArr = [] ;
        $todoRepository = $this->getDoctrine()
                               ->getManager()
                               ->getRepository('AppBundle:TodoList');
        $todos = $todoRepository->fetch();

        return $this->json($todos);
    }


    /**
     * @Route("todoList/insert", name="inserttodoList")
     * @Method("POST")
     */
    public function insertAction(Request $request)
    {
         // get data from request
        $data = json_decode($request->getContent());
        if(!$data) return $this->json(['error' => 'Data empty !']);

        // get EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Add new record
        $todoList = new TodoList();
        
        // Sets
        $todoList->setName($data->name ?? NULL);

        // Save to database
        $entityManager->persist($todoList);
        $entityManager->flush();

        return $this->json([ 'success' => true,
                             'inserted_todo' => $todoList->getId()
                           ]);
    }

    /**
     * @Route("todoList/update/{id}", name="updateTodoList")
     * @Method("POST")
     */
    public function updateAction(Request $request, $id)
    {

        // get data from request
        $data = json_decode($request->getContent());
        if(!$data) return $this->json(['error' => 'Data empty !']);

        // get EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Find record
        $todoList = $entityManager->getRepository(TodoList::class)->find($id);
        // Check if exist
        if (!$todoList)
            return $this->json(['error'=>'not_found']);

        // Sets
        $todoList->setName($data->name ?? NULL);
    
        // Save to database
        $entityManager->persist($todoList);
        $entityManager->flush();

        return $this->json([ 'success' => true,
                             'updated_todoList' => $todoList->getName()
                           ]);

    }


    /**
     * @Route("todoList/single/{id}", name="singleTodoList")
     * @Method("POST")
     */
    public function singleAction(Request $request, $id)
    {
        // get EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Find record
        $todoList = $entityManager->getRepository(TodoList::class)->find($id);

        // Check if not exist
        if (!$todoList)
            return $this->json(['error'=>'not_found']);

        $todoList = [
                        'id'=>$todoList->getId(),
                        'name'=>$todoList->getName(),
                    ] ;

        return $this->json([ 'success' => true,
                             'single_todoList' => $todoList
                          ]);
    }









    /**
     * @Route("TodoList/delete", name="TodoListDelete")
     * @Method("POST")
     */
    public function deleteAction(Request $request)
    {
        // get data from request
        $data = json_decode($request->getContent());

        $todoArr = [] ;
        $todoListRepository = $this->getDoctrine()
                               ->getManager()
                               ->getRepository('AppBundle:TodoList');
        $todos = $todoListRepository->delete( $data->ids );

        return $this->json($todos);
    }


}
