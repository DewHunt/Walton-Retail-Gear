<?php
function changeDateFormate($date,$date_format){
    return \Carbon\Carbon::createFromFormat('d/m/Y', $date)->format($date_format);    
}
   
function productImagePath($image_name)
{
    return public_path('images/products/'.$image_name);
}