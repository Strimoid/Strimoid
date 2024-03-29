<?php

namespace Strimoid\Markdown\Inline\Parser;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;
use Strimoid\Models\Group;

class GroupMentionParser implements InlineParserInterface
{
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::string('g/');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $previousChar = $cursor->peek(-1);
        $nextChar = $cursor->peek(1);

        if ($previousChar !== null && $previousChar !== ' ' && $nextChar !== '/') {
            // peek() doesn't modify the cursor, so no need to restore state first
            return false;
        }

        $previousState = $cursor->saveState();
        $cursor->advanceBy(2);

        $handle = $cursor->match('/^[A-Za-z0-9_]{1,32}(?!\w)/');

        if (empty($handle)) {
            $cursor->restoreState($previousState);
            return false;
        }

        $group = Group::name($handle)->first();

        if (!$group) {
            $cursor->restoreState($previousState);

            return false;
        }

        $groupUrl = route('group_contents', $group, false);
        $inlineContext->getContainer()->appendChild(new Link($groupUrl, 'g/' . $handle));

        return true;
    }
}
