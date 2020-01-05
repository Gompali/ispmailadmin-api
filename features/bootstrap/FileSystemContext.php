<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class FileSystemContext.
 */
class FileSystemContext implements Context
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * FileSystemContext constructor.
     *
     * @param Filesystem $filesystem
     * @param Finder     $finder
     */
    public function __construct(Filesystem $filesystem, Finder $finder)
    {
        $this->filesystem = $filesystem;
        $this->finder = $finder;
    }

    /**
     * @Given the directory :arg1 exists
     */
    public function theDirectoryExists($arg1)
    {
        if (!is_dir($arg1)) {
            try {
                if (!mkdir($arg1, 0777, true) && !is_dir($arg1)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $arg1));
                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
    }

    /**
     * @Then the directory should contain :arg1 file:
     */
    public function theDirectoryShouldContainFile($arg1, TableNode $table)
    {
        $parameters = $table->getRowsHash(0);
        $directory = $parameters['directory'] ?? '';
        $iterator = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);
        if (iterator_count($iterator) !== $arg1) {
            throw new \Exception('Directory contains '.iterator_count($iterator).' files and not '.$arg1.' files');
        }

        return true;
    }

    /**
     * @Given the directory :arg1 is writable
     */
    public function theDirectoryIsWritable($arg1)
    {
        if (!is_writable($arg1)) {
            throw new Exception('Directory '.$arg1.' is not writable');
        }
    }

    /**
     * @Given I empty the directory :arg1
     *
     * @throws \Exception
     */
    public function iEmptyTheDirectory($arg1)
    {
        if (!is_dir($arg1)) {
            throw new \Exception($arg1.' is not a directory');
        }
        $items = $this->finder->exclude($arg1)->in($arg1)->getIterator();
        $this->filesystem->remove($items);
    }

    /**
     * @Given I have a placeholder file :arg1
     */
    public function iHaveAPlaceholderFile($arg1)
    {
        if (!file_exists(__DIR__.'/../fixtures/files/'.$arg1)) {
            $placeholder = file_get_contents(__DIR__.'/../fixtures/files/placeholder.jpg');
            file_put_contents(__DIR__.'/../fixtures/files/testfile.jpg', $placeholder);
        }

        return '';
    }

    /**
     * @param $directory
     */
    private function listDirectory($directory)
    {
        $results = [];
        $finder = $this->finder->files()->in($directory);

        foreach ($finder as $file) {
            if (!in_array($file->getRealPath(), $results, true)) {
                $results[] = $file->getRealPath();
            }
        }

        foreach ($results as $result) {
            echo $result.'<br />';
        }
    }

    /**
     * @Given I empty system tmp directory
     */
    public function iEmptySystemTmpDirectory()
    {
        $this->iEmptyTheDirectory(sys_get_temp_dir());
    }

    /**
     * @Then the system tmp directory should contain :arg1 file
     */
    public function theSystemTmpDirectoryShouldContainFile()
    {
        $directory = sys_get_temp_dir();
        $iterator = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);
        if (iterator_count($iterator) !== $directory) {
            throw new \Exception('Directory contains '.iterator_count($iterator).' files and not '.$directory.' files');
        }

        return true;
    }

    /**
     * @Then the system tmp directory should contain a file with name :arg1
     */
    public function theSystemTmpDirectoryShouldContainAFileWithName($arg1)
    {
        $fileNameArray = explode('-', $arg1);
        $dateFormat = array_shift($fileNameArray);
        $fileName = (new \DateTime('now'))->format($dateFormat).'-'.implode('-', $fileNameArray);
        $directory = sys_get_temp_dir();
        if (!file_exists($directory.DIRECTORY_SEPARATOR.$fileName)) {
            throw new Exception('file could not be exported correctly');
        }
    }
}
