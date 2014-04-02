<?php
namespace App;

class InitValidator {
  private $columns;
  private $map_validations;
  private $import_path;
  private $file_path;
  //private $converterXSLtoArray;
  private $redirect_path;
  private $spread_sheet_model_path;

  protected $validator;

  public $csv;
  public $message;

  public function __construct() {
    // Classes's instances
    $this->validator = new Validator();
    //$this->converterXSLtoArray = new ConvertXLStoArray();
    $this->csv = new CSV();
    $this->message = new Message();

    // Set attributes
    $this->file_path = NULL;
    $this->import_path = NULL;
    $this->redirect_path = NULL;
    $this->spread_sheet_model_path = NULL;

    $this->setColumns(array(
      'name'    => 'Name',
      'e-mail'  => 'E-mail',
    ));

    $this->setMapValidations(array(
      'not null' => array(
        'Name',
        'E-mail',
      ),
      'not repeat' => array(
        'E-mail',
      ),
    ));
  }

  public function start() {

    // Some problem with upload i can treat here with IF
    if (empty($this->file_path)) {
      $this->message->set('Houve algum problema com a sua conexão e o arquivo no pode ser enviado. Por favor tente novamente.', 'warning', $this->getRedirectPath());
      die;
    }

    //$this->converterXSLtoArray->setFileId($this->getFileId());
    // Converter XLS to Array
    //$this->converterXSLtoArray->convert();
    // Saving data in an array
    //$data = $this->converterXSLtoArray->getData();

    $this->csv->getCSVFile($this->csv->openCSVFile($this->getFilePath()));
    $data = $this->csv->getData();

    // Starting Validation
    $errors = $this->validator->startValidation($this->getColumns(), $data,
    $this->getMapValidations(), $this->getRedirectPath(),
    $this->getSpreadSheetModelPath());

    // CSV configuration
    $this->csv->setDestinationPath($this->getImportPath());

    // Another csv file to report some problems could having happened
    // case not return null
    if (!empty($errors)) {
      $csvErrorsContent = $this->csv->prepareContentCSV($errors);
      $prefix_error_name = 'errors_';
      $this->message->set(sprintf('Some problems were found, please check this <a href="%s">report</a>.',
        $this->csv->writeFile($csvErrorsContent, $prefix_error_name)), 'warning');
    }

    $csvContent = $this->csv->prepareContentCSV($data);
    // CSV ready to import in the migrate Module
    $this->message->set(sprintf('After validation was generated a new file with only the correct lines. <a href="%s">Download</a>.', $this->csv->writeFile($csvContent)), 'status');
  }

  public function setColumns($columns) {
    $this->columns = $columns;
  }

  public function getColumns() {
    return $this->columns;
  }

  public function setFilePath($file_path) {
    $this->file_path = $file_path;
  }

  public function getFilePath() {
    return $this->file_path;
  }

  public function getFileCSVName() {
    return $this->csv->getFileCSVName();
  }

  public function getMapValidations() {
    return $this->map_validations;
  }

  public function setMapValidations(array $map_validations) {
    $this->map_validations = $map_validations;
  }

  public function getHeadlineAllowed() {
    foreach ($this->map_validations as $key => $headers) {
      $allowed = array();
      foreach ($headers as $key => $header) {
        $allowed[] = $header;
      }
      $alloweds[] = implode(', ', $allowed);
    }
    return implode(', ', $alloweds);
  }

  public function getHeadlineColumns() {
    foreach ($this->map_validations as $key => $headers) {
      foreach ($headers as $key => $header) {
        $allowed[] = $header;
      }
    }
    return $allowed;
  }

  public function setImportPath($path) {
    $this->import_path = $path;
  }

  public function getImportPath() {
    return $this->import_path;
  }

  public function setRedirectPath($path) {
    $this->redirect_path = $path;
  }

  public function getRedirectPath() {
    return $this->redirect_path;
  }

  public function setSpreadSheetModelPath($path) {
    $this->spread_sheet_model_path = $path;
  }

  public function getSpreadSheetModelPath() {
    return $this->spread_sheet_model_path;
  }
}

