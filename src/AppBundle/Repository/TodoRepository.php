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

}
