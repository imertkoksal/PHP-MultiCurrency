<?php
require "CurrencySystem.php";

/* ECHO TEST */
$bank=new KoksalBankStyleCurrency__System();

$bank->setBalance("user",1578.75,"USD");

echo $bank->format($bank->getBalance("user","USD"),"USD","en").
" — ".
$bank->text($bank->getBalance("user","USD"),"USD","en");

echo "<br>";

$bank->setBalance("user",1578.75,"EUR");

echo $bank->format($bank->getBalance("user","EUR"),"EUR","de").
" — ".
$bank->text($bank->getBalance("user","EUR"),"EUR","de");

echo "<br>";

$bank->setBalance("user",1578.75,"JPY");

echo $bank->format($bank->getBalance("user","JPY"),"JPY","ja").
" — ".
$bank->text($bank->getBalance("user","JPY"),"JPY","ja");

echo "<br>";

$bank->setBalance("user",1578.75,"TRY");

echo $bank->format($bank->getBalance("user","TRY"),"TRY","tr").
" — ".
$bank->text($bank->getBalance("user","TRY"),"TRY","tr");
