<?php
foreach (App\Models\User::all() as $u) {
    echo $u->id . ' | ' . $u->name . ' | ' . $u->email . ' | ' . $u->role . PHP_EOL;
}