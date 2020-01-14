<?php

namespace App\Tests\Sample\Infrastructure\Formatter;

use PHPUnit\Framework\TestCase;
use App\Sample\Infrastructure\Formatter\UpperFormatter;

/**
 * Class UpperFormatterTest's
 *
 * @package App\Tests\Sample\Infrastructure\Formatter
 */
class UpperFormatterTest extends TestCase
{
    /**
     * A dot case's.
     */
    const DOT_CASE = 'Hi there! Happy Halloween.';

    /**
     * A whitespace case's.
     */
    const WHITESPACE_CASE = 'Hi there! Happy Halloween ';

    /**
     * A whitespace and a dot case's.
     */
    const WHITESPACE_AND_DOT_CASE = 'Hi there! Happy Halloween. ';

    /**
     * A whitespace and nothing in a quote case's.
     */
    const WHITESPACE_AND_NOTHING_IN_QUOTE_CASE = 'He asked ’Happy Halloween?’ ';

    /**
     * A whitespace and nothing in a quote case's.
     */
    const WHITESPACE_AND_NOTHING_IN_QUOTE_CASE_2 = 'He asked ’Happy Halloween...’ ';

    /**
     * Nothing in a quote case's.
     */
    const NOTHING_IN_QUOTE_CASE = 'He asked ’Happy Halloween?’';

    /**
     * Nothing at the very end case's.
     */
    const NOTHING_AT_THE_END_CASE = 'Hi there! Happy Halloween';

    /**
     * Question mark at the very end case's.
     * In cases when we change from a question tone to a strong statement point.
     */
    const QUESTION_MARK_AT_THE_END_CASE = 'Hi there! Happy Halloween?';

    /**
     * 3 dots are at the very end case's.
     */
    const THREE_DOTS_AT_THE_END_CASE = 'Hi there! Happy Halloween...';

    /**
     * An exclamation mark is at the very end case's.
     */
    const EXCLAMATION_MARK_AT_THE_END_CASE = 'Hi there! Happy Halloween!';

    /**
     * A test result 1 must be.
     */
    const TEST_RESULT = 'HI THERE! HAPPY HALLOWEEN!';

    /**
     * A quote test result must be.
     */
    const TEST_WITH_QUOTE_RESULT = 'HE ASKED ’HAPPY HALLOWEEN?’!';

    /**
     * A quote test 2 result must be.
     */
    const TEST_WITH_QUOTE_RESULT_2 = 'HE ASKED ’HAPPY HALLOWEEN...’!';

    /**
     * A test for a dot case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInDotCase()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::DOT_CASE);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_RESULT, $result);
    }

    /**
     * A test for a whitespace case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInWhitespaceCase()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::WHITESPACE_CASE);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_RESULT, $result);
    }

    /**
     * A test for a whitespace and dot case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInWhitespaceAndDotCase()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::WHITESPACE_AND_DOT_CASE);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_RESULT, $result);
    }

    /**
     * A test for a whitespace and nothing at the very end case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInWhitespaceAndNothingAtTheEndCase()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::NOTHING_AT_THE_END_CASE);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_RESULT, $result);
    }

    /**
     * A test for a whitespace and nothing in quote case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInWhitespaceAndNothingInQuoteCase()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::WHITESPACE_AND_NOTHING_IN_QUOTE_CASE);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_WITH_QUOTE_RESULT, $result);
    }

    /**
     * A test for a whitespace and nothing in quote 2 case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInWhitespaceAndNothingInQuote2Case()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::WHITESPACE_AND_NOTHING_IN_QUOTE_CASE_2);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_WITH_QUOTE_RESULT_2, $result);
    }

    /**
     * A test for a three dots case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInTreeDotsCase()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::THREE_DOTS_AT_THE_END_CASE);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_RESULT, $result);
    }

    /**
     * A test for a question case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInQuestionCase()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::QUESTION_MARK_AT_THE_END_CASE);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_RESULT, $result);
    }

    /**
     * A test for an exclamation mark case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInExclamationCase()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::EXCLAMATION_MARK_AT_THE_END_CASE);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_RESULT, $result);
    }

    /**
     * A test for nothing in quote case's.
     */
    public function testChangesOfUpperCaseAndExclamationMarkInNothingQuoteCase()
    {
        $upperFormatter = new UpperFormatter();
        $result = $upperFormatter->applyUpperCaseAndExclamationMark(self::NOTHING_IN_QUOTE_CASE);

        // assert that your formatter applied all necessary changes!
        $this->assertEquals(self::TEST_WITH_QUOTE_RESULT, $result);
    }
}