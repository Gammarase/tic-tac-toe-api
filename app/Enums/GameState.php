<?php
namespace App\Enums;

enum GameState: int
{
    case INITIALIZED = 0;
    case IN_PROCESS = 1;
    case FINISHED = 2;
}
