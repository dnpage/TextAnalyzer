<?php

namespace  DNPage\TextAnalyzer;


class WordLists
{
    protected $stop_words = [
        "-", "--", "th", 'pm', 'i', "i've", "'",
        "a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone",
        "along", "already", "also", "although", "always", "am","among", "amongst", "amount", "an", "and",
        "another", "any", "anyhow", "anyone", "anything", "anyway", "anywhere", "are", "around", "as", "at", "back",
        "be", "because", "become","becomes", "becoming", "been", "before", "beforehand", "behind", "being",
        "below", "beside", "besides", "between", "beyond", "both", "bottom", "but", "by", "can",
        "cannot", "can't", "co", "con", "could", "couldn't", "de",  "do", "done", "down",
        "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc",
        "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fifty", "fill",
        "find", "fire", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front",
        "full", "further", "get", "give", "go", "had", "has", "hasn't", "have", "he", "hence", "her", "here",
        "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however",
        "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "it's", "itself", "keep",
        "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might",
        "mill", "mine", "more", "moreover", "most", "mostly", "much", "must", "my", "myself", "name", "namely",
        "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "nor", "not", "nothing",
        "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others",
        "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put",
        "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "several", "she", "should",
        "show", "side", "since", "six", "sixty", "so", "some", "somehow", "someone", "something",
        "sometime", "sometimes", "somewhere", "still", "such", "take", "ten", "than", "that", "the",
        "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein",
        "thereupon", "these", "they", "this", "those", "though", "three", "through",
        "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty",
        "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what",
        "whatever", "when", "whence", "whenever", "where", "whereas", "whereby", "wherein", "whereupon", "wherever",
        "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with",
        "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the"
    ];

    protected $pronouns = [
        "i", "me", "my", "mine", "we", "us", "our", "ours",
        "you", "your", "yours", "he", "him", "his", "she", "her", "hers", "it", "its",
        "they", "them", "theirs"
    ];

    protected $self_directed_pronouns = [
        "i", "me", "my", "mine", "we", "us", "our", "ours"
    ];

    protected $other_directed_pronouns = [
        "you", "your", "yours", "he", "him", "his", "she", "her", "hers", "it", "its",
        "they", "them", "theirs"
    ];

    public function stopWords()
    {
        return array_fill_keys($this->stop_words, '');
    }


    public function pronouns()
    {
        return array_fill_keys($this->pronouns, '');
    }

    public function selfDirectedPronouns()
    {
        return array_fill_keys($this->self_directed_pronouns, '');
    }

    public function otherDirectedPronouns()
    {
        return array_fill_keys($this->other_directed_pronouns, '');
    }
}