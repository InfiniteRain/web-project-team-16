<?php require_once __DIR__ . '/../php/header.php'; ?>

<?php
echo Session::user()->first_name . ' ';
echo Session::user()->last_name . ' ';
echo Session::user()->id . '<br>';
?>

<?php

Session::user()->first_name = 'David';
Session::user()->last_name = 'Lossenko';
Session::user()->id = 0;
echo Session::user()->first_name . ' ';
echo Session::user()->last_name . ' ';
echo Session::user()->id . '<br>';
Session::user()->save();
echo Session::user()->first_name . ' ';
echo Session::user()->last_name . ' ';
echo Session::user()->id . '<br>';

?><br><br><br><?php

$newUser = new User();
$newUser->username = 'NEW';
$newUser->password = 'TBD';
$newUser->first_name = 'FIRSTNAME';
$newUser->last_name = 'LASTNAME';
$newUser->email = 'kek@kek.kek';
$newUser->type = 1;
$newUser->speciality = 1;

echo $newUser->first_name . ' ';
echo $newUser->last_name . ' ';
echo ($newUser->id === null ? 'null' : $newUser->id) . '<br>';
$newUser->save();
echo $newUser->first_name . ' ';
echo $newUser->last_name . ' ';
echo ($newUser->id === null ? 'null' : $newUser->id) . '<br>';

foreach (User::where('id > 1') as $m) {
    echo $m->username . '<br>';
}

?>