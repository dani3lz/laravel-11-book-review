<?php 

namespace App\Enums;

enum Filters : string
{
    case POPULAR = 'popular';
    case HIGHEST = 'highest';
    case LOWEST = 'lowest';
    case LATEST = '';
}