<?php
// Heading

// Text
$_['text_module']      = 'Módulos';
//$_['text_success']     = 'Éxito: ¡modificó el módulo de precio Spot de metales!';
$_['text_edit']        = 'Modificación del módulo de precios spot de metales';
$_['text_metals']      = array(0=>'Seleccionar tipo de metal', 1=>'Oro', 2=>'Plata', 3=>'Platino', 4=>'Paladio', 5=>'Cobre', 6=>'Rodio');
$_['text_fixed']        = 'Fijo';
$_['text_percentage']   = 'Porcentaje';

// Entry
//$_['entry_name']       = 'Nombre del módulo';
$_['entry_metal']      = 'Metal';
$_['entry_metals']     = 'Rieles';
$_['entry_status']     = 'Estado';
$_['entry_price_extra']        	= 'Precio adicional';

// Help
$_['help_metal']      = 'Elija si el producto es oro / plata / platino / paladio / rodio / cobre y obtenga el precio directo en vivo
';
$_['help_metals']     = 'Elija metales para mostrar los precios spot';
$_['help_price_extra']   = 'El Precio adicional se agrega al Precio base de dos maneras: 1- Fija: Precio base + Precio adicional 2- Porcentaje: Precio base + (Porcentaje del precio base)
';

// Error
$_['error_permission'] = 'Advertencia: ¡no tiene permiso para modificar el módulo de precios Spot Metals!';
$_['error_name']       = '¡El nombre del módulo debe tener entre 3 y 64 caracteres!';
$_['error_width']      = 'Ancho requerido!';
$_['error_height']     = 'Altura requerida!';
$_['error_weight']     = 'P¡El peso del producto debe ser mayor que 0!';
$_['error_price_extra']     = '¡El precio adicional debe ser mayor que 0!';
$_['error_price_extra_type']     = 'Tipo de precio extra es obligatorio, seleccione Fixed or Percentage!';