<?php
namespace Tests\Unit;

use NamespaceProtector\Analyser;
use NamespaceProtector\Common\FileSystemPath;
use NamespaceProtector\Parser\ParserInterface;
use NamespaceProtector\Result\Result;
use NamespaceProtector\Result\ResultCollector;

class AnalyserTest extends AbstractUnitTestCase {

    /** @test */
    public function it_create_work(): void
    {
        $file = $this->getFileToParse();
        $parser = $this->prophesize(ParserInterface::class);
        $parser->parseFile($file)
                ->shouldBeCalled(); 

        $resultCollector = $this->resultCollectorWithError();

        $parser->getListResult()
                ->shouldBeCalled()
                ->willReturn(
                    $resultCollector
                );

        $parser = $parser->reveal();

        $analyser = new Analyser($parser);
        $analyser->execute($file);
    }

    /** @test */
    public function it_parse_file_with_one_error(): void
    {
        $file = $this->getFileToParse();
        $parser = $this->prophesize(ParserInterface::class);
        $parser->parseFile($file)
                ->shouldBeCalled(); 

        $resultCollector = $this->resultCollectorWithError();
        $parser->getListResult()
                ->shouldBeCalled()
                ->willReturn(
                    $resultCollector
                );

        $parser = $parser->reveal();

        $analyser = new Analyser($parser);
        $analyser->execute($file);

        $this->assertTrue($analyser->withError());
        $this->assertEquals(1,$analyser->getCountErrors());

    }

    private function resultCollectorWithError()
    {
        $resultCollector = new ResultCollector();
        $resultCollector->addResult(
            new Result('Message',1)
        );
        

        return $resultCollector;
    }

    private function getFileToParse()
    {
        $fileSystem = $this->StartBuildFileSystem()
            ->addFile('ClassPsr4Composer.php', 'php', 'files')
            ->buildFileSystemUrl();
        
        $file = new FileSystemPath($fileSystem.'/files/ClassPsr4Composer.php'); 
        

        return $file;
    }

}


