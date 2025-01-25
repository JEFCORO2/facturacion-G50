<?php

class api_genera_xml {
    //Funcion que realiza la creacion del XML de boletas y facturas

    function crear_xml_invoice($nombreXML, $emisor, $cliente, $comprobante, $detalle, $cuotas = null){
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = true;
        $doc->formatOutput = false;
        $doc->encoding = "utf-8";

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
            <ext:UBLExtensions>
                <ext:UBLExtension>
                    <ext:ExtensionContent />
                </ext:UBLExtension>
            </ext:UBLExtensions>
        <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
        <cbc:CustomizationID>2.0</cbc:CustomizationID>
        <cbc:ID>'. $comprobante['serie'] . '-' . $comprobante['correlativo'] .'</cbc:ID>
        <cbc:IssueDate>'.$comprobante['fecha_emision'].'</cbc:IssueDate>
        <cbc:IssueTime>'.$comprobante['hora'].'</cbc:IssueTime>
        <cbc:DueDate>'.$comprobante['fecha_vencimiento'].'</cbc:DueDate>
        <cbc:InvoiceTypeCode listID="0101">'.$comprobante['tipoDoc'].'</cbc:InvoiceTypeCode>
        <cbc:Note languageLocaleID="1000"><![CDATA['.$comprobante['total_texto'].']]></cbc:Note>
        <cbc:DocumentCurrencyCode>'.$comprobante['moneda'].'</cbc:DocumentCurrencyCode>
        <cac:Signature>
            <cbc:ID>'.$emisor['numeroDoc'].'</cbc:ID>
            <cbc:Note>'.$emisor['nombre_comercial'].'</cbc:Note>
            <cac:SignatoryParty>
                <cac:PartyIdentification>
                    <cbc:ID>'.$emisor['numeroDoc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><!['.$emisor['razon_social'].']]></cbc:Name>
                </cac:PartyName>
            </cac:SignatoryParty>
            <cac:DigitalSignatureAttachment>
                <cac:ExternalReference>
                    <cbc:URI>#SIGN-GREEN</cbc:URI>
                </cac:ExternalReference>
            </cac:DigitalSignatureAttachment>
        </cac:Signature>
        <cac:AccountingSupplierParty>
            <cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="'.$emisor['tipoDoc'].'">'.$emisor['numeroDoc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyName>
                    <cbc:Name><!['.$emisor['razon_social'].']]></cbc:Name>
                </cac:PartyName>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName><!['.$emisor['nombre_comercial'].']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                    <cbc:ID>'.$emisor['ubigeo'].'</cbc:ID>
                    <cbc:AddressTypeCode>0000</cbc:AddressTypeCode>
                    <cbc:CitySubdivisionName>NONE</cbc:CitySubdivisionName>
                    <cbc:CityName>'.$emisor['provincia'].'</cbc:CityName>
                    <cbc:CountrySubentity>'.$emisor['departamento'].'</cbc:CountrySubentity>
                    <cbc:District>'.$emisor['distrito'].'</cbc:District>
                    <cac:AddressLine>
                        <cbc:Line><!['.$emisor['direccion'].']]></cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>'.$emisor['pais'].'</cbc:IdentificationCode>
                    </cac:Country>
                    </cac:RegistrationAddress>
                </cac:PartyLegalEntity>
            </cac:Party>
        </cac:AccountingSupplierParty>
        <cac:AccountingCustomerParty>
            <cac:Party>
                <cac:PartyIdentification>
                    <cbc:ID schemeID="'.$cliente['tipoDoc'].'">'.$cliente['numeroDoc'].'</cbc:ID>
                </cac:PartyIdentification>
                <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA['.$cliente['razon_social'].']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                    <cac:AddressLine>
                        <cbc:Line><!['.$cliente['direccion'].']]></cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>'.$cliente['pais'].'</cbc:IdentificationCode>
                    </cac:Country>
                    </cac:RegistrationAddress>
                </cac:PartyLegalEntity>
            </cac:Party>
        </cac:AccountingCustomerParty>';

        if($comprobante['tipoDoc'] == '01'){
            if($comprobante['forma_pago'] == 'Contado'){//esto seri la etiqueta para la forma pago contado
                $xml = $xml . '<cac:PaymentTerms>
                                    <cbc:ID>FormaPago</cbc:ID>
                                    <cbc:PaymentMeansID>'. $comprobante['forma_pago'] .'</cbc:PaymentMeansID>
                               </cac:PaymentTerms>';
            }

            if($comprobante['forma_pago'] == 'credito'){//esto seri la etiqueta para la forma pago credito
                $xml = $xml . '<cac:PaymentTerms>
                                    <cbc:ID>FormaPago</cbc:ID>
                                    <cbc:PaymentMeansID>'. $comprobante['forma_pago'] .'</cbc:PaymentMeansID>
                                    <cbc:Amount currencyID="'. $comprobante['moneda'] .'">'. $comprobante['monto_pendiente'] .'</cbc:Amount>
                               </cac:PaymentTerms>';

                foreach ($cuotas as $key => $value) {
                    $xml = $xml . '<cac:PaymentTerms>
                                    <cbc:ID>FormaPago</cbc:ID>
                                    <cbc:PaymentMeansID>'.$value["cuota"].'</cbc:PaymentMeansID>
                                    <cbc:Amount currencyID="'. $comprobante['moneda'] .'">'. $comprobante['monto_pendiente'] .'</cbc:Amount>
                                    <cbc:PaymentDueDate>'. $value['fechaVenci'] .'</cbc:PaymentDueDate>
                               </cac:PaymentTerms>';
                }
            }
        }

        $xml = $xml . '<cac:TaxTotal>
            <cbc:TaxAmount currencyID="'. $comprobante['moneda'] .'">'. ($comprobante['igv'] + $comprobante['total_impbolsas']) .'</cbc:TaxAmount>';

        if($comprobante['total_opgravadas'] > 0){
            $xml = $xml . '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="'. $comprobante['moneda'] .'">'. $comprobante['total_opgravadas'] .'</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="'. $comprobante['moneda'] .'">'. $comprobante['igv'] .'</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                    <cbc:ID>1000</cbc:ID>
                                    <cbc:Name>IGV</cbc:Name>
                                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
        }

        if($comprobante['total_opexoneradas'] > 0){
            $xml = $xml . '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="'. $comprobante['moneda'] .'">'. $comprobante['total_opexoneradas'] .'</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="'. $comprobante['moneda'] .'">0.00</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                    <cbc:ID>1000</cbc:ID>
                                    <cbc:Name>IGV</cbc:Name>
                                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
        }

        if($comprobante['total_opeinafectas'] > 0){
            $xml = $xml . '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="'. $comprobante['moneda'] .'">'. $comprobante['total_opinafectas'] .'</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="'. $comprobante['moneda'] .'">0.00</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                    <cbc:ID>1000</cbc:ID>
                                    <cbc:Name>IGV</cbc:Name>
                                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
        }

        if($comprobante['total_opgratuitas_1'] > 0){
            $xml = $xml . '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="'. $comprobante['moneda'] .'">'. $comprobante['total_opgratuitas_1'] .'</cbc:TaxableAmount>
                            <cbc:TaxaAmount currencyID="'. $comprobante['moneda'] .'">'. $comprobante['total_opgratuitas_2'] .'</cbc:TaxaAmount>
                                <cbc:TaxAmount currencyID="'. $comprobante['moneda'] .'">0.00</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                    <cbc:ID>1000</cbc:ID>
                                    <cbc:Name>IGV</cbc:Name>
                                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
        }

        if($comprobante['total_impbolsas'] > 0){
            $xml = $xml . '<cac:TaxSubtotal>
                            <cbc:TaxableAmount currencyID="'. $comprobante['moneda'] .'">'. $comprobante['total_impbolsas'] .'</cbc:TaxableAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                    <cbc:ID>1000</cbc:ID>
                                    <cbc:Name>IGV</cbc:Name>
                                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
        }

        $total_antes_impuestos = $comprobante['total_opgravadas'] + $comprobante['total_opexoneradas'] + $comprobante['total_opinafectas'];//Subtotal

        $xml = $xml . '</cac:TaxTotal>
                        <cac:LegalMonetaryTotal>
                            <cbc:LineExtensionAmount currencyID="'.$comprobante['moneda'].'">'.$total_antes_impuestos.'</cbc:LineExtensionAmount>
                            <cbc:TaxInclusiveAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total'].'</cbc:TaxInclusiveAmount>
                            <cbc:PayableAmount currencyID="'.$comprobante['moneda'].'">'.$comprobante['total'].'</cbc:PayableAmount>
                        </cac:LegalMonetaryTotal>';


                
        
        '<cac:InvoiceLine>
            <cbc:ID>1</cbc:ID>
            <cbc:InvoicedQuantity unitCode="NIU">2</cbc:InvoicedQuantity>
            <cbc:LineExtensionAmount currencyID="PEN">200.00</cbc:LineExtensionAmount>
            <cac:PricingReference>
                <cac:AlternativeConditionPrice>
                    <cbc:PriceAmount currencyID="PEN">118.000000</cbc:PriceAmount>
                    <cbc:PriceTypeCode>01</cbc:PriceTypeCode>
                </cac:AlternativeConditionPrice>
            </cac:PricingReference>
            <cac:TaxTotal>
                <cbc:TaxAmount currencyID="PEN">36.00</cbc:TaxAmount>
                <cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="PEN">200.00</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="PEN">36.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                    <cbc:Percent>18</cbc:Percent>
                    <cbc:TaxExemptionReasonCode>10</cbc:TaxExemptionReasonCode>
                    <cac:TaxScheme>
                        <cbc:ID>1000</cbc:ID>
                        <cbc:Name>IGV</cbc:Name>
                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                    </cac:TaxCategory>
                </cac:TaxSubtotal>
            </cac:TaxTotal>
            <cac:Item>
                <cbc:Description><![CDATA[PROD 1]]></cbc:Description>
                <cac:SellersItemIdentification>
                    <cbc:ID>P001</cbc:ID>
                </cac:SellersItemIdentification>
            </cac:Item>
            <cac:Price>
                <cbc:PriceAmount currencyID="PEN">100.000000</cbc:PriceAmount>
            </cac:Price>
        </cac:InvoiceLine>
        </Invoice>';
    }
}