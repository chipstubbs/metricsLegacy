<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    function format_telephone($phone){

        preg_match('/(\d{3})(\d{3})(\d{4})/', $phone, $matches);
        return "({$matches[1]}) {$matches[2]}-{$matches[3]}";

    }
    setlocale(LC_MONETARY, 'en_US');
    function money($number){
        if ( !is_numeric($number) ){
            $number = 0;
        }
        echo "&#36;".money_format('%!n', $number);
    }
