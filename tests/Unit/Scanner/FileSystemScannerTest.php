<?php
namespace Tests\Unit\Scanner;

use Tests\Unit\AbstractUnitTestCase;
use NamespaceProtector\Common\FileSystemPath;
use NamespaceProtector\Scanner\FileSystemScanner;

class FileSystemScannerTest extends AbstractUnitTestCase {

    /** @test */
    public function it_create_work(): void
    {
        $path = $this->getPathToScan();

        $file = new FileSystemScanner([$path],'php');
        $file->load();

        $this->assertCount(1,$file->getFileLoaded());
        $this->assertStringContainsString('root/files/ClassPsr4Composer.php',$file->getFileLoaded()[0]);
    }

    private function getPathToScan()
    {
        $fileSystem = $this->StartBuildFileSystem()
            ->addFile('ClassPsr4Composer.php', 'php', 'files')
            ->addFile('composer.json', 'json', 'files')
            ->addFile('namespace-protector-config.json', 'json', 'files')
            ->buildFileSystemUrl();
        
        $file = new FileSystemPath($fileSystem.'/files'); 
        

        return $file;
    }

}
