<?php


use Cat\Lang\Loaders\FileSystemLoader;
use Cat\Lang\Translator;

class TranslatorTest extends \PHPUnit\Framework\TestCase
{
    public $path = FileSystemLoader::class;
    public $defaultLanguage = 'pt';

    public function testTranslateFunctionWorksFine()
    {
        $translator = new Translator($this->path, $this->defaultLanguage);

        $expected = $translator->translate('ola');
        $this->assertEquals('olá', $expected);
    }

    public function testTranslateFunctionWorksFineInAnotherLanguage()
    {
        $translator = new Translator($this->path, 'fr');

        $expected = $translator->translate('ola');
        $this->assertEquals('Salut', $expected);
    }

    public function testTranslateFunctionWorksFineInTwoLevels()
    {
        $translator = new Translator($this->path, $this->defaultLanguage);

        $expected = $translator->translate('level.two');
        $this->assertEquals('Nível 2Ω', $expected);
    }


}