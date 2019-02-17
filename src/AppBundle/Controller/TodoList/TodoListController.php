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
// use AppBundle\Controller\TodoList\TodoListService ;

class TodoListController extends Controller
{
    
      /**
     * @Route("/", name="TodoListFetch")
     * @Method({"GET", "POST"})
     */
    public function fetchAction(Request $request)
    {
        $tdlSer = $this->get('app.todolistservice');
        var_dump($tdlSer->myservice);
        die();

        // Get
        TodoListService::$entityManager = $this->getDoctrine()->getManager();
        TodoListService::$createFormBuilder = $this->createFormBuilder() ;

      //  TodoListService::setEntityManager( $entityManager );
        $todoLists = TodoListService::getTodoLists();
        $todos = TodoListService::getTodos();
        $uncategorizedTodos = TodoListService::getUncategorizedTodos();

        // Forms
        $todoListForm = TodoListService::formTodoList();
        $todoForm = TodoListService::formTodo();

        return $this->render('homepage.html.twig', [    'todoLists'=> $todoLists,
                                                        'todos'=> $todos,
                                                        'uncategorizedTodos'=> $uncategorizedTodos,
                                                        'todoListForm' => $todoListForm->createView()
                                                    ]);
    }

    // /**
    //  * @Route("/", name="TodoListFetch")
    //  * @Method({"GET", "POST"})
    //  */
    // public function fetchAction(Request $request)
    // {
    //    $tmp = TodoListService::testFunc();
    //    var_dump($tmp);
    //     // Get TodoLists
    //     $todoArr = [] ;
    //     $todoRepository = $this->getDoctrine()
    //                            ->getManager()
    //                            ->getRepository('AppBundle:TodoList');
    //     $todoLists = $todoRepository->fetch();

    //     // Get Todos
    //     $todos = $this->getDoctrine()
    //                 ->getRepository('AppBundle:Todo')
    //                 ->findAll();
        
    //     // Get Uncategorized todos
    //     $todoRepository = $this->getDoctrine()
    //                             ->getManager()
    //                             ->getRepository('AppBundle:Todo');
    //     $uncategorizedTodos = $todoRepository->uncategorizedTodos();        

    //     /*
    //     |-------------------------------------------------------------------------------------------------------------
    //     |  BEGIN FORMS
    //     |-------------------------------------------------------------------------------------------------------------
    //     */

    //     /*********************************
    //      * BEGIN TodoList Form
    //      *********************************/
    //     // Create TodoList Form
    //     $todoListForm = $this->createFormBuilder()
    //                           ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
    //                           ->getForm();
    //     // Submit TodoList Form
    //     $todoListForm->handleRequest($request);
    //     if ($todoListForm->isSubmitted() && $todoListForm->isValid()) {
    //             $getTodoList = $todoListForm->getData();
    //             $res = $this->insert( $getTodoList );
    //             return $this->redirectToRoute('TodoListFetch');
    //     }
    //     /*********************************
    //      * END TodoList Form
    //      *********************************/


    //     /*********************************
    //      * BEGIN Todo Form
    //      *********************************/
    //     // Create Todo Form
    //     $todoForm = $this->createFormBuilder()
    //                         ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
    //                         ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
    //                         ->getForm();
    //     // Submit Todo Form
    //     $todoForm->handleRequest($request);
    //     if ($todoForm->isSubmitted() && $todoForm->isValid()) {
    //             $getTodoList = $todoForm->getData();
    //             $res = $this->insert( $getTodoList );
    //             return $this->redirectToRoute('TodoListFetch');
    //     }
    //     /********************************
    //     * END Todo Form
    //     *********************************/

    //     /*
    //     |-------------------------------------------------------------------------------------------------------------
    //     |  END FORMS
    //     |-------------------------------------------------------------------------------------------------------------
    //     */

    //     return $this->render('homepage.html.twig', ['todoLists'=> $todoLists,
    //                                                     'todos'=> $todos,
    //                                                     'uncategorizedTodos'=> $uncategorizedTodos,
    //                                                     'todoListForm' => $todoListForm->createView()
    //                                                     ]);
    //     return $this->json($todoLists);
    // }


    /**
     * Insert new TodoList
     * @param $todoList array
     * @return bool
     */
    public function insert( $todoList )
    {
         // get data from request
       
    //    $data = json_decode(json_decode($todoList)) ;
        $data = json_encode($todoList) ;
        $data = json_decode($data);
       // print_r($data->name); die();

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

        $todoArr = [] ;
        $todoListRepository = $this->getDoctrine()
                               ->getManager()
                               ->getRepository('AppBundle:TodoList');
        $todos = $todoListRepository->delete( $data->ids );

        return $this->json($todos);
    }


}
