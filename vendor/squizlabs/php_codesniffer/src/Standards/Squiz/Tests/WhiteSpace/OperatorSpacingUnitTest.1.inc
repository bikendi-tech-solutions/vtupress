<?php

$result = 1 + 2;
$result = 1  + 2;
$result = 1  +   2;
$result = 1 +2;
$result = 1+ 2;
$result = 1+2;

$result = 1 - 2;
$result = 1  - 2;
$result = 1  -   2;
$result = 1 -2;
$result = 1- 2;
$result = 1-2;

$result = 1 * 2;
$result = 1  * 2;
$result = 1  *   2;
$result = 1 *2;
$result = 1* 2;
$result = 1*2;

$result = 1 / 2;
$result = 1  / 2;
$result = 1  /   2;
$result = 1 /2;
$result = 1/ 2;
$result = 1/2;

$result = 1 % 2;
$result = 1  % 2;
$result = 1  %   2;
$result = 1 %2;
$result = 1% 2;
$result = 1%2;
$result = '100%';

$result += 4;
$result+=4;
$result -= 4;
$result-=4;
$result /= 4;
$result/=4;
$result *=4;
$result*=4;

$result =& $thing;
if ($result & 4) {
    if ($result | 4) {
    }
}
if ($result&4 && $result  &  4) {
    if ($result |4 || $result  |  4) {
    }
}

$result = ((1 + 2) - (3 * 4 / 5) % 6);
$result = ((1+  2)  -  (3*4/5)  %  6);
return -1;
$file = '...'.substr($file, (($padding * -1) + 3));

$totalErrors   += $errors['errors'];
$totalWarnings +=  $errors['warnings'];

if (substr($line, 0, 3) === '/**') {
    $line = substr($line, 3);
} else if (substr($line, -2, 2) === '*/') {
    $line = substr(-2, 0, -2);
} else if ($line[0] === '*') {
    $line = substr($line, 1);
}

if ($pos === -1) {
}

for ($i=0; $i<=5; $i++) {
    $j^= $i;
    $k %=$i;
    $l&=$i;
    $m.= 'Hello ';
}

$z = ($a+ $b- $c* $d/ $e% $f^ $g);
$z = ($a +$b -$c *$d /$e %$f ^$g);

$a== $b && $c!= $d && $e=== $f && $g!== $h;
$i> $j && $k< $l && $m>= $n && $o<= $p && $q<> $r;

$a ==$b && $c !=$d && $e ===$f && $g !==$h;
$i >$j && $k <$l && $m >=$n && $o <=$p && $q <>$r;

function myFunction($variable=0, $var2='string') {}

if (index > -1) {
}

array_walk_recursive($array, function(&$item) use (&$something) {
});

$var = saveFile(&$model, &$foo);

// This is all valid.
$boo = -$foo;
function foo($boo = -1) {}
$foo = array('boo' => -1);
$x = $test ? -1 : 1;
$y = $test ? 1 : -1;
$z = $test ?: false;

$closureWithDefaultParameter = function (array $testArray=array()) {};

switch ($foo) {
	case -1:
		break;
}

$y = 1 * -1;
$y = -1 * 1;
$y = -1 * $var;
$y = 10 / -2;
$y = -10 / 2;
$y = (-10 / 2);
$y = (-10 / $var);
$y = 10 + -2;
$y = -10 + 2;

$a = $x?$y:$z;
$a = $x ? $y : $z;

$y = 1
   + 2  
   - 3;

$y = 1 + 
     2 - 
     3;

$y = 1
+ 2
- 3;

// phpcs:set Squiz.WhiteSpace.OperatorSpacing ignoreNewlines true
$y = 1
   + 2
   - 3;

$y = 1 +
     2 -
     3;

$y = 1
+ 2
- 3;
// phpcs:set Squiz.WhiteSpace.OperatorSpacing ignoreNewlines false

if (true || -1 == $b) {
}

$var = array(-1);
$var = [-1];
$var = [0, -1, -2];

$y = array(&$x);
$y = [&$x];
$y = array(&$a, &$b, &$c);
$y = [&$a, &$b, &$c];
$y = array(&$a => 1, 2 => &$b, &$c);
$y = [&$a => 1, 2 => &$b, &$c];

