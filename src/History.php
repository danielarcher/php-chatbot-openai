<?php

namespace App;

class History
{
    public static function save($session, $message, $role = 'user')
    {
        $fileName = __DIR__ . "/../storage/session_$session.json";
        if (!file_exists($fileName)) {
            file_put_contents($fileName, json_encode([]));
        }

        $history = json_decode(file_get_contents($fileName), true);
        $history[] = ['role' => $role, 'content' => $message];

        return file_put_contents($fileName, json_encode($history));
    }

    public static function get($session)
    {
        $fileName = __DIR__ . "/../storage/session_$session.json";
        if (!file_exists($fileName)) {
            file_put_contents($fileName, json_encode([]));
        }

        return json_decode(file_get_contents($fileName), true);
    }
}