<?php
use some\foo\{ClassA, ClassB, ClassC as C};
class Test
{
    public function __construct()
    {
    }

    function test1()
    {
     }

    function test2() {}

    private function _test3()
    {
    }

}


class Test2
{
 }


public function test2()
{
    if ($str[0]) {
        $chr = $str[0];
    }
    
    if (!class_exists($class_name)) {
        echo $error;
    }
    $this->{$property} =& new $class_name($this->db_index);
    $this->modules[$module] =& $this->{$property};
}

foreach ($elements as $element) {
    if ($something) {
        // Do IF.
    } else if ($somethingElse) {
        // Do ELSE.
    }
}

if ($token['code'] === T_COMMENT
    && $multiLineComment === false
    && (substr($token['content'], 0, 2) === '//'
    || $token['content'][0] === '#')
) {
}

switch ($blah) {
    case 'one':
        echo 'one';
    break;
    default:
        echo 'another';
}

?>

<?php while($row = $data->getRow()): ?>
    <p><?= $val ?></p>
<?php endwhile; ?>

<body>
    <div>
    <?php if (true == false) : ?>
        <h1>o hai!</h1>
    <?php endif; ?>
    </div>
</body>

<?php
    if ($foo) {
        ?>
        <?php         } ?>

<?php
    if ($foo) {
        ?>
    <?php         } ?>

<?php
    if ($foo) {
        ?>
    <?php } ?>

<?php
public function foo()
{
    $foo('some
    long description', function () {
    });

    $foo('some
    long
    description', function () {
});

    $foo(
'bad indent here leads to bad indent for closer', function () {
});
}

$fn1 = fn($x) => $x + $y;

$match = match ($test) { 1 => 'a', 2 => 'b' };

$match = match ($test) {
    1 => 'a',
    2 => 'b'
    };

?>
<?php if ($someConditional) : ?>
<div class="container">

</div> <?php endif; ?>

<?php

enum Enum
{
}

enum Suits {}

enum Cards
{
    }
