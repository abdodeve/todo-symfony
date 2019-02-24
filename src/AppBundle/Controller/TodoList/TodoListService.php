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

use Symfony\Component\Form\Extension\Core\Type\FormType ;


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
    public function formTodoList( $data = null )
    {
        $request = $this->requestStack->getCurrentRequest();

        // Create TodoList Form
        $todoListForm = $this->formFactory
                            ->createBuilder(FormType::class, $data)
                            ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
                            ->getForm();

        // Submit TodoList Form
        $todoListForm->handleRequest($request);

        if ($todoListForm->isSubmitted() && $todoListForm->isValid()) {
            $getTodoList = $todoListForm->getData();

            if($data)
                $this->update( $getTodoList );
            else
                $this->insert( $getTodoList );

            return (object)["submited"=>true, "uri"=> new RedirectResponse($this->router->generate('TodoListFetch'))];
        }

        return (object)["submited"=> false, "form"=> $todoListForm] ;
    }


    /**
     * Create & Submit Todo form
     * @return mixed
     */
    public function formTodo()
    {
        $request = $this->requestStack->getCurrentRequest();

        // Create Todo Form
        $todoForm = $this->formFactory
                            ->createBuilder()
                            ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
                            ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
                            ->getForm();
        // Submit Todo Form
       $todoForm->handleRequest($request);
        if ($todoForm->isSubmitted() && $todoForm->isValid()) {
                $getTodoList = $todoForm->getData();
                $this->insert( $getTodoList );
                return ["submited"=> new RedirectResponse($this->router->generate('TodoListFetch'))];
        }

        return ["form"=> $todoForm] ;
    }

    /**
     * Retrieving shared TodoList Data
     * @param $todoList array
     * @return bool
     */
    public function getSharedData(){
        // Retrieving data
        $todoLists          = $this->getTodoLists();
        $todos              = $this->getTodos();
        $uncategorizedTodos = $this->getUncategorizedTodos();

        $sharedData = [ 
                        'todoLists'         => $todoLists,
                        'todos'             => $todos,
                        'uncategorizedTodos'=> $uncategorizedTodos
                     ] ;

        return (object)$sharedData ;
    }

    /**
     * Retrieve single TodoList
     * @param $id
     * @return array
     */
    public function single($id)
    {
        // Find record
        $todoList = $this->entityManager->getRepository(TodoList::class)->find($id);

        // Check if not exist
        if (!$todoList)
            return (object)['error'=>'not_found'];

        $todoList = [
                        'id'    =>  $todoList->getId(),
                        'name'  =>  $todoList->getName(),
                    ] ;

        return  $todoList ;
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


    /**
     * Update TodoList
     * 
     */
    public function update( $todoList )
    {
        $data = json_encode($todoList) ;
        $data = json_decode($data);

        // get data from request
        if(!$data)
            return ['error' => 'Data empty !'];

        // Find record
        $todoList = $this->entityManager->getRepository(TodoList::class)->find($todoList["id"]);

        // Check if exist
        if (!$todoList)
            return ['error'=> 'not_found'];

        // Sets
        $todoList->setName($data->name ?? NULL);
    
        // Save to database
        $this->entityManager->persist($todoList);
        $this->entityManager->flush();

        return true ;

    }

}