if ($a <=> $b) {
}

if ($a <=>$b) {
}

$a |=  $b;
$a **=  $b;
$a ??=  $b;

$a = +1;

function bar($boo = +1) {}

$username = $_GET['user']??'nobody';

function foo(string $bar, array $baz, ?MyClass $object) : MyClass {}

declare(strict_types=1);

function foo($c = ((BAR)?10:100)) {}

$res = $a ?: $b;
$res = $a ?:  $b;
$res = $a  ?: $b;
$res = $a  ?:  $b;

$res = $a ? : $b;
$res = $a ? :  $b;
$res = $a  ? : $b;
$res = $a  ? :  $b;

function foo(string $a = '', ?string $b = ''): ?string {}

// Issue #1605.
$text = preg_replace_callback(
		self::CHAR_REFS_REGEX,
		[ 'Sanitizer', 'decodeCharReferencesCallback' ],
		$text, /* limit */ -1, $count );

if (true || /* test */ -1 == $b) {}
$y = 10 + /* test */ -2;

// Issue #1604.
Hooks::run( 'ParserOptionsRegister', [
	&self::$defaults,
	&self::$inCacheKey,
	&self::$lazyOptions,
] );

$x = $foo ? function (): int {
    return 1;
} : $bar;

$x = $foo ? function ($foo)
    // comment
    : int {
    return 1;
} : $bar;

$x = $foo ? function ($foo) use /* comment */ ($bar): int {
    return 1;
} : $bar;

$x = !$foo ? $bar : function (): int {
    return 1;
};

$a =
    // Comment.
    [
        'a',
        'b',
    ];

$a =
// phpcs:ignore Standard.Category.Sniff -- for reasons.
[
    'a',
    'b',
];

$foo = is_array($bar) ? array_map(
        function () {},
        $bar
    ) : $bar;

function bar(): array {}

if ($line[-1] === ':') {
    $line = substr($line, 0, -1);
}

$a = $a instanceof $b;
$a = $a  instanceof  $b;
$a = ($a)instanceof$b;

fn&($x) => $x;

// phpcs:set Squiz.WhiteSpace.OperatorSpacing ignoreSpacingBeforeAssignments false
$a  =  3;
// phpcs:set Squiz.WhiteSpace.OperatorSpacing ignoreSpacingBeforeAssignments true
yield -1;
echo -1;
$a = -1;
func(-1);
$a = [-1];
return -1;
print -1;
$a &= -1;
switch ($a) {
    case -1:
}
$a = $a ?? -1;
$a .= -1;
$a /= -1;
$a = [1 => -1];
$a = $a == -1;
$a = $a >= -1;
$a = $a === -1;
$a = $a != -1;
$a = $a !== -1;
$a = $a <= -1;
$a = $a <=> -1;
$a = $a and -1;
$a = $a or -1;
$a = $a xor -1;
$a -= -1;
$a %= -1;
$a *= -1;
$a |= -1;
$a += -1;
$a = $a ** -1;
$a **= -1;
$a = $a << -1;
$a <<= -1;
$a = $a >> -1;
$a >>= -1;
$a = (string) -1;
$a = (array) -1;
$a = (bool) -1;
$a = (object) -1;
$a = (unset) -1;
$a = (float) -1;
$a = (int) -1;
$a ^= -1;
$a = [$a, -1];
$a = $a[-1];
$a = $a ? -1 : -1;

yield - 1;
echo - 1;
$a = - 1;
func(- 1);
$a = [- 1];
return - 1;
print - 1;
$a &= - 1;
switch ($a) {
    case - 1:
}
$a = $a ?? - 1;
$a .= - 1;
$a /= - 1;
$a = [1 => - 1];
$a = $a == - 1;
$a = $a >= - 1;
$a = $a === - 1;
$a = $a != - 1;
$a = $a !== - 1;
$a = $a <= - 1;
$a = $a <=> - 1;
$a = $a and - 1;
$a = $a or - 1;
$a = $a xor - 1;
$a -= - 1;
$a %= - 1;
$a *= - 1;
$a |= - 1;
$a += - 1;
$a = $a ** - 1;
$a **= - 1;
$a = $a << - 1;
$a <<= - 1;
$a = $a >> - 1;
$a >>= - 1;
$a = (string) - 1;
$a = (array) - 1;
$a = (bool) - 1;
$a = (object) - 1;
$a = (unset) - 1;
$a = (float) - 1;
$a = (int) - 1;
$a ^= - 1;
$a = [$a, - 1];
$a = $a[- 1];
$a = $a ? - 1 : - 1;


