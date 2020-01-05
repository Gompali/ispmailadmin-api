<?php

declare(strict_types=1);


namespace App\Infra\Services\Config;


use http\Exception\InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\Yaml\Yaml;

class YamlFileContentLoader implements YamlFileContentLoaderInterface
{
    /** @var FileLocator */
    private $fileLocator;

    public function __construct(FileLocator $fileLocator)
    {
        $this->fileLocator = $fileLocator;
    }

    public function load(string $fileName, string $dirName, string $type = null)
    {
        $fileElements = explode('.', $fileName);
        if(!in_array(end($fileElements), $yamlExt = ['yaml', 'yml'])){
            if(!in_array($type, $yamlExt)){
                throw new InvalidArgumentException('Can not load file because extension is not valid');
            }
        }

        $filePath = $this->fileLocator->locate($fileName, $dirName);
        $values = Yaml::parse(file_get_contents($filePath));
        $array_values = array_shift($values);
        sort($array_values);
        return $array_values;
    }

    public function supports($resource, string $type = null)
    {
        return is_string($resource) && 'yaml' === pathinfo($resource, PATHINFO_EXTENSION);
    }

}