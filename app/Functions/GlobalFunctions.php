<?php

function image_format($someValue)
{
    $uzanti = substr($someValue, -3);

    if($uzanti=='jpg'||$uzanti=='png'||$uzanti=='peg'){
        return '<img src="'.$someValue.'" class="img-fluid" />';
    }elseif($uzanti=='pdf'){
        return Metronic::getSVG("media/svg/icons/Files/Download.svg", "svg-icon-2x svg-icon-primary d-block");
    }else{
        return Metronic::getSVG("media/svg/icons/Files/Download.svg", "svg-icon-2x svg-icon-primary d-block");
    }
}

function is_image($someValue)
{
    $uzanti = substr($someValue, -3);

    if($uzanti=='jpg'||$uzanti=='png'||$uzanti=='peg'){
        return true;
    }
    
    return false;
}

function money_formatter($someValue)
{
        return number_format($someValue, 2, ",", ".");
}
function money_deformatter($someValue)
{
        return str_replace(",", ".", str_replace(".", "", $someValue));
}
function date_formatter($someValue)
{
        return date('d-m-Y', strtotime($someValue));
}
function date_deformatter($someValue)
{
        return date('Y-m-d', strtotime($someValue));
}

function vat_calculator($price, $vat, $type="from")
{
    if ($type == "from"){
        return $price/((100+$vat)/100);
    }else{
        return $price*((100+$vat)/100);
    }
}

function vat_price($price, $vat, $type="vatted")
{
    if ($type == "vatted"){
        $kdvsiz = $price/((100+$vat)/100);
        return $price-$kdvsiz;
    }else{
        $kdvli = $price*((100+$vat)/100);
        return $kdvli-$price;
    }
}

function error_formatter($validator)
{
    $error;
    foreach($validator->errors()->getMessages() as $validationErrors):
        if (is_array($validationErrors)) {
            foreach($validationErrors as $validationError):
                $error[] = $validationError;
            endforeach;
        } else {
            $error[] = $validationErrors;
        }
    endforeach;

    $error = implode('<br>', $error);

    return $error;
}


function notification_icons($someValue)
{

    switch ($someValue){
        case "product_stock":
            return "flaticon2-bell-alarm-symbol";
            break;
        case str_starts_with($someValue, 'depo'):
            return "flaticon2-graph-1";
            break;
        case "network":
            return "flaticon-network";
            break;

        default:
            return null;
    }

}
