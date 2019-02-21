<?php

namespace AppBundle\Controller\TodoList;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query ;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;


use AppBundle\Entity\Todo ;
use AppBundle\Entity\TodoList ;
use AppBundle\Repository\TodoRepository ;

class TodoListService
{
    public static $createFormBuilder;

    public $testVar = "123" ;
    public $entityManager ;
    public $formFactory ;
    protected $requestStack;

    public function __construct( \Doctrine\ORM\EntityManager    $entityManager, 
                                                                $formFactory, 
                                 RequestStack                   $requestStack,
                                 RouterInterface                $router){

        $this->entityManager    = $entityManager;
        $this->formFactory      = $formFactory ;
        $this->requestStack     = $requestStack;
        $this->router           = $router;
    }

    // public function setMyservice( $myservice ){
    //    $this->myservice = $myservice ;
    //    return $this ;
    // }


    /**
     * Get TodoLists
     * @return todoLists array
     */
    public function getTodoLists()
    {
        $todoArr = [] ;
        $todoRepository = $this->entityManager
                               ->getRepository('AppBundle:TodoList');
        return $todoRepository->fetch();
    }


    /**
     * Get Todos
     * @return todos array
     */
    public function getTodos()
    {
        // Get Todos
        $todos = $this->entityManager
                        ->getRepository('AppBundle:Todo')
                        ->findAll();
        return $todos ;
    }

    /**
     * Get UncategorizedTodos
     * @return uncategorizedTodos array
     */
    public function getUncategorizedTodos()
    {
        // Get Uncategorized todos
        $todoRepository = $this->entityManager
                                ->getRepository('AppBundle:Todo');
        return $todoRepository->uncategorizedTodos();   
    }


    /**
     * Create & Submit TodoList form
     * @return mixed
     */
    public function formTodoList()
    {
        // return new RedirectResponse($this->router->generate('TodoListFetch'));
        // var_dump($this->router->generate('TodoListFetch'));
        // die();
        $request = $this->requestStack->getCurrentRequest();

        // Create TodoList Form
        $todoListForm = $this->formFactory
                            ->createBuilder()
                            ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
                            ->getForm();

        // Submit TodoList Form
        $todoListForm->handleRequest($request);

        if ($todoListForm->isSubmitted() && $todoListForm->isValid()) {
            $getTodoList = $todoListForm->getData();
            $res = $this->insert( $getTodoList );
           // return $this->redirectToRoute('TodoListFetch');
            return ["redirect"=> new RedirectResponse($this->router->generate('TodoListFetch'))];
        }

        return ["form"=> $todoListForm] ;
    }


    /**
     * Create & Submit Todo form
     * @return mixed
     */
    public function formTodo()
    {
        // Create Todo Form
        $todoForm = $this->formFactory
                            ->createBuilder()
                            ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
                            ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
                            ->getForm();
        // Submit Todo Form
    //    $todoForm->handleRequest($request);
    //     if ($todoForm->isSubmitted() && $todoForm->isValid()) {
    //             $getTodoList = $todoForm->getData();
    //             $res = $this->insert( $getTodoList );
    //             return $this->redirectToRoute('TodoListFetch');
    //     }

        return $todoForm ;
    }

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
       // $entityManager = $this->getDoctrine()->getManager();

        // Add new record
        $todoList = new TodoList();
        
        // Sets
        $todoList->setName($data->name ?? NULL);

        // Save to database
        $this->entityManager->persist($todoList);
        $this->entityManager->flush();

        return true ;
    }

}
