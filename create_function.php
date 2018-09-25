<?php
function ocena ($jaka=5){
if($jaka<=2){
echo('slabo<br>');
}
elseif ($jaka<=3){
echo ('srednio<br>');
}
else{
   echo('super<br>'); 
}
}
ocena(1);
ocena(2);
ocena(3);
ocena(4);
ocena(5);
?>

<?php
function poleProst($a, $b){
return $a * $b;
}
echo ('Pole prostokata o bokach 5x3 ='.poleProst(5,3));
?>