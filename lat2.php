<?php
$a = $_GET['a'] ?? 0;
$b = $_GET['b'] ?? 0;

if ($a > $b) {
    echo $a . " lebih besar<br>";
} elseif ($a < $b) {
    echo $b . " lebih besar<br>";
} else {
    echo "nilai mereka sama<br>";
}

$c = $a + $b;

for ($i = 0; $i < $c; $i++) {
    echo "Loop yang ke- " . $i . "<br>";
}
echo "<br>";
while ($c >0) {
    echo $c . " ";
    $c = $c - 1;
}