yield -$b;
echo -$b;
$a = -$b;
func(-$b);
$a = [-$b];
return -$b;
print -$b;
$a &= -$b;
switch ($a) {
    case -$b:
}
$a = $a ?? -$b;
$a .= -$b;
$a /= -$b;
$a = [1 => -$b];
$a = $a == -$b;
$a = $a >= -$b;
$a = $a === -$b;
$a = $a != -$b;
$a = $a !== -$b;
$a = $a <= -$b;
$a = $a <=> -$b;
$a = $a and -$b;
$a = $a or -$b;
$a = $a xor -$b;
$a -= -$b;
$a %= -$b;
$a *= -$b;
$a |= -$b;
$a += -$b;
$a = $a ** -$b;
$a **= -$b;
$a = $a << -$b;
$a <<= -$b;
$a = $a >> -$b;
$a >>= -$b;
$a = (string) -$b;
$a = (array) -$b;
$a = (bool) -$b;
$a = (object) -$b;
$a = (unset) -$b;
$a = (float) -$b;
$a = (int) -$b;
$a ^= -$b;
$a = [$a, -$b];
$a = $a[-$b];
$a = $a ? -$b : -$b;

yield - $b;
echo - $b;
$a = - $b;
func(- $b);
$a = [- $b];
return - $b;
print - $b;
$a &= - $b;
switch ($a) {
    case - $b:
}
$a = $a ?? - $b;
$a .= - $b;
$a /= - $b;
$a = [1 => - $b];
$a = $a == - $b;
$a = $a >= - $b;
$a = $a === - $b;
$a = $a != - $b;
$a = $a !== - $b;
$a = $a <= - $b;
$a = $a <=> - $b;
$a = $a and - $b;
$a = $a or - $b;
$a = $a xor - $b;
$a -= - $b;
$a %= - $b;
$a *= - $b;
$a |= - $b;
$a += - $b;
$a = $a ** - $b;
$a **= - $b;
$a = $a << - $b;
$a <<= - $b;
$a = $a >> - $b;
$a >>= - $b;
$a = (string) - $b;
$a = (array) - $b;
$a = (bool) - $b;
$a = (object) - $b;
$a = (unset) - $b;
$a = (float) - $b;
$a = (int) - $b;
$a ^= - $b;
$a = [$a, - $b];
$a = $a[- $b];
$a = $a ? - $b : - $b;

exit -1;

$cl = function ($boo =-1) {};
$cl = function ($boo =+1) {};
$fn = fn ($boo =-1) => $boo;
$fn = fn ($boo =+1) => $boo;

$fn = static fn(DateTime $a, DateTime $b): int => -($a->getTimestamp() <=> $b->getTimestamp());

$a = 'a '.-MY_CONSTANT;
$a = 'a '.-$b;
$a = 'a '.- MY_CONSTANT;
$a = 'a '.- $b;

match ($a) {
    'a' => -1,
    'b', 'c', 'd' => -2,
    default => -3,
};

$foo = $var
    ? 10
    : true;

// Safeguard that a non-fixable error is thrown when there is a new line before the operator,
// but the last non-whitespace token before the operator is a comment token.
$foo = $var // Comment
    ? false /* Comment */
    : true;

$foo = $var // phpcs: ignore Stnd.Cat.Sniff -- for reasons.


    ? $something /**
     * Don't ask, but someone might have a docblock between the lines. It's valid PHP after all.
     */


    : true;

// phpcs:set Squiz.WhiteSpace.OperatorSpacing ignoreNewlines true
$foo = $var // Comment
    ? false // Comment
    : true;
// phpcs:set Squiz.WhiteSpace.OperatorSpacing ignoreNewlines false
