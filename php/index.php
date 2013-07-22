<?php 

require_once 'clsWSSEAuth.php'; 
require_once 'TrainingComponentSearchRequest.php'; 
require_once 'TrainingComponentTypeFilter.php'; 
?>
<html>
 <head>
    <title>Test TGA Soap Call</title>
 </head>
<body>

    <?php
ini_set("soap.wsdl_cache_enabled", "1");

$soap_url = "https://ws.sandbox.training.gov.au/Deewr.Tga.Webservices/TrainingComponentService.svc?wsdl";

////////////////////////////////////////////////////////////////////
$username = 'INSERT YOUR USERNAME HERE';
$password = 'AND YOUR PASSWORD HERE';
////////////////////////////////////////////////////////////////////

$strWSSENS = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";


if(isset($_REQUEST['btnSearch'])){

 try
    {        

$objSoapVarUser = new SoapVar($username, XSD_STRING, NULL, $strWSSENS, NULL, $strWSSENS);
$objSoapVarPass = new SoapVar($password, XSD_STRING, NULL, $strWSSENS, NULL, $strWSSENS);

$objWSSEAuth = new clsWSSEAuth($objSoapVarUser, $objSoapVarPass);
$objSoapVarWSSEAuth = new SoapVar($objWSSEAuth, SOAP_ENC_OBJECT, NULL, $strWSSENS, 'UsernameToken', $strWSSENS);

$objWSSEToken = new clsWSSEToken($objSoapVarWSSEAuth);

$objSoapVarWSSEToken = new SoapVar($objWSSEToken, SOAP_ENC_OBJECT, NULL, $strWSSENS, 'UsernameToken', $strWSSENS);

$objSoapVarHeaderVal=new SoapVar($objSoapVarWSSEToken, SOAP_ENC_OBJECT, NULL, $strWSSENS, 'Security', $strWSSENS);

$objSoapVarWSSEHeader = new SoapHeader($strWSSENS, 'Security', $objSoapVarHeaderVal);

////////////////////////////////////////////////////////////////////////////////////////////////////////////
// if you are accessing the SOAP feed via a proxy you may need to include some more details...
$arrOptions = array('trace' => 1, 'style'=> SOAP_DOCUMENT,
    'proxy_host'     => "HOST ADDRESS HERE, SOMETHING LIKE: proxy.someplace.edu.au",
    'proxy_port'     => 8080,
    'proxy_login'    => "LOGIN NAME",
    'proxy_password' => "PASSWORD"    

    );
/////////////////////////////////////////////////////////////////////////////////////////////////////////////    
    

$objClient = new SoapClient($soap_url, $arrOptions);
$objClient->__setSoapHeaders(array($objSoapVarWSSEHeader));


try{
//get the Search
if(isset($objClient)){
    //$strMethod  ="Search";
    //$filter = new TrainingComponentTypeFilter("true","Training and Education");
    //$TrainingComponentSearchRequest = new TrainingComponentSearchRequest ("true","false","false",$filter);
        
    //$objResponse = $objClient->__soapCall($strMethod, $requestPayloadString);
    //print_r($options);
    //print_r($objClient->Search($TrainingComponentSearchRequest));
    
    // try this instead
    $objSimpleSearch = new SimpleSearch("Training and Education");
		$result = $objClient->Search(array("request"=>$objSimpleSearch));
		print_r($result);
 }
}
 
    catch(SoapFault $ex)
    {
       echo "There was an Error: " . var_dump($ex);
       //var_dump($ex);
    }   
    
    
    

}
    catch(SoapFault $ex)
    {
       echo $ex->getMessage();
       //var_dump($ex);
    }
}

echo  "<h3>
        Calling TGA Soap Service
    </h3>";

//echo "Server Time: " . print_r($objClient) ."<p></p>";
//echo "Server Time: " . print_r($objClient->GetServerTime()->GetServerTimeResult);
 
?>
   
    <form action=""  method="post">
        
        <p>Enter a Unit to Search</p>
        <input type="text" name="strsearch" value="<?php echo $_REQUEST['strsearch'] ?>"/>
        <input type="submit" value="search" name="btnSearch"/>
        
        
    </form>
    
    

</body>
</html>
