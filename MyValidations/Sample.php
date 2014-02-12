<?php
namespace MyValidations;

use App\InitValidator;

class Sample extends InitValidator{

  public function __construct() {
    parent::__construct();

    // Set attributes
    $this->setFilePath('../public/files/sample.csv');
    $this->setImportPath('../public/results/');
    $this->setRedirectPath('index.php');
    $this->setSpreadSheetModelPath('../public/files/model.csv');
    $this->csv->setFileCSVName('sample');
    $this->csv->setDelimiter(';');
    //$this->csv->setEnclosure('"');

    // Columns's name
    $this->setColumns(array(
      'name'    => 'Name',
      'e-mail'  => 'E-mail',
      'cpf'     => 'CPF',
      'cnpj'    => 'CNPJ',
    ));

    // What validation I want using
    $this->setMapValidations(array(
      'cpf' => array('CPF'),
      'cnpj' => array('CNPJ'),
      'not null' => array(
        'Name',
        'E-mail',
      ),
      'not repeat' => array(
        'E-mail',
      ),
    ));
  }
}
