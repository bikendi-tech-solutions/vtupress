<?php

// Valid
$var1    = 'var1';
$var10   = 'var1';
$var100  = 'var1';
$var1000 = 'var1';

// Invalid
$var1    = 'var1';
$var10 = 'var1';
$var100 = 'var1';
$var1000 = 'var1';

// Valid
$var1  = 'var1';
$var10 = 'var1';

$var100  = 'var1';
$var1000 = 'var1';

// Invalid
$var1     = 'var1';
$var10    = 'var1';

$var100   = 'var1';
$var1000  = 'var1';

// Valid
$var1    .= 'var1';
$var10   .= 'var1';
$var100  .= 'var1';
$var1000 .= 'var1';

// Invalid
$var1    .= 'var1';
$var10.= 'var1';
$var100 .= 'var1';
$var1000 .= 'var1';

// Valid
$var1     = 'var1';
$var10   .= 'var1';
$var100   = 'var1';
$var1000 .= 'var1';

// Invalid
$var1    = 'var1';
$var10   .= 'var1';
$var100  = 'var1';
$var1000.= 'var1';

// Valid
$var1  .= 'var1';
$var10 .= 'var1';

$var100  .= 'var1';
$var1000 .= 'var1';

// Invalid
$var1     .= 'var1';
$var10    .= 'var1';

$var100   .= 'var1';
$var1000  .= 'var1';

// Valid
$var = 100;

// InValid
$var  = 100;

$commentStart = $phpcsFile->findPrevious();
$commentEnd   = $this->_phpcsFile;
$expected    .= '...';

// Invalid
$this->okButton    = new Button();
$content = new MyClass();


$GLOBALS['_PEAR_ERRORSTACK_SINGLETON'] = array();

class MyClass
{
    const MODE_DEBUG = 'debug';
    const MODE_DEBUG2 = 'debug';

    var $array[$test] = 'anything';
    var $var          = 'anything';

    const MODE_DEBUG3  = 'debug';
    public $array[$test]    = 'anything';
    private $vara             = 'anything';
    protected $array[($test + 1)] = 'anything';
    var $array[($blah + (10 - $test))] = 'anything';
}

function myFunction($var=true)
{
    if ($strict === true) {
        $length          = strlen($string);
        $lastCharWasCaps = ($classFormat === false) ? false : true;

        for ($i = 1; $i < $length; $i++) {
            $isCaps = (strtoupper($string[$i]) === $string[$i]) ? true : false;
            if ($isCaps === true && $lastCharWasCaps === true) {
                return false;
            }

            $lastCharWasCaps = $isCaps;
        }
    }
}

// Valid
for ($i = 0; $i < 10; $i += 2) {
    $i = ($i - 1);
}

// Invalid
foreach ($files as $file) {
    $saves[$file] = array();
    $contents = stripslashes(file_get_contents($file));
    list($assetid, $time, $content) = explode("\n", $contents);
    $saves[$file]['assetid'] = $assetid;
}

$i = ($i - 10);
$ip = ($i - 10);
for ($i = 0; $i < 10; $i += 2) {
    $i = ($i - 10);
}

// Valid
$variable = 12;
$var      = a_very(long_line('that', 'contains'),
                   a_bunch('of long', 'parameters'),
                   'that_need to be aligned with the equal sign');
$var2     = 12;

// Valid
$variable = 12;
$var      = 'a very long line of text that contains '
            .$someVar
            .' and some other stuff that is too long to fit on one line';
$var2     = 12;

// Invalid
$variable = 12;
$var      = a_very(long_line('that', 'contains'),
                   a_bunch('of long', 'parameters'),
                   'that_need to be aligned with the equal sign');
$var2 = 12;

// Invalid
$variable = 12;
$var      = 'a very long line of text that contains '
            .$someVar
            .' and some other stuff that is too long to fit on one line';
$var2 = 12;

// Valid
$variable = 12;
$var     .= 'a very long line of text that contains '
            .$someVar
            .' and some other stuff that is too long to fit on one line';
$var2     = 12;

// Valid
$variable += 12;
$var      .= 'a very long line of text that contains '
            .$someVar
            .' and some other stuff that is too long to fit on one line';
$var2      = 12;

// Invalid
$variable  = 12;
$var      .= 'a very long line of text that contains '
            .$someVar
            .' and some other stuff that is too long to fit on one line';
$var2      = 12;

// Valid
$error = false;
while (list($h, $f) = getKeyAndValue($handle)) {
    $error = true;
}

// Valid
$value = false;
function blah ($value = true) {
    $value = false;
    if ($value === true) {
        $value = false;
    }
}

if (TRUE) {
    $args = array('foo' => 'foo');
    $res  = 'bar';
}

// phpcs:set Generic.Formatting.MultipleStatementAlignment maxPadding 8

$one                = 'one';
$varonetwo          = 'two';
$varonetwothree     = 'three';
$varonetwothreefour = 'four';

$one        = 'one';
$varonetwo .= 'two';
$varonetwo  = 'two';
$varonetwo .= 'two';
$varonetwothree     = 'three';
$varonetwothreefour = 'four';

// phpcs:set Generic.Formatting.MultipleStatementAlignment maxPadding 1000

$filterQuery = SquizRoadmap::getSearchQueryFilter($searchParams);
Channels::addToBasket('itemid', $filterQuery);
$query   = DAL::getDALQuery('SquizRoadmapItem', 'getItemIds');
$results = DAL::getAssoc($query, 0);

$path  = BaseSystem::getDataDir('SquizRoadmap').'/items/';
$path .= FileSystem::getHashDir($itemid).'/'.$itemid;

$contents  .= 'if (';
$conditions = array();

$bar = 'hi';
$foo = $moo = $test;
$boo = 'boo';
$foo = $moooo = 'foo';

