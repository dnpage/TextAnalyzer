<?php

namespace DNPage\TextAnalyzer;

interface TextAnalyzerInterface
{
    public function loadText($text);
    public function getSentences();
    public function getAverageSentenceLength();
    public function getAllWordCount();
    public function getUniqueWordCount();
    public function getAbridgedWordCount();
    public function getPronounCount();
    public function getAllWords();
    public function getUniqueWordFrequency();
    public function getAbridgedWordFrequency();
    public function getTopAbridgedWordFrequency($count);
    public function getPronounFrequency();
    public function getSyllableCount();
    public function getSentenceCount();
    public function getReadabilityScore();
    public function getGradeLevel();
    public function getSODScale();
}
