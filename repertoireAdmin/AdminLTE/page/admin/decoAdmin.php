<?php
require('../config/config.php');
session_destroy(); //supprime la session 
header('Location: ../../index.php');