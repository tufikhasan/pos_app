<?php

function stringToInteger( $string ) {
    return (float) str_replace( ',', '', $string );
}