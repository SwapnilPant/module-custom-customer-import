<?php

namespace Swapnil\CustomCustomerImport\Api;

interface ReaderInterface
{
    /**
     * Create Class Object of $className 
     * @param mixed $className
     */
    public function create($className);

    /**
     * Get reader list 
     */
    public function getReaders();
}