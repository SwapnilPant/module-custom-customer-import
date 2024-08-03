<?php
namespace Swapnil\CustomCustomerImport\Model;

/**
 * Class ReaderPool
 */
class ReaderPool
{
    /**
     * @var array $readers
     */
    public $readers = [];

    /**
     * Constructor
     * 
     * @param array $readers
     */
    public function __construct(array $readers = [])
    {
        $this->readers = $readers;
    }
}
