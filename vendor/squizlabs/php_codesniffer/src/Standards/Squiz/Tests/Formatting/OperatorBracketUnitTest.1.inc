<?php
$value = ($one + $two);
$value = $one + $two;

$value = ($one - $two);
$value = $one - $two;

$value = ($one * $two);
$value = $one * $two;

$value = ($one / $two);
$value = $one / $two;

$value = ($one % $two);
$value = $one % $two;

$value = ($one + $two + $three);
$value = $one + $two + $three;
$value = ($one + ($two + $three));
$value = $one + ($two + $three);

$value++;
$value--;
$value = -1;
$value = - 1;

$value = (1 + 2);
$value = 1 + 2;

$value = (1 - 2);
$value = 1 - 2;

$value = (1 * 2);
$value = 1 * 2;

$value = (1 / 2);
$value = 1 / 2;

$value = (1 % 2);
$value = 1 % 2;

$value = (1 + 2 + 3);
$value = 1 + 2 + 3;
$value = (1 + (2 + 3));
$value = 1 + (2 + 3);

$value = $one + 2 + 3 - ($four * $five * (6 + 7)) + $nine + 2;
$value = myFunction($tokens[$stackPtr - 1]);

for ($i = 1 + 2; $i < 4 + 5; $i++) {
}

function myFunction()
{
    $value = ($one + 1) + ($two + 2) + (myFunction() + 2);
    $value = myFunction() + 2;
    $value = (myFunction($var) + myFunction2($var));
    return -1;
}

$line  = substr($line, 0, -2);
$words = preg_split('|(\s+)|', $line."\n", -1, $flags);
if (!isset($this->words[$wordPos-1]) || $this->words[$wordPos-1] !== ' ') {
} else if ($this->tokens[$pos + 1] === "\n") {
}

if ($pos === count($this->tokens) - 1) {
    $file = '...'.substr($file, (($padding * -1) + 3));
}

if (substr($basename, -5) !== 'Sniff') {
    $i = ($this->_tokens[$i]['parenthesis_closer'] + 1);
}

$pos = $this->_getShortCommentEndPos();
if ($pos === -1) {
    $stackPtr = ($tokens[$next][$to] - 1);
    $stackPtr = ($tokens[$next][$pattern[$i]['to']] + 1);
    $var = (($var1 + $var2) + $var3 + $var4)
}

$commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);
$commentEnd   = ($this->_phpcsFile->findNext(T_DOC_COMMENT, ($commentStart + 1), null, true) - 1);
$expected    .= '...'.substr($tokens[($stackPtr - 2)]['content'], -5).$tokens[$stackPtr]['content'];

if (($tokens[$nextToken - 1]['code']) !== T_WHITESPACE) {
    $errorPos = ($params[(count($params) - 1)]->getLine() + $commentStart);
}

while (($nextSpace = $phpcsFile->findNext(T_WHITESPACE, $nextSpace + 1, $nextBreak)) !== false) {
}

foreach ($attributes as $id => &$attribute) {
}

class MyClass
{


    public function &myFunction(array &$one, array &$two)
    {

    }//end myFunction()


}//end class

if ($index < -1) $index = 0;
if ($index < - 1) $index = 0;

$three = ceil($one / $two);
$three = ceil(($one / $two) / $four);
$three = ceil($one / ($two / $four));

$four = -0.25;

$three = ceil($one[1] / $two);

switch ($number % 10) {
    case -1:
        $suffix = 'st';
    break;
}

$expectedPermission = array(
                       'granted'        => 4,
                       'denied'         => 1,
                       'cascade'        => TRUE,
                       'blockergranted' => 2,
                       'blockerdenied'  => - 3,
                       'effective'      => TRUE,
                      );

$value = (int) isset($blah) + 2;
$value = (int) isset($blah) + (int) isset($foo) + (int) isset($bar);

doSomething(getValue($var, 2)) - $y;

$codeFiles = array($global => $codeFiles[$global]) + $codeFiles;

$var = array(-1);
$var = [-1];
$var = [0, -1, -2];

$cntPages = ceil(count($items) / self::ON_PAGE);

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
error_reporting(E_ALL & ~E_NOTICE | ~E_WARNING);
$results = $stmt->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP | \PDO::FETCH_UNIQUE);
$di = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS);
foo(1 + 2 + 3);

if (empty($foo[-1]) === true) {
    $foo[-1] = 'foo';
}

try {
} catch (AException | BException $e) {
}

$var = $foo['blah'] + [];

$a = 2 * ${x} - ${minus};

$foo = $bar ?? $baz ?? '';

$foo = $myString[-1];

$value = (binary) $blah + b"binary $foo";

$test = (1 * static::TEST);
$test = myfunc(1 * static::TEST);

$errorPos = $params[$x]?->getLine() + $commentStart;

$foo = $this->gmail ?? $this->gmail = new Google_Service_Gmail($this->google);

exit -1;

$expr = match ($number - 10) {
    -1 => 0,
};

$expr = match ($number % 10) {
    1 => 2 * $num,
};

$expr = match (true) {
    $num * 100 > 500 => 'expression in key',
};

// PHP 8.0 named parameters.
if ($pos === count(value: $this->tokens) - 1) {
    $file = '...'.substr(string: $file, offset: $padding * -1 + 3);
}

match ($a) {
    'a' => -1,
    'b', 'c', 'd' => -2,
    default => -3,
};

$cntPages = ceil(count($items) / parent::ON_PAGE);
