<?php

use DNPage\TextAnalyzer\TextAnalyzer;
use DNPage\TextAnalyzer\WordLists;

class TextAnalyzerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DNPage\TextAnalyzer\TextAnalyzer
     */
    protected $ta;


    public function setup()
    {
        $this->ta = new TextAnalyzer(new WordLists);
    }

    public function testLoadTextViaLoadText()
    {
        $text = 'This is text that I am loading into text analyzer';
        $this->ta->loadText($text);
        $this->assertEquals(10, $this->ta->getAllWordCount());
    }

    public function testLoadTextViaConstructor()
    {
        $ta = new TextAnalyzer(new WordLists, 'This is text that I am loading into text analyzer');
        $this->assertEquals(10, $ta->getAllWordCount());
    }

    public function testReturnsSentences()
    {
        $text = 'This is text that I am loading into text analyzer. This is a second sentence.';
        $this->ta->loadText($text);
        $expected_sentences = [
            'this is text that i am loading into text analyzer.',
            'this is a second sentence.'
        ];
        $this->assertEquals($expected_sentences, $this->ta->getSentences());
    }


    public function testReturnsAvgSentenceLength()
    {
        $text = 'This is text that I am loading into text analyzer. This is a second sentence. This is a third one.';
        $this->ta->loadText($text);
        $this->assertEquals(6.7, $this->ta->getAverageSentenceLength());
    }

    public function testReturnsTotalWordCount()
    {
        $text = 'This is text that I am loading into text analyzer.';
        $this->ta->loadText($text);
        $this->assertEquals(10, $this->ta->getAllWordCount());
    }

    public function testReturnsUniqueWordCount()
    {
        $text = 'This is text that I am loading into text analyzer. This is a second sentence. This is a third one.';
        $this->ta->loadText($text);
        $this->assertEquals(14, $this->ta->getUniqueWordCount());
    }

    public function testReturnsAbridgedWordCount()
    {
        $text = 'This is text that I am loading into text analyzer. This is a second sentence. This is a third one.';
        $this->ta->loadText($text);
        $this->assertEquals(6, $this->ta->getAbridgedWordCount());
    }


    public function testReturnsTopAbridgedWordCount()
    {
        $text =
            'This is text that I am loading into text analyzer and counts as the first sentence. This is a second sentence. This is a third one. Here
            is a fourth sentence that is being loaded into the analyzer. I bet sentence and analyzer are the most
            popular words.';
        $this->ta->loadText($text);
        $top = $this->ta->getTopAbridgedWordFrequency(2);

        $this->assertEquals(['sentence'=> 4 , 'analyzer' => 3], $top);
    }

    public function testReturnsSODScale()
    {   $text =
            'This is text that I am loading into text analyzer and counts as the first sentence. This is a second sentence. This is a third one. Here
            is a fourth sentence that is being loaded into the analyzer. I bet sentence and analyzer are the most
            popular words. They can see the results later.';
        $this->ta->loadText($text);
        $expected_results = [
            'self' => 33,
            'others' => 17,
            'neutral' => 50
        ];

        $this->assertEquals($expected_results, $this->ta->getSODScale());
    }


    public function testReturnsSentenceCount()
    {
        $text = 'This is text that I am loading into text analyzer. This is a second sentence.';
        $this->ta->loadText($text);
        $this->assertEquals(2, $this->ta->getSentenceCount());
    }


    public function testReturnsSyllableCount()
    {
        $text = 'This is text that I am loading into text analyzer. This is a second sentence.';
        $this->ta->loadText($text);
        $this->assertEquals(22, $this->ta->getSyllableCount());
    }

    public function testReturnsReadabilityScoreOfTextWithLessThan100Words()
    {
        $text = 'This is text that I am loading into text analyzer and counts as the first sentence.';
        $this->ta->loadText($text);

        $this->assertEquals(0.0, $this->ta->getReadabilityScore());
    }

    public function testReturnsReadabilityScoreOfTextWithMoreThan100Words()
    {
        $text =
            'This is a text passage with more than one hundred words. It is being used in a readability test so that it
            can trigger logic in a class method to derive the Flesch Reading Ease Score. If the text has less than one
            hundred word, it simply returns the value zero. If, however, there are one hundred words or more, it will
            perform the calculation. Ths score is not the same as the Flesch Kincaid Grade Level but still can be used
            to provide a scale that can be used to determine how easy a text or how difficult a text passage is
            to read. There\'s a reason why this is sentence is being used in this text.';
        $this->ta->loadText($text);
        $this->assertGreaterThan(0.0, $this->ta->getReadabilityScore());
    }


    public function testReturnsGradeLevelOfTextWithLessThan100Words()
    {
        $text = 'This is text that I am loading into text analyzer and counts as the first sentence.';
        $this->ta->loadText($text);
        $this->assertEquals(0.0, $this->ta->getGradeLevel());
    }

    public function testReturnsGradeOfTextWithMoreThan100Words()
    {
        $text =
            'This is a text passage with more than one hundred words. It is being used in a readability test so that it
            can trigger logic in a class method to derive the Flesch Kincaid Grade Level Score. If the text has less
            than one hundred words, it simply returns the value zero. If, however, there are one hundred words or more,
            it will perform the calculation. Ths score is not the same as the Flesch Reading Ease Score but still can
            be used to provide what grade level the text passage would have no problem comprehending. It is a relative
            indicator as it presumes a level of education required to understand the text passage.  There\'s a reason
            why this is sentence is being used in this text.';
        $this->ta->loadText($text);
        $this->assertGreaterThan(0.0, $this->ta->getGradeLevel());
    }

    public function testGetAllWordsReturnsArray()
    {
        $text = 'This is text that I am loading into word lib. I am expecting that this will all work.';
        $this->ta->loadText($text);
        $word_array = $this->ta->getAllWords();
        $this->assertInternalType('array', $word_array);
        $this->assertEquals(18, count($word_array));
    }

    public function testGetUniqueWordsReturnsArray()
    {
        $text = 'This is text that I am loading into word lib. I am expecting that this will all work.';
        $this->ta->loadText($text);
        $word_array = $this->ta->getUniqueWordFrequency();
        $this->assertInternalType('array', $word_array);
        $this->assertEquals(14, count($word_array));
    }

    public function testUniqueWordCountArray()
    {
        $text = 'This is text that I am loading into word lib. I am expecting that this will all work.';
        $this->ta->loadText($text);
        $word_array = $this->ta->getUniqueWordFrequency();
        $this->assertEquals(1, $word_array['text']);
        $this->assertEquals(2, $word_array['that']);
    }

    public function testGetAbridgedWordsReturnsArray()
    {
        $text = 'This is text that I am loading into word lib. I am expecting that this will all work.';
        $this->ta->loadText($text);
        $word_array = $this->ta->getAbridgedWordFrequency();
        $this->assertInternalType('array', $word_array);
        $this->assertEquals(6, count($word_array));
    }

    public function testCountOfWordsInAbridgedWordsArray()
    {
        $text = 'This is text that I am loading into word lib. I am expecting that this will all work. Loading is fast.';
        $this->ta->loadText($text);
        $word_array = $this->ta->getAbridgedWordFrequency();
        $this->assertEquals(1, $word_array['text']);
        $this->assertEquals(2, $word_array['loading']);
    }

    public function testStopWordsReduction()
    {
        $text = 'I you me is are not reality';
        $this->ta->loadText($text);
        $word_array = $this->ta->getAbridgedWordFrequency();
        $this->assertInternalType('array', $word_array);
        $this->assertEquals(1, count($word_array));
    }

    public function testGetPronounCount()
    {
        $text = 'I you me is are not reality';
        $this->ta->loadText($text);
        $pronoun_count = $this->ta->getPronounCount();
        $this->assertEquals(3, $pronoun_count);
    }

    public function testGetPronounsReturnsArray()
    {
        $text = 'I you me is are not reality';
        $this->ta->loadText($text);
        $word_array = $this->ta->getPronounFrequency();
        $this->assertInternalType('array', $word_array);
        $this->assertEquals(3, count($word_array));
    }

    public function testCountOfWordsInPronounsArray()
    {
        $text = 'I you me is are not reality, You and They';
        $this->ta->loadText($text);
        $word_array = $this->ta->getPronounFrequency();
        $this->assertEquals(1, $word_array['me']);
        $this->assertEquals(2, $word_array['you']);
    }
}
