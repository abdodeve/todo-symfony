<?php

namespace AppBundle\Repository;

/**
 * TodoRepository
 *
 */
class TodoRepository extends \Doctrine\ORM\EntityRepository
{

    public function fetch2()
    {
        return 'works' ;
    }

    /**
     * Get uncategorized todos
     * 
     */
    public function uncategorizedTodos()
    {
        $uncategorized = $this->createQueryBuilder('todo')
                            ->select("count(todo.id) as nbUncategorized")
                            ->where('todo.fk_todolist IS NULL')
                            ->getQuery()
                            ->getResult() ;
        return $uncategorized ;                    
    }

}
