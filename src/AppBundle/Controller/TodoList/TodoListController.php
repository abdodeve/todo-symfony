<?php

namespace AppBundle\Controller\TodoList;

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
     * @Route("/", name="TodoListFetch")
     * @Method({"GET", "POST"})
     */
    public function fetchAction(Request $request)
    {
        // Instance todoListService
        $todolistService = $this->get('app.todolistservice');

        // Call service methodes
       $getSharedData = $todolistService->getSharedData();
       $formTodoList  = $todolistService->formTodoList() ;

        // Forms
        if( $formTodoList->submited ) 
            return $formTodoList->uri ;

        return $this->render('homepage.html.twig', [     'todoLists'            => $getSharedData->todoLists,
                                                         'todos'                => $getSharedData->todos,
                                                         'uncategorizedTodos'   => $getSharedData->uncategorizedTodos,
                                                         'todoListForm'         => $formTodoList->form->createView()
                                                    ]
                            );
    }


    /**
     * @Route("todolist/single/{id}", name="TodoListSingle")
     * @Method({"GET", "POST"})
     */
    public function todoListSingleAction(Request $request, $id)
    {
        // Instance todoListService
        $todolistService = $this->get('app.todolistservice');

        // Call service methodes
       $singleTodoListArr   = $todolistService->single( $id ) ;
       $formTodoList        = $todolistService->formTodoList( $singleTodoListArr ) ;

        // Forms
        if( $formTodoList->submited ) 
            return $formTodoList->uri ;

        return $this->render('homepage.html.twig', [     'todoLists'            => $todolistService->getTodoLists(),
                                                         'todos'                => $todolistService->getTodos( $id ),
                                                         'uncategorizedTodos'   => $todolistService->getUncategorizedTodos(),
                                                         'todoListForm'         => $formTodoList->form->createView(),
                                                         "currentID"=> $id
                                                    ]
                            );
    }

    /**
     * Insert new TodoList
     * @param $todoList array
     * @return bool
     */
    public function insert( $todoList )
    {
         // get data from request
        $data = json_encode($todoList) ;
        $data = json_decode($data);

        if(!$data) return false ;

        // get EntityManager
        $entityManager = $this->getDoctrine()->getManager();

        // Add new record
        $todoList = new TodoList();
        
        // Sets
        $todoList->setName($data->name ?? NULL);

        // Save to database
        $entityManager->persist($todoList);
        $entityManager->flush();

        return true ;
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
        return $this->json($data);

        // $todoArr = [] ;
        // $todoListRepository = $this->getDoctrine()
        //                        ->getManager()
        //                        ->getRepository('AppBundle:TodoList');
        // $todos = $todoListRepository->delete( $data->ids );

        // return $this->json($todos);
    }


}
