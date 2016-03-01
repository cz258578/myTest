<?php
namespace common\phpexcel;

use common\phpexcel\Excel;

/**
 * Excel操作类扩展类
 */
class SelfExcel extends Excel{

    /**
     * 获取Excel数据(所有工作表)
     * @param type $path
     * @return type 数组
     */
    public static function getExcelData($path) {
        $PHPExcel = \PHPExcel_IOFactory::load($path);
        $sheetCountNum = $PHPExcel->getSheetCount();

        $result = [];

        for ($sheetCountNumKey = 0; $sheetCountNumKey < $sheetCountNum; $sheetCountNumKey++) {
            $sheet = $PHPExcel->getSheet($sheetCountNumKey);
            if ($sheet->getSheetState() == 'visible') {
                $allColumn = $sheet->getHighestDataColumn(); //取得总列数 ABCDE
                $allRow = $sheet->getHighestDataRow(); //取得总行数 12345
                $data = array();
                for ($i = 1; $i <= $allRow; $i++) {
                    for ($n = 'A'; $n <= $allColumn; $n++) {
                        $point = $n . $i;
                        $data[$i][] = $sheet->getCell($point)->getValue();
                    }
                }
                $result[$i] = $data;
            } else {
                unset($sheet);
            }
        }
        return $result;
    }
}
