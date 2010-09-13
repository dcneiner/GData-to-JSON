<?php
  if (!isset($column_name_mapping)) {
    $column_name_mapping = array(
      'yourname' => 'name',
      'emailaddress' => 'email',
      'talktitle' => 'title',
      'pleaseprovideanabstractofyourproposedtalk' => 'abstract',
      'bio' => 'bio',
      'urltoahigh-resphoto' => 'photo_url'
    );
  }

  require_once 'events-importer/base.php';
  
  if (php_sapi_name() !== 'cli') {
    $mode = "cli";
  } else {
    $mode = "web";
  }
  
  if(isset($worksheet) && $mode == "web") {
    $command = "export";
  } else {
    # Pull in command line arguments
    list($filename, $command, $spreadsheet, $worksheet) = $argv;    
  }
  
  # Initialize Spreadsheet Service
  $spreadsheetService = new Zend_Gdata_Spreadsheets($client);
  
  switch ($command) {
    case 'list':
      $feed = $spreadsheetService->getSpreadsheetFeed();
      
      echo "\nAvailible Spreadsheets:\n";
      
      foreach ($feed->entries as $sheet) {
        echo $sheet->title->text . ": ";
        echo get_key($sheet->id->text) . "\n";
      }
      
      echo "\n\n";
      
      break;
    case 'list-worksheets':
      if (!$spreadsheet) die("You must supply a spreadsheet key to use this command. \n");
      
      $query = new Zend_Gdata_Spreadsheets_DocumentQuery();
      $query->setSpreadsheetKey($spreadsheet);
      $feed = $spreadsheetService->getWorksheetFeed($query);
      
      echo "\nAvailible Worksheets:\n";
      
      foreach ($feed->entries as $index => $sheet) {
        echo 'Sheet' . $index . ': ' . get_key($sheet->id->text) . "\n";
      }
      
      echo "\n\n";
    
    case 'export':
      if (!$spreadsheet) die("You must supply a spreadsheet key to use this command. \n");
      if (!$worksheet) die("You must supply a worksheet id to use this command. \n");
      
      $query = new Zend_Gdata_Spreadsheets_ListQuery();
      $query->setSpreadsheetKey($spreadsheet);
      $query->setWorksheetId($worksheet);
      
      $feed = $spreadsheetService->getListFeed($query);
      
      $data = array();
      
      foreach($feed->entries as $entry){
        $line = array();
        $fields = $entry->getCustom();
        foreach($fields as $field) {
          $key = get_column_name($field->getColumnName());
          if(!$key) continue;
          $line[$key] = utf8_encode($field->text);
        }
        
        add_slugs(&$line);
        
        $data[] = $line;
      }
      if ($mode = "cli") {
        echo json_encode($data) . "\n";
      } else {
        return $data;
      }
    
      break;
    
    default:
      # code...
      break;
  }
    
  function get_key( $url ){
    $url = explode('/', $url);
    return array_pop($url);
  }
  
  function get_column_name( $name ) {
    global $column_name_mapping;
    
    if (isset($column_name_mapping[$name])) {
      return $column_name_mapping[$name];
    }
    return "";
  }
  
  function add_slugs( &$speaker ) {
    $name = $speaker['name'];
    $name = preg_replace('|[^-_a-z0-9]|','-', strtolower($name));
    $name = preg_replace('|-+|','-', $name);
    $speaker['name-slug'] = $name;
  }
  
  
?>