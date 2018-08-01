<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
<!-- https://www.uw-team.org/bbbbbbbbbbb-->
<!-- instrukcje warunkowe i obsluga formularzy-->
<form method= "post" action"">
Zgadnij jaka liczbę wymyslilem (0-1000)<br>
Liczba: <input type="text" name="liczba">
<input type="submit" value="wyslij">

</form>

<?php

ini_set('display_errors', '0');     # don't show any errors...
error_reporting(E_ALL | E_STRICT);  # ...but do log them


$liczba_komputera =431;
if ($_POST['liczba']>$liczba_komputera){

    echo'Za duzo :(');

} elseif ($_POST['liczba']<$liczba_komputera){
echo('Za malo:b(');

}else {
    echo('Zgadles!');
}

?>

<?php

//skladnia, tablice globalne
ini_set('display_errors', '0');     # don't show any errors...
error_reporting(E_ALL | E_STRICT);  # ...but do log them

$liczba1 = $_GET['liczba1'];
$liczba2 = $_GET['liczba2'];

$wynik1 = $liczba1 + $liczba2;
$wynik2 = $liczba1 - $liczba2;
$wynik3 = $liczba1 * $liczba2;

echo(" Suma wynosi: $wynik1");



?>
    
</body>
</html>

