<?php
namespace common\phpexcel;
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

/**
 * Excel操作类
 */
class Excel{

    private $_PHPExcel = null;

    /**
     * 构造函数
     */
    public function __construct() {
        if ($this->_PHPExcel === null) {
            $this->_PHPExcel = new \PHPExcel();
        }
    }
    public function init()
    {
    }

    /**
     * 获取Excel数据
     * @param type $path
     * @return type 数组
     */
    public static function getExcelData($path) {
        $PHPExcel = \PHPExcel_IOFactory::load($path);

        $sheet = $PHPExcel->getSheet(0); //读取第一個工作表

        $allColumn = $sheet->getHighestDataColumn(); //取得总列数 ABCDE
        $allRow = $sheet->getHighestDataRow(); //取得总行数 12345

        $data = array();
        for ($i = 1; $i <= $allRow; $i++) {
            for ($n = 'A'; $n <= $allColumn; $n++) {
                $point = $n . $i;
                $data[$i][] = $sheet->getCell($point)->getValue();
            }
        }
        return $data;
    }
    
    /**
     * 根据指定行获取Excel数据
     * @param string $path
     * @param int $sheet 
     * @param int $startRow
     * @param int $endRow
     * @return array
     */
    public static function getExcelDataRow($path,$sheet=0, $startRow = 1, $endRow = 0) {
        $PHPExcel = \PHPExcel_IOFactory::load($path);

        $sheet = $PHPExcel->getSheet(0); //读取第一個工作表

        $allColumn = $sheet->getHighestDataColumn(); //取得总列数 ABCDE
        $allRow = $sheet->getHighestDataRow(); //取得总行数 12345

        $data = array();
        for ($i = $startRow; $i <= ($endRow === 0 ? $allRow : $endRow); $i++) {
            for ($n = 'A'; $n <= $allColumn; $n++) {
                $point = $n . $i;
                $data[$i][] = $sheet->getCell($point)->getValue();
            }
        }
        return $data;
    }
    /**
     * 生成Excel
     * @param type $path
     * @param type $data
     * @param type $type
     */
    public static function createExcel($path, $headArr, $data, $format_fields=array(), $type = 'Excel2007', $download = false, $filename='download', $limit=10000) {

        $PHPExcel = new PHPExcel();
        
        $limit = $limit;    // 分表    每$limit个数据一个sheet表
        $nums = count($data);
        $total = ceil($nums/$limit);
		$fm_fields = !empty($format_fields) ? array_keys($format_fields) : array();
        for($s=0; $s < $total; $s++){
	        
        	$PHPExcel->createSheet($s);
        	$PHPExcel->setActiveSheetIndex($s);
	        $sheet = $PHPExcel->getActiveSheet();
	        $sheet->setTitle($filename.'_'.($s+1));

	        //设置表头
	        $key = 0; //ord("A");
	        
	        foreach($headArr as $v){
	        	$colum = PHPExcel_Cell::stringFromColumnIndex($key);
	        	$PHPExcel->setActiveSheetIndex($s) ->setCellValue($colum.'1', $v);
	        	$key += 1;
	        }
	        //设置内容
	        $i = 2;
	        foreach ($data as $k=>$value) {
	        	if(($i-1) <=  $limit){
		            $n = 0;//ord("A");
		            
		            if(is_array($value)){
		                foreach ($value as $fk=>$v) {
		                	if(!empty($fm_fields) && in_array($fk, $fm_fields)){
		                		if($format_fields[$fk][0] == 'date'){
		                			$v = date($format_fields[$fk][1], $v);
		                		}
		                		if($format_fields[$fk][0] == 'number_format'){
		                			$v = number_format($v/100,$format_fields[$fk][1]);
		                		}
		                		
		                	}
			                $colum = PHPExcel_Cell::stringFromColumnIndex($n);;
			                $point = $colum . $i;
			                $sheet->setCellValueExplicit($point, $v, PHPExcel_Cell_DataType::TYPE_STRING);
			                $n++;
		                }
		            }
		            $i++;
		            unset($data[$k]);
	        	}else{
	        		break;
	        	}
	        }
	        $Excel = \PHPExcel_IOFactory::createWriter($PHPExcel, $type);
	        
        }
        
        if($download){

        	// Redirect output to a client’s web browser (Excel5)
        	header('Content-Type: application/vnd.ms-excel');
        	header("Content-Type:application/octet-stream");
        	header("Content-Type:application/download");
        	header("Content-Disposition:attachment;filename=\"".$filename.".xls\"");
        	header('Cache-Control: max-age=0');
        	// If you're serving to IE 9, then the following may be needed
        	//header('Cache-Control: max-age=1');
        	
        	// If you're serving to IE over SSL, then the following may be needed
        	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        	header ('Pragma: public'); // HTTP/1.0
        	header("Content-Transfer-Encoding:binary");
        	header("Accept-Ranges: bytes");

        	$Excel->save('php://output');
        	exit;
        	
        }else{
           $Excel->save($path.'/'.$filename.".xls"); 
        }
    }

}

?>