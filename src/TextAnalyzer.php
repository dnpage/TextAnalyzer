<?php

namespace  DNPage\TextAnalyzer;

class TextAnalyzer implements TextAnalyzerInterface
{
    protected $text;
    protected $sentences = [];
    protected $avg_sentence_length;
    protected $words;
    protected $unique_words;
    protected $abridged_words;
    protected $pronoun_words;
    protected $pronoun_count;
    protected $syllable_count;
    protected $sentence_count;
    protected $readability_score;
    protected $grade_level;
    protected $sod_scale;
    protected $stop_words;
    protected $pronouns;
    protected $self_directed_pronouns;
    protected $other_directed_pronouns;

    /**
     * TextAnalyzer constructor.
     * @param WordLists $word_lists
     * @param string $text
     */
    public function __construct(WordLists $word_lists, $text = '')
    {
        $this->stop_words              = $word_lists->stopWords();
        $this->pronouns                = $word_lists->pronouns();
        $this->self_directed_pronouns  = $word_lists->selfDirectedPronouns();
        $this->other_directed_pronouns = $word_lists->otherDirectedPronouns();
        if ($text != '') {
            $this->loadText($text);
        }
    }

    /**
     * @param $text
     */
    public function loadText($text)
    {
        $this->text = strtolower($text);
        $this->sentences = $this->deriveSentences();
        $this->avg_sentence_length = $this->deriveAverageSentenceLength();
        $this->words = str_word_count($this->text, 1);
        $this->unique_words = array_count_values($this->words);
        $this->pronoun_words = array_intersect_key($this->unique_words, $this->pronouns);
        $this->pronoun_count = array_sum($this->pronoun_words);
        $this->abridged_words = array_diff_key($this->unique_words, $this->stop_words);
        $this->syllable_count = $this->deriveTotalNumberOfSyllables();
        $this->sentence_count = count($this->sentences);
        $this->readability_score = $this->deriveFleschReadingEaseScore();
        $this->grade_level = $this->deriveFleschKincaidGradeLevel();
        $this->sod_scale = $this->deriveSODScale();
    }

    /**
     * @return array
     */
    public function getSentences()
    {
        return $this->sentences;
    }

    /**
     * @return float
     */
    public function getAverageSentenceLength()
    {
        return $this->avg_sentence_length;
    }
    /**
     * @return int
     */
    public function getAllWordCount()
    {
        return count($this->words);
    }

    /**
     * @return int
     */
    public function getUniqueWordCount()
    {
        return count($this->unique_words);
    }

    /**
     * @return int
     */
    public function getAbridgedWordCount()
    {
        return count($this->abridged_words);
    }

    /**
     * @return int
     */
    public function getPronounCount()
    {
        return count($this->pronoun_words);
    }

    /**
     * @return array
     */
    public function getAllWords()
    {
        return $this->words;
    }

    /**
     * @return array
     */
    public function getUniqueWordFrequency()
    {
        arsort($this->unique_words);
        return $this->unique_words;
    }

    /**
     * @return array
     */
    public function getAbridgedWordFrequency()
    {
        arsort($this->abridged_words);
        return $this->abridged_words;
    }

    /**
     * @param int $count
     * @return array
     */
    public function getTopAbridgedWordFrequency(int $count)
    {
        arsort($this->abridged_words);
        $top = array_slice($this->abridged_words, 0, $count);

        return $top;
    }

    /**
     * @return array
     */
    public function getPronounFrequency()
    {
        arsort($this->pronoun_words);
        return $this->pronoun_words;
    }

    /**
     * @return float
     */
    public function getSODScale()
    {
        return $this->sod_scale;
    }

    /**
     * @return int syllable count
     */
    public function getSyllableCount()
    {
        return $this->syllable_count;
    }

    /**
     * @return int
     */
    public function getSentenceCount()
    {
        return $this->sentence_count;
    }

    /**
     * @return float
     */
    public function getReadabilityScore()
    {
        return $this->readability_score;
    }

    /**
     * @return float
     */
    public function getGradeLevel()
    {
        return $this->grade_level;
    }

    /**
     * @return int
     */
    private function deriveTotalNumberOfSyllables()
    {
        $tot_syllables = 0;
        foreach($this->words as $word) {
            $num_syllables = $this->deriveNumberOfSyllables($word);
            $tot_syllables += $num_syllables;
        }
        return $tot_syllables;
    }

    /**
     * @return float
     */
    private function deriveFleschReadingEaseScore()
    {
        $score = 0;
        $total_words = count($this->words);
        if ($total_words >= 100) {
            $score = 206.835 - (1.015 * ($total_words / $this->sentence_count)) - (84.6 * ($this->syllable_count / $total_words));
        }
        return round($score, 0);
    }

    /**
     * @return float
     */
    private function deriveFleschKincaidGradeLevel()
    {
        $grade_level = 0;
        $total_words = count($this->words);
        if ($total_words >= 100) {
            $grade_level = 0.39 * ($total_words / $this->sentence_count) + 11.8 * ($this->syllable_count / $total_words) - 15.59;
        }
        return round($grade_level, 0);
    }

