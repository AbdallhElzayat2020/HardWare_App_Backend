<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LightStatus extends Enum
{
    const fall =   4;
    const occupied =   16;

    const unoccupied = 8;
    const occupied_warning = 32;
    const occupied_alerm = 4;




   const   MA_STAND = 0.5;
      const   MA_FALL = 0.8;
      const   MA_UNOCCUPIED= 0.5;
      const long_period_of_inactivity_flag = 2;
      const short_period_of_inactivity_flag = 1;
}

