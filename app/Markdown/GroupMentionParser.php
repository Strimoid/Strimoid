<?php

namespace Strimoid\Markdown;

use League\CommonMark\Inline\Element\Link;
use League\CommonMark\Inline\Parser\AbstractInlineParser;
use League\CommonMark\InlineParserContext;
use Strimoid\Models\Group;

class GroupMentionParser extends AbstractInlineParser
{
    public function getCharacters()
    {
        return ['g'];
    }

    public function parse(InlineParserContext $inlineContext)
    {
        $cursor = $inlineContext->getCursor();

        // The 'g/' prefix must not have any other characters immediately prior
        $previousChar = $cursor->peek(-1);
        $nextChar = $cursor->peek(1);

        if ($previousChar !== null && $previousChar !== ' ') {
            // peek() doesn't modify the cursor, so no need to restore state first
            return false;
        }

        if ($nextChar !== '/') {
            return false;
        }

        // Save the cursor state in case we need to rewind and bail
        $previousState = $cursor->saveState();

        // Advance past the 'g/' prefix to keep parsing simpler
        $cursor->advanceBy(2);

        // Parse the handle
        $handle = $cursor->match('/^[A-Za-z0-9_]{1,32}(?!\w)/');
        if (empty($handle)) {
            // Regex failed to match; this isn't a valid username
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
