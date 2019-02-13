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

class TodoListService
{
    /**
     * Get TodoLists
     * @return todoLists array
     */
    public static function getTodoLists()
    {
        $todoArr = [] ;
        $todoRepository = $this->getDoctrine()
                               ->getManager()
                               ->getRepository('AppBundle:TodoList');
        return $todoRepository->fetch();
    }


    /**
     * Get Todos
     * @return todos array
     */
    public static function getTodos()
    {
        // Get Todos
        $todos = $this->getDoctrine()
                        ->getRepository('AppBundle:Todo')
                        ->findAll();
        return $todos ;
    }

    /**
     * Get UncategorizedTodos
     * @return uncategorizedTodos array
     */
    public static function getUncategorizedTodos()
    {
        // Get Uncategorized todos
        $todoRepository = $this->getDoctrine()
                                ->getManager()
                                ->getRepository('AppBundle:Todo');
        return $todoRepository->uncategorizedTodos();   
    }


    /**
     * Create & Submit TodoList form
     * @return mixed
     */
    public static function formTodoList()
    {
        // Create TodoList Form
        $todoListForm = $this->createFormBuilder()
                            ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
                            ->getForm();

        // Submit TodoList Form
        $todoListForm->handleRequest($request);

        if ($todoListForm->isSubmitted() && $todoListForm->isValid()) {
            $getTodoList = $todoListForm->getData();
            $res = $this->insert( $getTodoList );
            return $this->redirectToRoute('TodoListFetch');
        }

        return $todoListForm ;
    }


    /**
     * Create & Submit Todo form
     * @return mixed
     */
    public static function formTodo()
    {
        // Create Todo Form
        $todoForm = $this->createFormBuilder()
                            ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
                            ->add('name', null, ['attr' => ['class' => 'form-control', 'placeholder'=> 'Entrer list']])
                            ->getForm();
        // Submit Todo Form
        $todoForm->handleRequest($request);
        if ($todoForm->isSubmitted() && $todoForm->isValid()) {
                $getTodoList = $todoForm->getData();
                $res = $this->insert( $getTodoList );
                return $this->redirectToRoute('TodoListFetch');
        }

        return $todoForm ;
    }

}
