<?php 

$output = [];
$inputFile = 'input.txt';
$outputFile = 'output.json';

// using a supplied input filename, if given
if (array_key_exists(1, $argv)) {
    $inputFile = $argv[1];
}

// using a supplied output filename, if given
if (array_key_exists(2, $argv)) {
    $outputFile = $argv[2];
}

// Checking whether the input file exists
if ( !file_exists($inputFile) ) {
    die("Input file not found.\n");
}

$input = file($inputFile);

foreach ($input as $line) {
    //Stripping line endings from string and storing for later
    $original = preg_replace("/\r|\n/", '', $line);
    $stripped = format($line);
    $backwards = strrev($stripped);
    $palindromes = [];
    $result = new stdClass();
    //For each character in alphanumeric string
    for ($charpos=0; $charpos < strlen($stripped); $charpos++) {
        //create strings 2, 3, 4... char in length starting at current char
        for ($length=1; $length < strlen($stripped) - $charpos; $length++) {
            $string = substr($stripped, $charpos, $length);
            if (isPalindrome($string)) {
                array_push($palindromes, $string);
            }
        }
    }
    // If palindrome(s) are found, push new result object to output array
    if (count($palindromes) > 0) {
        usort($palindromes,'sortByLength');
        $result->original = $original;
        $result->palindromes = $palindromes;
        $result->characters = totalArrayStringLength($palindromes);
        array_push($output, $result);
    }
}

// Processing the resulting output array and placing it into file "output.json"
$jsondata = json_encode($output, JSON_PRETTY_PRINT);
file_put_contents($outputFile, $jsondata);
echo "Done!\n";

// usort helper function to arrange array from longest to shortest string
function sortByLength($a,$b){
    return strlen($b)-strlen($a);
}

// Returns the total number of characters in the given array
function totalArrayStringLength($array) {
    $total = 0;
    foreach ($array as $line) {
        $total += strlen($line);
    }
    return $total;
}

// Sprips all non alphanumeric characters
function format($line) {
    return preg_replace("/[^A-Za-z0-9]/", '', $line);
}

// Returns true if string is longer than 1 char and is the same forward and backward
function isPalindrome($string) {
    return (strlen($string) > 1 && $string === strrev($string));
}