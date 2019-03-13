<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query ;
use AppBundle\Repository\TodoRepository ;
use AppBundle\Entity\Todo ;

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
        // Get todolists
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
     * Delete TodoList & related todos
     *
     * @return bool
     */
    public function delete( $ids )
    {

        // Delete todoLists
        $isTodoListDeleted = $this->createQueryBuilder('todoList')
                                    ->delete()
                                    ->where('todoList.id in (:ids)')
                                    ->setParameter(':ids', $ids)
                                    ->getQuery()
                                    ->execute();

        // Delete todos related with todolists
        // $isTodosDeleted = $this->getEntityManager()
        //             ->getRepository(Todo::class) 
        //             ->createQueryBuilder('todo') 
        //             ->delete()
        //             ->where('todo.fk_todolist in (:fk_todolists)')
        //             ->setParameter(':fk_todolists', [$ids])
        //             ->getQuery()
        //             ->execute();
        return $isTodoListDeleted == 1 ? true : false ;
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
