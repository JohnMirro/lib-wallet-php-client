<?php

use PHPUnit\Framework\TestCase;

class Php84CompatibilityTest extends TestCase
{
    public function testSourceDoesNotUseImplicitlyNullableParameterTypes()
    {
        $sourceFiles = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(dirname(__DIR__, 2) . '/src')
        );
        $pattern = '/^\\s*(?!\\?)[A-Za-z_\\\\][A-Za-z0-9_\\\\]*\\s+\\$[A-Za-z_][A-Za-z0-9_]*\\s*=\\s*null/m';

        foreach ($sourceFiles as $sourceFile) {
            if (!$sourceFile->isFile() || $sourceFile->getExtension() !== 'php') {
                continue;
            }

            $source = file_get_contents($sourceFile->getPathname());
            $this->assertSame(
                0,
                preg_match($pattern, $source),
                'Implicitly nullable parameter found in ' . $sourceFile->getPathname()
            );
        }
    }
}
