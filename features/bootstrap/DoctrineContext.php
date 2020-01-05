<?php

use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Class DoctrineContext.
 */
class DoctrineContext implements Context
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    /**
     * DoctrineContext constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @BeforeScenario
     *
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function beforeScenario()
    {
        $this->clearData();
        $this->buildSchema();
    }

    /**
     * @AfterScenario
     */
    public function afterScenario()
    {
        $this->clearData();
        $this->entityManager->clear();
    }

    /**
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    protected function buildSchema()
    {
        $metadata = $this->getMetadata();

        if (!empty($metadata)) {
            $tool = new SchemaTool($this->entityManager);
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
    }

    public function clearData()
    {
//        $purger = new ORMPurger($this->entityManager);
//        $purger->purge();
    }

    /**
     * @return array
     */
    protected function getMetadata()
    {
        return $this->entityManager->getMetadataFactory()->getAllMetadata();
    }
}
