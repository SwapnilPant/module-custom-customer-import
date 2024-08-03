<?php

namespace Swapnil\CustomCustomerImport\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Input\InputArgument;
use Swapnil\CustomCustomerImport\Model\Process;
use Swapnil\CustomCustomerImport\Api\ReaderInterface;
use Magento\Framework\Module\Dir;

/**
 * Command for import custom from profile base file params
 */
class CustomerImport extends Command
{
    /** Command name */
    const NAME = 'customer:import';

    /** Profile param name */
    const PROFILE_NAME = 'profile-name';

    /** File name param name */
    const FILE_NAME = 'file-name';

    /**
     * @var Process
     */
    private $process;

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var Dir
     */
    private $moduleDir;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $driverFile;

    public function __construct(
        Process $process,
        ReaderInterface $reader,
        Dir $moduleDir,
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        ?string $name = null
    ) {
        parent::__construct($name);
        $this->process = $process;
        $this->reader = $reader;
        $this->moduleDir = $moduleDir;
        $this->driverFile = $driverFile;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {

        $this->setName(self::NAME)
            ->setDescription('Multi format and file based customer import')
            ->addArgument(self::PROFILE_NAME, InputArgument::REQUIRED, __('Profile Name'))
            ->addArgument(self::FILE_NAME, InputArgument::REQUIRED, __('File Name'));
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $inputProfile = strtolower($input->getArgument(self::PROFILE_NAME));
            $inputFile = $input->getArgument(self::FILE_NAME);
            $readerList = $this->reader->getReaders();
            $file = $this->moduleDir->getDir('Swapnil_CustomCustomerImport')
                . DIRECTORY_SEPARATOR . 'Files' . DIRECTORY_SEPARATOR . $inputFile;

            $customerData = $this->getCustomerData($inputProfile, $file, $readerList);
            $this->processCustomers($customerData);
            $output->writeln("<info>Customer imported successfully</info>");
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Cli::RETURN_FAILURE;
        }
        return Cli::RETURN_SUCCESS;
    }

    /**
     * get customer data
     */
    public function getCustomerData($inputProfile, $file, $readerList)
    {
        $reader = $readerList[$inputProfile];
        switch ($inputProfile) {
            case 'sample-csv':
                return $this->parseCsv($file, $reader);
            case 'sample-json':
                return $this->parseJson($file, $reader);
            default:
                throw new \Exception('Not valid profile added');
        }
    }

    /**
     * parse data from csv
     */
    public function parseCsv($filePath, $reader)
    {
        $data = [];
        $rowDatas = $reader->getData($filePath);
        $firstRow = true;
        foreach ($rowDatas as $rowData) {
            if ($firstRow) {
                foreach ($rowData as $key => $value) {
                    $columnMapper[$key] = $value;
                }
                $firstRow = false;
            } else {
                $rowArr = [];
                foreach ($rowData as $key => $value) {
                    $rowArr[$columnMapper[$key]] = $value;
                }
                $data[] = $rowArr;
            }
        }
        return $data;
    }

    /**
     * parse data from json
     */
    public function parseJson($filePath, $reader)
    {
        return $reader->unserialize($this->driverFile->fileGetContents($filePath));
    }

    /**
     * process customer data to DB
     */
    protected function processCustomers($data)
    {
        foreach ($data as $customer) {
            $this->process->saveCustomer($customer);
        }
    }
}