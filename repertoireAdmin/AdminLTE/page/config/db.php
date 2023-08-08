<?php 
function connect_db(){// connection a la base 
        return new PDO('mysql:host=localhost;dbname=distribution','root','');
}