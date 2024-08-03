<?php

namespace Swapnil\CustomCustomerImport\Model;

class Process
{
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @param \Magento\Customer\Model\CustomerFactory    $customerFactory
     */
    public function __construct(
        \Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->customerFactory = $customerFactory;
    }

    /**
     * Save customer
     * @param array $customerData
     */
    public function saveCustomer($customerData)
    {
        try {
            //Website Admin ID
            $websiteId = 0;
            $customer = $this->customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->setEmail($customerData['emailaddress']);
            $customer->setFirstname($customerData['fname']);
            $customer->setLastname($customerData['lname']);
            $customer->save();
        } catch (\Exception $e) {
            throw new \Exception('Customer not saved');
        }
        return true;
    }
}