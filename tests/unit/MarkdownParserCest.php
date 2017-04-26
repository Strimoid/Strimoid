<?php

class MarkdownParserCest
{
    public function _before(UnitTester $I)
    {
        $this->parser = app(League\CommonMark\Converter::class);
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function tryToParseUserMention(UnitTester $I)
    {
        $html = $this->parser->convertToHtml('@julian85');
        $I->assertContains('<a href="/u/julian85">@julian85</a>', $html);
    }

    public function tryToParseGroupLink(UnitTester $I)
    {
        $html = $this->parser->convertToHtml('g/moore');
        $I->assertContains('<a href="/g/moore">g/moore</a>', $html);
    }

    public function tryToParseSpoiler(UnitTester $I)
    {
        
    }

    public function tryToParseImage(UnitTester $I)
    {
        
    }

    public function tryToParseYoutubeVideo(UnitTester $I)
    {
        
    }
}
