<?php

function SetHeaderCell($sheet,$c,$r){
    $sheet->getStyleByColumnAndRow($c,$r)->applyFromArray(
    array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
        'font'  => array(
            // 'bold'  => true,
            'color' => array('rgb' => 'FFFFFF'),
            // 'size'  => 15,
            // 'name'  => 'Verdana'
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4169E1')
        )
        ));
}

function SetNumberCell($sheet,$c,$r){
    $sheet->getStyleByColumnAndRow( $c, $r )->getNumberFormat()->setFormatCode('###,##0');
}

function SetNumberSumCell($sheet,$c,$r){
    $sheet->getStyleByColumnAndRow($c,$r)->applyFromArray(
    array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '90EE90')
        )
        ));
    SetNumberCell($sheet,$c,$r);
}


function SetCenterCell($sheet,$c,$r){
    $sheet->getStyleByColumnAndRow($c,$r)->applyFromArray(
    array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            )
        ));
    SetNumberCell($sheet,$c,$r);
}

function SetLeftCell($sheet,$c,$r){
    $sheet->getStyleByColumnAndRow($c,$r)->applyFromArray(
    array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            )
        ));
    SetNumberCell($sheet,$c,$r);
}


function SetCenterCellString($sheet,$c,$r){
    $sheet->getStyleByColumnAndRow($c,$r)->applyFromArray(
    array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            )
        ));
}
?>