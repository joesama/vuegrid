<?php

namespace Joesama\VueGrid\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * VueDatagrid Data Handler Traits.
 *
 * @author joharijumali@gmail.com
 **/
trait DataModeller
{
    /**
     * Build Paginator For Data.
     *
     * @param void
     *
     * @author
     **/
    public function buildPaginators(LengthAwarePaginator $paginator)
    {
        return $paginator;
    }
} // END class Elequent
