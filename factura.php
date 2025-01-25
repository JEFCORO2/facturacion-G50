<?php

$emisor = array(
    "tipoDoc"   => "6",
    "numeroDoc"   => "20123456789",
    "razon_social"   => "CETI ORG",
    "nombre_comercial"   => "CETI",
    "direccion"   => "VIRTUAL",
    "ubigeo"   => "130101",
    "pais"   => "PE",
    "usuario_secundario"   => "MODDATOS",
    "claveUsuario"   => "MODDATOS",
);

$cliente = array (
    "tipoDoc" => "6",
    "numeroDoc" => "10123456789",
    "razon_social" => "CLIENTE CON RUC",
    "direccion" => "VIRTUAL",
    "pais" => "PE"
);

$comprobante = array (
    "tipoDoc" => "01",
    "serie" => "FABC",
    "correlativo" => 1,
    "fecha_emision" => date("Y-m-d"),
    "hora" => time("h:m:i"),
    "fecha_vencimiento" => date("Y-m-d"),
    "moneda" => "PEN",
    "total_opgravadas" => 0.00,
    "total_opexoneradas" => 0.00,
    "total_opinafectas" => 0.00,
    "total_impbolsas" => 0.00,

    //Le ponemos 1 y 2 para ver quien tiene igv y quien no

    "total_opgratuitas_1" => 0.00,
    "total_opgratuitas_2" => 0.00,
    "igv" => 0.00,
    "total" => 0.00,
    "total_texto" => "",
    "forma_pago" => "credito",
    "monto_pendiente" => 100.00
);

$cuotas = array(
    array(
        "cuota" => "Cuota001",
        "monto" => 50.00,
        "fechaVenci" => "2024-08-30"
    ),

    array(
        "cuota" => "Cuota002",
        "monto" => 50.00,
        "fechaVenci" => "2024-09-30"
    )
);

$detalle = array(
    array (
        "item" => 1,
        "codigo" => "PROD001",
        "descripcion" => "Samsung S21 Ultra",
        "cantidad" => 1,
        "precio_unitario" => 2000, //Precio incluye el impuest;o IGV
        "valor_unitario" => 1990, // Precio que no inculye impuestos
        "igv" => 100,
        "tipo_precio" => 01, //Cuando es oneroso(lucrar) : 01 , No oneroso(no lucrar) : 02
        "porcentaje_igv" => 18,
        "importe_total" => 2000,
        "valor_total" => 1990,
        "unidad" => "NIU",

        "bolsa_plastica" => "NO",
        "total_impuesto_bolsa" => 0.00,
        "tipo_afectacion_igv" => 10, //Grabaado:10 Exonerado:20 Inafecto:30
        "codigo_tipo_tributo" => "1000", //Catalogo numero 5 codigo tributo
        "tipo_tributo" => "VAT",
        "nombre_tributo" => "igv"
    ),

    array (
        "item" => 2,
        "codigo" => "PROD002",
        "descripcion" => "Libros de Derecho penal",
        "cantidad" => 2,
        "precio_unitario" => 100, //Precio incluye el impuest;o IGV
        "valor_unitario" => 100, // Precio que no inculye impuestos
        "igv" => 0.00,
        "tipo_precio" => 01, //Cuando es oneroso(lucrar) : 01 , No oneroso(no lucrar) : 02
        "porcentaje_igv" => 0.0,
        "importe_total" => 200,
        "valor_total" => 200,
        "unidad" => "NIU",

        "bolsa_plastica" => "NO",
        "total_impuesto_bolsa" => 0.00,
        "tipo_afectacion_igv" => 20, //Grabado:10 Exonerado:20 Inafecto:30
        "codigo_tipo_tributo" => "9997", //Catalogo numero 5 codigo tributo
        "tipo_tributo" => "VAT",
        "nombre_tributo" => "EXO"
    ),

    array (
        "item" => 3,
        "codigo" => "PROD003",
        "descripcion" => "MANZANA ROJA IMPORTADA",
        "cantidad" => 6,
        "precio_unitario" => 2, //Precio incluye el impuest;o IGV
        "valor_unitario" => 2, // Precio que no inculye impuestos
        "igv" => 0.00,
        "tipo_precio" => 01, //Cuando es oneroso(lucrar) : 01 , No oneroso(no lucrar) : 02
        "porcentaje_igv" => 0.00,
        "importe_total" => 12,
        "valor_total" => 12,
        "unidad" => "NIU",

        "bolsa_plastica" => "NO",
        "total_impuesto_bolsa" => 0.00,
        "tipo_afectacion_igv" => 30, //Grabaado:10 Exonerado:20 Inafecto:30
        "codigo_tipo_tributo" => "9998", //Catalogo numero 5 codigo tributo
        "tipo_tributo" => "FRE",
        "nombre_tributo" => "INA"
    ),
);

//Inicializar variables
$total_opgravadas = 0.00;
$total_opexoneradas = 0.00;
$total_opinafectas = 0.00;
$total_impbolsas = 0.00;
$total_opgratuito_1 = 0.00;
$total_opgratuito_2 = 0.00;
$igv = 0.00;
$total = 0.00;

foreach ($detalle as $key => $value) {
    if ($value["tipo_afectacion_igv" == 10]) {
        $total_opgravadas = $value["valor_total"];
    }
    if ($value["tipo_afectacion_igv" == 20]) {
        $total_opexoneradas = $value["valor_total"];
    }
    if ($value["tipo_afectacion_igv" == 30]) {
        $total_opinafectas = $value["valor_total"];
    }

    $igv += $value["igv"];
    $total_impbolsas += $value["total_impuesto_bolsa"];
    $total += $value["importe_total"] + $total_impbolsas;
}

$comprobante["total_opgravadas"] = $total_opgravadas;
$comprobante["total_opexoneradas"] = $total_opexoneradas;
$comprobante["total_opinafectas"] = $total_opinafectas;
$comprobante["total_impbolsas"] = $total_impbolsas;
$comprobante["total_opgratuitas_1"] = $total_opgratuito_1;
$comprobante["total_opgratuitas_2"] = $total_opgratuito_2;
$comprobante["igv"] = $igv; 
$comprobante["total"] = $total;

require_once("cantidad_en_letras.php");

$comprobante["total_texto"] = CantidadEnLetra($total);