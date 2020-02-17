<?php

function checkIfArrAndIfEmpty($data)
{
    if (gettype($data) == 'array' && count($data) == 0) {
        return true;
    }

    return false;
}
