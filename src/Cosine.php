<?php

namespace App;

class Cosine
{
    public static function dotp($arr1, $arr2)
    {
        return array_sum(array_map(fn($a, $b) => $a * $b, $arr1, $arr2));
    }

    public static function similarity($id1, $id2)
    {
        return self::dotp($id1, $id2) / sqrt(self::dotp($id1, $id1) * self::dotp($id2, $id2));
    }
}