$foobarbaz = array_map(
    function ($n) {
        return $n * $n;
    },
    [1, 2, 3]
);
$foo       = 5;

$loggerResult = $util->setLogger(new class {
    public function log($msg)
    {
        echo $msg;
    }
});
$foo          = 5;

$foo = array(
    'a' => 'b',
);
$barbar = 'bar';

$foo = array(
    // Some comment.
    'a' => 'b',
);
$barbar = 'bar';

$foo = [
    // phpcs:ignore Standard.Category.Sniff.Code -- for reasons.
    'a' => 'b',
];
$barbar = 'bar';

$foo = [

    'a' => 'b',
];
$barbar = 'bar';

function buildForm(FormBuilderInterface $builder, array $options)
{
    $transformer = new ContractTransformer($options['contracts']);
    $types       = ['support.contact.question' => ContactData::QUESTION];

    [$important, $questions, $incidents, $requests] = $this->createContractBuckets($options['contracts']);
}

function buildForm(FormBuilderInterface $builder, array $options)
{
    $transformer                                    = new ContractTransformer($options['contracts']);
    $types                                          = ['support.contact.question' => ContactData::QUESTION];
    [$important, $questions, $incidents, $requests] = $this->createContractBuckets($options['contracts']);
}

$loggerResult = $util->setLogger(new class {
    public function log($msg)
    {
        $a = $msg;
        $foobar = $msg;
        $foo = function() {
            $a = $msg;
            $foobar = $msg;
            $loggerResult = $util->setLogger(new class {
                public function log($msg)
                {
                    $a = $msg;
                    $foobar = $msg;
                    $foo = function() {
                        foo(function() {
                            foo(function() {
                                echo 'hi';
                            });
                            $a = $msg;
                            $foobar = $msg;

                            $foo = function() {

                                $foo = 1;
                                $barbar=2;
                            };
                            $barbar = function() {
                                $foo    = 1;
                                $barbar = 2;
                            };
                        });
                        $a = $msg;
                        $foobar      = $msg;
                    };
                    $bar = $msg;
                }

                public function log2($msg)
                {
                    $a = $msg;
                    $foobar = $msg;
                    $foo = function() {
                        foo(function() {
                            foo(function() {
                                echo 'hi';
                            });
                            $a = $msg;
                            $foobar = $msg;

                            $foo = function() {

                                $foo = 1;
                                $barbar=2;
                            };
                            $barbar = function() {
                                $foo    = 1;
                                $barbar = 2;
                            };
                        });
                        $a = $msg;
                        $foobar      = $msg;
                    };
                    $bar = $msg;
                }
            });
            $foo          = 5;
        };
        $bar = $msg;
    }
});
$foo          = 5;

$foo = [
    0 => function () {
        $foo = 'foo';
        $barbar = 'bar';
    },
    1 => function () {
        $foo    = 'foo';
        $barbar = 'bar';
    },
];

$abc = 'something';
if ($foo) {}
$defghi = 'longer something';

function foo() {
    $foo = 'foo';
    $bar = 'bar';
    ?>

    <div>
        <?php
        $foo = 'foo';
        $bar = 'bar';
        ?>
    </div>
    <?php
}

$foo = new Foo([
    $a = new Bar(),
    $b = new Bar(),
]);

$foo = new Foo([
    $a = new Bar(),
    $b    = new Bar(),
    $c  = new Bar(),
]);
$foofoo   = new Foo([
    $a = new Bar(),
    $b = new Bar(),
    $c = new Bar(),
]);

$i = 0;
echo "TEST: ".($i += 1)."\n";

// Valid
$foo      = 'Hello';
$variable = 12;
$foo     .= ' World';
$test     = 1;
$test   <<= 6;

// Invalid
$foo      = 'Hello';
$variable = 12;
$foo      .= ' World';
$test     = 1;
$test     <<= 6;

// phpcs:set Generic.Formatting.MultipleStatementAlignment alignAtEnd false

// Valid
$foo      = 'Hello';
$variable = 12;
$foo      .= ' World';
$test     = 1;
$test     <<= 6;

// Invalid
$foo      = 'Hello';
$variable = 12;
$foo     .= ' World';
$test     = 1;
$test   <<= 6;

// phpcs:set Generic.Formatting.MultipleStatementAlignment maxPadding 8

$one                = 'one';
$varonetwo          = 'two';
$varonetwothree     = 'three';
$varonetwothreefour = 'four';

$one       = 'one';
$varonetwo .= 'two';
$varonetwo = 'two';
$varonetwo .= 'two';
$varonetwothree     = 'three';
$varonetwothreefour = 'four';

$one         <<= 8;
$onetwothree = 3;

// phpcs:set Generic.Formatting.MultipleStatementAlignment maxPadding 1000

$a        = 123;
$model    = new class() {
    // empty
};
$resource = new class() {
    // empty
};

// phpcs:set Generic.Formatting.MultipleStatementAlignment alignAtEnd true

$one       <<= 8;
$onetwothree = 3;

// Issue 3326.
class Test
{
    public const DEFAULT = 'default';
    public const SOS     = 'sos';
    public const HELP    = 'help';

    protected static $thisIsAReallyLongVariableName = [];
}

// Issue #3460.
function issue3460_invalid() {
    $a = static function ($variables = false) use ($foo) {
        return $variables;
    };
    $b   = $a;
}

function issue3460_valid() {
    $a = static function ($variables = false) use ($foo) {
        return $variables;
    };
    $b = $a;
}

function makeSureThatAssignmentWithinClosureAreStillHandled() {
    $a = static function ($variables = []) use ($temp) {
        $a = 'foo';
        $bar = 'bar';
        $longer = 'longer';
        return $variables;
    };
}
