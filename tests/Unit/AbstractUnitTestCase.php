<?php

namespace Tests\Unit;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use org\bovigo\vfs\vfsStreamDirectory;

abstract class AbstractUnitTestCase extends TestCase
{
    use ProphecyTrait;

    protected function getVirtualFileSystem()
    {
        $directory = [
      'json' => [
        'composer.json' => \file_get_contents(__DIR__ . '/../Stub/json/composer.json'),
        'namespace-protector-config.json' => \file_get_contents(__DIR__ . '/../Stub/json/namespace-protector-config.json'),
        'namespace-protector-config-mod-public.json' => \file_get_contents(__DIR__ . '/../Stub/json/namespace-protector-config-mod-public.json'),
      ],
      'files' => [
        'first.php' => \file_get_contents(__DIR__ . '/../Stub/php/first.php'),
        'no_violation.php' => \file_get_contents(__DIR__ . '/../Stub/php/no_violation.php'),
        'ClassPsr4Composer.php' => \file_get_contents(__DIR__ . '/../Stub/php/ClassPsr4Composer.php'),
        'FileThatUsePrivateNamespace.php' => \file_get_contents(__DIR__ . '/../Stub/php/FileThatUsePrivateNamespace.php'),
      ]
    ];

        return vfsStream::setup('root', 777, $directory);
    }

    //builder todo: move in specific class
    private $fileSystemtoBuild;
    protected function StartBuildFileSystem(): self
    {
        $this->fileSystemtoBuild = [];
        return $this;
    }

    protected function addFile(string $pathFile, string $directoryReal='', string $directoryVirtual): self
    {
        $this->fileSystemtoBuild[$directoryVirtual][$pathFile] = \file_get_contents(__DIR__ . '/../Stub/'.$directoryReal.'/'.$pathFile) ;

        return $this;
    }

    protected function addFileWithCallable(string $pathFile, string $directoryReal='', string $directoryVirtual, callable $callable): self
    {
        $this->fileSystemtoBuild[$directoryVirtual][$pathFile] = $callable($directoryReal, $pathFile) ;
        return $this;
    }

    protected function buildFileSystem()
    {
        return vfsStream::setup('root', 777, $this->fileSystemtoBuild);
    }

    protected function buildFileSystemUrl()
    {
        return vfsStream::setup('root', 777, $this->fileSystemtoBuild)->url();
    }

    //builder todo: move in specific class
}
