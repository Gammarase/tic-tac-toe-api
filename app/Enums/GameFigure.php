<?php

namespace App\Enums;


enum GameFigure: int
{
    case nought = 0;
    case cross = 1;

    public function oppositeFigure()
    {
        if ($this === self::nought) {
            return self::cross;
        } elseif ($this === self::cross) {
            return self::nought;
        }
    }
}