    /**
     * @param string $word
     * @return int
     */
    private function deriveNumberOfSyllables(string $word)
    {
        $syllable_count = 0;
        $last_was_vowel = false;

        $lower_case = $this->convertWord($word);

        for ($n = 0; $n < strlen($lower_case);$n++) {

            if ($this->isVowel($lower_case[$n])) {
                if (!$last_was_vowel) {
                    $syllable_count++;
                }
                $last_was_vowel = true;
            } else {
                $last_was_vowel = false;
            }
        }

        if ($this->endsWith($lower_case, 'ing') || $this->endsWith($lower_case, 'ings') ) {
            if (strlen($lower_case) > 4 && $this->isVowel($lower_case[strlen($lower_case) - 4])) {
                $syllable_count++;
            }
        }

        if ($this->endsWith($lower_case, 'e') && !$this->endsWith($lower_case, 'le')) {
            $syllable_count--;
        }

        if ($this->endsWith($lower_case, 'es')) {
            if (strlen($lower_case) > 4 && $this->isVowel($lower_case[strlen($lower_case) - 4])) {
                $syllable_count--;
            }
        }

        if ($this->endsWith($lower_case, "e's")) {
            if (strlen($lower_case) > 5 && $this->isVowel($lower_case[strlen($lower_case) - 5])) {
                $syllable_count--;
            }
        }
        if ($this->endsWith($lower_case, 'ed') &&
            !$this->endsWith($lower_case, 'ted') &&
            !$this->endsWith($lower_case, 'ded')) {
            $syllable_count--;
        }

        return $syllable_count > 0 ? $syllable_count : 1;
    }

    /**
     * @param string $word
     * @return string
     */
    private function convertWord(string $word)
    {
        $temp = strtolower($word);
        $search  = ['ome', 'ime', 'imn',  'ine', 'ely', 'ure', 'ery'];
        $replace = ['um',  'im',  'imen', 'in',  'ly',  'ur',  'ry'];
        return str_replace($search, $replace, $temp);
    }

    /**
     * @param string $string
     * @param string $end_str
     * @return bool
     */
    private function endsWith(string $string, string $end_str)
    {
        $str_len = strlen($string);
        $test_len = strlen($end_str);
        if ($test_len > $str_len) return false;
        return substr_compare($string, $end_str, $str_len - $test_len, $test_len) === 0;
    }

    /**
     * @param string $character
     * @return bool
     */
    private function isVowel(string $character)
    {
        $vowels = ['a', 'e', 'i', 'o', 'u', 'y'];
        return in_array($character, $vowels);
    }

    /**
     * @return array
     */
    private function deriveSentences()
    {
        return  preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $this->text);
    }

    /**
     * @return float
     */
    private function deriveAverageSentenceLength()
    {
        $sentence_count = count($this->sentences);
        $total_length = 0;

        foreach ($this->sentences as $sentence) {
            $length = $this->deriveSentenceLength($sentence);
            $total_length += $length;
        }
        $avg_length = $total_length / $sentence_count;


        return round($avg_length, 1);
    }

    /**
     * @param string $sentence
     * @return int
     */
    private function deriveSentenceLength($sentence)
    {
        return str_word_count($sentence);
    }


    /**
     * @return float
     */
    private function deriveSODScale()
    {
        $pct['self'] = 0;
        $pct['others'] = 0;
        $pct['neutral'] = 100;

        $sentences_with_pronouns_count = $this->getSentencesWithPronounsCount();
        $self_directed_pronouns_count = $this->getSelfDirectedPronounsCount();
        $others_directed_pronouns_count = $this->pronoun_count - $self_directed_pronouns_count;

        if ($this->pronoun_count > 0 && $this->sentence_count > 0) {
            $sentences_with_pronouns_pct = $sentences_with_pronouns_count / $this->sentence_count;
            $self_directed_pct = ($self_directed_pronouns_count / $this->pronoun_count) * $sentences_with_pronouns_pct;
            $others_directed_pct = ($others_directed_pronouns_count / $this->pronoun_count) * $sentences_with_pronouns_pct;

            $pct['self'] = round($self_directed_pct * 100);
            $pct['others'] = round($others_directed_pct * 100);
            $pct['neutral'] = 100 - ($pct['self'] + $pct['others']);

        };

        return $pct;

    }

    /**
     * @return int
     */
    private function getSentencesWithPronounsCount()
    {
        $count = 0;
        foreach ($this->sentences as $sentence) {
            if ($this->sentenceHasPronoun($sentence)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @param $sentence
     * @return int
     */
    private function sentenceHasPronoun($sentence)
    {
        $words = str_word_count($sentence, 1);
        $unique_words = array_count_values($words);
        $pronoun_words = array_intersect_key($unique_words, $this->pronouns);
        if ($pronoun_words) {
            return true;
        }
        return false;
    }

    /**
     * @return int
     */
    private function getSelfDirectedPronounsCount()
    {
        $self_directed_pronoun_words = array_intersect_key($this->unique_words, $this->self_directed_pronouns);
        return array_sum($self_directed_pronoun_words);
    }


}
