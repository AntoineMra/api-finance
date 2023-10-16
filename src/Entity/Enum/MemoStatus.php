<?php

namespace App\Entity\Enum;

enum GoalStatus: string
{
    case Ongoing = 'ongoing';
    case Closed = 'closed';
}
