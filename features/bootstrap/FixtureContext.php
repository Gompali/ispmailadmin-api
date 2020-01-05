<?php

use App\Domain\Builder\AdminUserBuilderInterface;
use App\Infra\Services\Config\YamlFileContentLoaderInterface;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Fidry\AliceDataFixtures\LoaderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FixtureContext.
 */
class FixtureContext implements Context
{
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    /** @var \Fidry\AliceDataFixtures\LoaderInterface */
    private $loader;

    /** @var \Behat\Gherkin\Loader\YamlFileLoader */
    private $yamlFileLoader;

    /** @var \App\Domain\Builder\AdminUserBuilderInterface */
    private $adminUserBuilder;

    /**
     * DoctrineContext constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Fidry\AliceDataFixtures\LoaderInterface $loader
     * @param YamlFileContentLoaderInterface $yamlFileLoader
     * @param AdminUserBuilderInterface $adminUserBuilder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoaderInterface $loader,
        YamlFileContentLoaderInterface $yamlFileLoader,
        AdminUserBuilderInterface $adminUserBuilder
    ) {
        $this->entityManager = $entityManager;
        $this->loader = $loader;
        $this->yamlFileLoader = $yamlFileLoader;
        $this->adminUserBuilder = $adminUserBuilder;
    }

    /**
     * @Given I use fixture file :filename
     *
     * @param string $filename
     */
    public function iUserFixtureFile(string $filename)
    {
        $this->loader->load([__DIR__ . '/../fixtures/' .$filename]);

        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /**
     * @Given I use fixture files:
     *
     * @param \Behat\Gherkin\Node\TableNode $fileNames
     */
    public function iUserFixtureFiles(TableNode $fileNames)
    {
        $files = array_map(
            function ($item) {
                return __DIR__ . '/../fixtures/' .$item[0];;
            },
            $fileNames->getRows()
        );
        $this->loader->load($files);
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /**
     * @Given The user fixture file :arg1 is loaded
     */
    public function theUserFixtureFileIsLoaded($arg1)
    {
        $arrayData = $this->yamlFileLoader->load($arg1, __DIR__ . '/../fixtures');

        foreach ($arrayData as $userFixture) {
            $user = $this->adminUserBuilder->createFromCredentials(
                $userFixture['id'],
                $userFixture['username'],
                $userFixture['password']
            );
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }
}
