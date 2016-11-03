<?php

require_once __DIR__ . '/Autoload/AutoLoad.php';

spl_autoload_register(['AutoLoad', 'register'], true, true);

