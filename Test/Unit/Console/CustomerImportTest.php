<?php

namespace Swapnil\CustomCustomerImport\Test\Unit\Model;

use Magento\Framework\Filesystem\Directory\ReadInterface;

class CustomerImportTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Swapnil\CustomCustomerImport\Console\CustomerImport
     */
    protected $modelClass;

    /**
     * @var array
     */
    protected $expectedData;

    public function setUp(): void
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->modelClass = $this->objectManager->getObject('Swapnil\CustomCustomerImport\Console\CustomerImport');
        $this->expectedData = [['fname' => 'Bob', 'lname' => 'Smith', 'emailaddress' => 'bob.smith@example.com']];
    }

    public function testGetMessage()
    {

        $this->assertEquals(
            $this->expectedData,
            $this->modelClass->parseCsv(
                'vendor/swapnil/custom-customer-import' . DIRECTORY_SEPARATOR . 'Files' . DIRECTORY_SEPARATOR . 'Test' . DIRECTORY_SEPARATOR . 'sampletest.csv',
                $this->objectManager->getObject('Magento\Framework\File\Csv')
            )
        );
    }

}