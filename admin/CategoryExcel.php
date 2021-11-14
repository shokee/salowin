<?php 
include_once 'includes/class.Main.php';
$dbf = new User();
//Upload Excel Product
if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='cateExport'){
    include("php/PHPExcel.php");
    $object = new PHPExcel();
    $object->setActiveSheetIndex(0);    
    $table_columns = array("Slno","Category Name");
    $column = 0;
  
    
    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $column++;
    }
  
    $excel_row = 2;
  
    $i=1;
    foreach($dbf->fetchOrder("product_catagory_1","","product_catagory_1_id ASC","","") as $Cateory){
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,$i++);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,$Cateory['product_catagory_1_name']);
      $excel_row++;					
    }
  
    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition:attachment;filename="grogodCategory.xls"');
    $object_writer->save('php://output');

    header("Location:catagory-1.php");exit;
  }

  if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='SubcateExport'){
    include("php/PHPExcel.php");
    $object = new PHPExcel();
    $object->setActiveSheetIndex(0);    
    $table_columns = array("Slno","Category Name","Sub Category");
    $column = 0;
  
    
    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $column++;
    }
  
    $excel_row = 2;
  
    $i=1;
    foreach($dbf->fetchOrder("product_catagory_2","","product_catagory_2_id ASC","","") as $Subcategory){
        $Category = $dbf->fetchSingle("product_catagory_1",'*',"product_catagory_1_id='$Subcategory[product_catagory_1_id]'");
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,$i++);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,$Category['product_catagory_1_name']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row,$Subcategory['product_catagory_2_name']);
      $excel_row++;					
    }
  
    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition:attachment;filename="grogodSubCategory.xls"');
    $object_writer->save('php://output');

    header("Location:catagory-1.php");exit;
  }
  if(isset($_REQUEST['operation']) && $_REQUEST['operation']=='SubcateExport2'){
    
    include("php/PHPExcel.php");
    $object = new PHPExcel();
    $object->setActiveSheetIndex(0);    
    $table_columns = array("Slno","Category Name","Sub Category","Sub Category2");
    $column = 0;
  
    
    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $column++;
    }
  
    $excel_row = 2;
  
    $i=1;
    foreach($dbf->fetchOrder("product_catagory_3","","product_catagory_3_id ASC","product_catagory_1_id,product_catagory_2_id,product_catagory_3_name,product_catagory_3_id,product_catagory_3_id","") as $Subcate2){
        $Category = $dbf->fetchSingle("product_catagory_1",'*',"product_catagory_1_id='$Subcate2[product_catagory_1_id]'");
        $Subcategory = $dbf->fetchSingle("product_catagory_2",'*',"product_catagory_2_id='$Subcate2[product_catagory_2_id]'");
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,$i++);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,$Category['product_catagory_1_name']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row,$Subcategory['product_catagory_2_name']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row,$Subcate2['product_catagory_3_name']);
      $excel_row++;					
    }
  
    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition:attachment;filename="grogodSubCategory2.xls"');
    $object_writer->save('php://output');

    header("Location:catagory-1.php");exit;
  }


  if(isset($_REQUEST['operation']) && $_REQUEST['operation']=="ProductsExports"){
     $condi=$_POST['condi'];
    include("php/PHPExcel.php");
    $object = new PHPExcel();
    $object->setActiveSheetIndex(0);    
    $table_columns = array("Slno.","Product Name","Category Name","Sub Category","Sub Category2","Brand","Weight","Measurement","Description");
    $column = 0;
  
    
    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $column++;
    }
  
    $excel_row = 2;
  
    $i=1;
    foreach($dbf->fetchOrder("product","product_id<>0".$condi,"product_id DESC","","") as $product){
     
      // Cate Lisst Get
      $Al_rel_cate=$dbf->fetchOrder("pro_rel_cat1","product_id='$product[product_id]'","","catagory1_id","");
        $Arrya_rel_cate=array();
        foreach($Al_rel_cate as $rel_cate){
          array_push($Arrya_rel_cate,$rel_cate['catagory1_id']);
        }
        if(!empty($Arrya_rel_cate)){
          $cate_id=implode(',',$Arrya_rel_cate);
          $All_Category=$dbf->fetchOrder("product_catagory_1","product_catagory_1_id IN($cate_id)","","product_catagory_1_name","");
          $Array_of_cate=array();
          foreach($All_Category as $category){
            array_push($Array_of_cate,$category['product_catagory_1_name']);
          }
          if(empty($Array_of_cate)){
            $cate_list="";
          }else{
            $cate_list=implode(',',$Array_of_cate);
          }

        }else{
          $cate_list="";
        }
      // Cate Lisst Get

        
      // Sub-Cate List Get
      $Al_rel_Subcate=$dbf->fetchOrder("pro_rel_cat2","product_id='$product[product_id]'","","catagory2_id","");
      $Arrya_rel_Subcate=array();
      foreach($Al_rel_Subcate as $rel_subcate){
        array_push($Arrya_rel_Subcate,$rel_subcate['catagory2_id']);
      }
      if(!empty($Arrya_rel_Subcate)){
        $Subcate_id=implode(',',$Arrya_rel_Subcate);
        $All_SubCategory=$dbf->fetchOrder("product_catagory_2","product_catagory_2_id IN($Subcate_id)","","product_catagory_2_name","");
        $Array_of_subcate=array();
        foreach($All_SubCategory as $Subcategory){
          array_push($Array_of_subcate,$Subcategory['product_catagory_2_name']);
        }
        if(empty($Array_of_subcate)){
          $Subcate_list="";
        }else{
          $Subcate_list=implode(',',$Array_of_subcate);
        }

      }else{
        $Subcate_list="";
      }
    // Sub-Cate2 Lisst Get

    // Sub-Cate List Get
    $Al_rel_Subcate2=$dbf->fetchOrder("pro_rel_cat3","product_id='$product[product_id]'","","catagory3_id","");
    $Arrya_rel_Subcate2=array();
    foreach($Al_rel_Subcate2 as $rel_subcate2){
      array_push($Arrya_rel_Subcate2,$rel_subcate2['catagory3_id']);
    }
    if(!empty($Arrya_rel_Subcate2)){
      $Subcate_id2=implode(',',$Arrya_rel_Subcate2);
      $All_SubCategory2=$dbf->fetchOrder("product_catagory_3","product_catagory_3_id IN($Subcate_id2)","","product_catagory_3_name","");
      $Array_of_subcate2=array();
      foreach($All_SubCategory2 as $Subcategory2){
        array_push($Array_of_subcate2,$Subcategory2['product_catagory_3_name']);
      }
      if(empty($Array_of_subcate2)){
        $Subcate_list2="";
      }else{
        $Subcate_list2=implode(',',$Array_of_subcate2);
      }

    }else{
      $Subcate_list2="";
    }
  // Sub-Cate2 Lisst Get

  //Brands
  $Brand = $dbf->fetchSingle("brands",'brands_name',"brands_id='$product[brands_id]'");
  //Brands
  
  //Weight & Measure
  $Al_rel_Varitions=$dbf->fetchOrder("price_varition","product_id='$product[product_id]'","","measure_id,units","");
  $Arry_of_measure=array();
  $Array_of_unit = array();
    foreach($Al_rel_Varitions as $Variton){
      array_push($Array_of_unit,$Variton['units']);
      $unit = $dbf->fetchSingle("units",'unit_name',"unit_id='$Variton[measure_id]'");
      array_push($Arry_of_measure,$unit['unit_name']);
    }
    if(empty($Array_of_unit)){
      $List_unit="";
    }else{
        $List_unit = implode(',',$Array_of_unit);
    }

    if(empty($Arry_of_measure)){
      $List_measure="";
    }else{
        $List_measure = implode(',',$Arry_of_measure);
    }
  //Weight & Measure


      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,$i++);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,$product['product_name']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row,$cate_list);
      $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row,$Subcate_list);
      $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row,$Subcate_list2);
      $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row,$Brand['brands_name']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row,$List_measure);
      $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row,$List_unit);
      $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row,$product['description']);
      $excel_row++;					
    }
  
    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition:attachment;filename="grogodProducts.xls"');
    $object_writer->save('php://output');

    header("Location:catagory-1.php");exit;
  }
  
  
  if(isset($_REQUEST['operation']) && $_REQUEST['operation']=="ProductsExportsAll"){
      
      $vendor_id=$_POST['vendor_id'];
    include("php/PHPExcel.php");
    $object = new PHPExcel();
    $object->setActiveSheetIndex(0);    
    $table_columns = array("","Product Id","Product Name","Category Name","Brand Name","Variation Id","MRP Price","Sale Price","Quantity");
    $column = 0;
    
    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $column++;
    }
  
    $excel_row = 2;
  
    $i=1;
    foreach($dbf->fetchOrder("product","product_id<>0","product_id DESC","","") as $product){
     
      // Cate Lisst Get
      $Al_rel_cate=$dbf->fetchOrder("pro_rel_cat1","product_id='$product[product_id]'","","catagory1_id","");
        $Arrya_rel_cate=array();
        foreach($Al_rel_cate as $rel_cate){
          array_push($Arrya_rel_cate,$rel_cate['catagory1_id']);
        }
        if(!empty($Arrya_rel_cate)){
          $cate_id=implode(',',$Arrya_rel_cate);
          $All_Category=$dbf->fetchOrder("product_catagory_1","product_catagory_1_id IN($cate_id)","","product_catagory_1_name","");
          $Array_of_cate=array();
          foreach($All_Category as $category){
            array_push($Array_of_cate,$category['product_catagory_1_name']);
          }
          if(empty($Array_of_cate)){
            $cate_list="";
          }else{
            $cate_list=implode(',',$Array_of_cate);
          }

        }else{
          $cate_list="";
        }
      // Cate Lisst Get


  //Brands
  $Brand = $dbf->fetchSingle("brands",'brands_name',"brands_id='$product[brands_id]'");
  //Brands
 
   //Brands
  $priceID = $dbf->fetchSingle("price_varition",'price_varition_id',"product_id='$product[product_id]'");
  //Brands 

   
  $vendorPriceId = $dbf->fetchSingle("variations_values",'*',"product_id='$product[product_id]' AND vendor_id='$vendor_id' AND price_variation_id='$priceID[price_varition_id]'");
  
if($vendorPriceId != ''){
    
    $mrp=$vendorPriceId['mrp_price'];
    $saleprice=$vendorPriceId['sale_price'];
    $quantity=$vendorPriceId['qty'];
    
}else{
    $mrp='';
    $saleprice='';
    $quantity='';
}
$nullField='';

      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,$nullField);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row,$product['product_id']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row,$product['product_name']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row,$cate_list);
      $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row,$Brand['brands_name']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row,$priceID['price_varition_id']);
      $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row,$mrp);
      $object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row,$saleprice);
      $object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row,$quantity);
      
      $excel_row++;					
    }
  
    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition:attachment;filename="salowinpro.xls"');
    $object_writer->save('php://output');

    header("Location:vendor_product_management.php");exit;
  }
?>