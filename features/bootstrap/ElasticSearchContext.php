<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Symfony\Component\Messenger\MessageBusInterface;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class ElasticSearchContext.
 *
 * Provide elastic search index management
 */
class ElasticSearchContext implements KernelAwareContext
{
    private $kernel;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @param string $index
     * @param string $type
     *
     * @Given I reset elastic search type :index :type
     */
    public function iResetElasticIndexType(string $index, string $type)
    {
        $arguments = [
            '--index' => $index,
            '--type' => $type,
        ];

        $this->executeIndexCommand('fos:elastica:reset', $arguments);
    }

    /**
     * @param string $index
     * @param string $type
     *
     * @Given I refresh elastic search type :index :type
     */
    public function iRefreshElasticIndexType(string $index, string $type)
    {
        $arguments = [
            '--index' => $index,
            '--type' => $type,
        ];

        $this->executeIndexCommand('fos:elastica:populate', $arguments);
    }

    private function executeIndexCommand(string $command, array $arguments)
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(
            array_merge(
                ['command' => $command],
                $arguments
            ));
        $output = new BufferedOutput();

        $application->run($input, $output);

        echo $output->fetch();

    }
}
