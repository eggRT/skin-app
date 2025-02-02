<?php

namespace App\Services\CalculatePercent;

trait CalcPercentTrait {

    public function calcPercent($newValue, $lastValue) {
        $percentVal = 0;
        if($newValue != 0 && $lastValue != 0) {
            $percentVal = round((($newValue - $lastValue) / $lastValue * 100), 2);
        }

        return $percentVal;
    }

}