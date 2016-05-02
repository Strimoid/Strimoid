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
        $html = $this->parser->convertToHtml('@tobin74');
        $I->assertContains('<a href="/u/tobin74">@tobin74</a>', $html);
    }

    public function tryToParseGroupLink(UnitTester $I)
    {
        $html = $this->parser->convertToHtml('g/kub');
        $I->assertContains('<a href="/g/kub">g/kub</a>', $html);
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
