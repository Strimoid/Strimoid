<?php

namespace Strimoid\Markdown\Inline\Parser;

use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;
use Strimoid\Models\User;

class UserMentionParser implements InlineParserInterface
{
    public function getCharacters(): array
    {
        return ['@'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $previousChar = $cursor->peek(-1);

        if ($previousChar !== null && $previousChar !== ' ') {
            return false;
        }

        $previousState = $cursor->saveState();
        $cursor->advance();

        $handle = $cursor->match('/^[A-Za-z0-9_]{1,32}(?!\w)/');

        if (empty($handle)) {
            $cursor->restoreState($previousState);
            return false;
        }

        $user = User::name($handle)->first();
        if (!$user) {
            $cursor->restoreState($previousState);
            return false;
        }

        $profileUrl = route('user_profile', $user, false);
        $inlineContext->getContainer()->appendChild(new Link($profileUrl, '@' . $handle));

        return true;
    }
}
