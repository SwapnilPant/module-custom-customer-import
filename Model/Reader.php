<?php

namespace Swapnil\CustomCustomerImport\Model;

use Magento\Framework\ObjectManagerInterface;

class Reader implements \Swapnil\CustomCustomerImport\Api\ReaderInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var ObjectManagerInterface
     */
    public $readerList;

    /**
     * @var \Swapnil\CustomCustomerImport\Model\ReaderPool
     */
    public $readerPool;

    /**
     * Constructor
     *
     * @param ObjectManagerInterface $objectManager
     * @param \Swapnil\CustomCustomerImport\Model\ReaderPool $readerPool
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        \Swapnil\CustomCustomerImport\Model\ReaderPool $readerPool
    ) {
        $this->objectManager = $objectManager;
        $this->readerPool = $readerPool;
    }

    /**
     * {@inheritdoc}
     */
    public function create($className)
    {
        return $this->objectManager->create(
            $className
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getReaders()
    {
        if (empty($this->readerList)) {
            foreach ($this->readerPool->readers as $key => $value) {
                $this->readerList[$key] = $this->create($value);
            }
        }
        return $this->readerList;
    }
}