<?php
$x=5; //global variable
function localVariable(){
    \$y=10; //local variable
    echo $y;
}
localVariable();
echo "\n, $x";
?>