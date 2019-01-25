<?php

namespace AppBundle\Repository;

/**
 * TodoRepository
 *
 */
class TodoRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Get all Todo Lists
     *
     * @return array
     */
    public function fetch()
    {
        
        $todos  =  $this->createQueryBuilder('todo')
                        ->select('todo')
                        ->leftJoin(
                                    'AppBundle:TodoList', 
                                    'todoList', 'WITH', 
                                    'todo.fk_todolist = todoList.id'
                                 )
                        ->getQuery()
                        ->getResult() ;
        return $todos ;
    }


}
