<?php


namespace App\Infra\Services\Config;


interface YamlFileContentLoaderInterface
{
    public function load(string $dirName, string $fileName,  string $type = null);
}