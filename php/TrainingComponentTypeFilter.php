<?php

class TrainingComponentTypeFilter{
    
    public $IncludeTrainingPackage;
    public $Filter;
    
    function __construct($includeTrainingPackage, $filter) {
    $this->IncludeTrainingPackage = $includeTrainingPackage;
    $this->Filter = $filter;
    
    } 
    
    
}
?>
