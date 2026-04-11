<?php

namespace App\Enums;

enum FileTypes: string
{
    case DOCUMENT = 'Document';
    case IMAGE = 'image';
    case AUDIO = 'Audio';
    case VIDEO = 'Video';
}
