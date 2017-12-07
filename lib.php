<?php

function validate ($value) {
    foreach ($value as $item) {
        if (!is_numeric($item)) {
            return false;
        }
        if ($item <= 0) {
            return false;
        }
        if ($item > 1000) {
            return false;
        }

    }

    return true;
}