<?php

use App\Models\User;

foreach (User::all() as $u) {
    echo $u->id.' | '.$u->name.' | '.$u->email.' | '.$u->role.PHP_EOL;
}
