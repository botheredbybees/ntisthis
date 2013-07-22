<?php

class TrainingComponentSearchRequest  {
    

public $SearchTitle;
public $IncludeDeleted;
public $SearchCode;
public $TrainingComponentTypes;

function __construct($searchTitle, $includeDeleted ,$searchCode, $trainingComponentTypes) {
    $this->SearchTitle = $searchTitle;
    $this->IncludeDeleted = $includeDeleted;
    $this->SearchCode = $searchCode;
    $this->TrainingComponentTypes = $trainingComponentTypes;
    
    } 
}



?>
