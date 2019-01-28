<?php

namespace AppBundle\Repository;

/**
 * listRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TodoListRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Get all Todo Lists
     * & Mark empty 
     *
     * @return array
     */
    public function fetch()
    {
        
        $todos  =  $this->createQueryBuilder('todoList')
                        ->select("todoList.id, todoList.name, count(todo.fk_todolist) as nbTodo")
                        ->leftJoin(
                                    'AppBundle:Todo',
                                    'todo', 'WITH',
                                    'todo.fk_todolist = todoList.id'
                                 )
                       ->groupBy('todoList.id, todoList.name')
                        ->getQuery()
                        ->getResult() ;
        return $todos ;
    }


    /**
     * Get all Todo Lists
     * & Mark empty 
     * Other methode (Just Test Purpose)
     *
     * @return array
     */
    public function fetch2()
    {
        $todos  =  $this->getEntityManager()
        ->createQuery(
                       'SELECT 
                                tdl
                        FROM 
                                AppBundle:TodoList tdl'     
                    )
        ->getResult();
        return $todos ;
    }
}