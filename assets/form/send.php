<?php
//echo $_REQUEST['field'][1].'<br>';
//echo '<pre>'; print_r($_REQUEST); echo '</pre>'; exit;
/*$post = $_POST;*/


$ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
$ajax = true;
//we do not allow direct script access
if (!$ajax) {
	//redirect to contact form
	echo "Please enable Javascript";
	exit;
}






if( !empty($_REQUEST['field']['Email']['required']['Email']) ){



    $email = $_REQUEST['field']['Email']['required']['Email'];
    
    
    $link = mysql_connect('localhost', 'geteasyf_support', 'Support1985007');
    if (!$link) {
    
    
    
        $msg =  'Could not connect: ' . mysql_error();
        $result = array('success' => 0, 'msg' => $msg);
        echo json_encode($result);
        exit;
        
    }else{
    
        $db_selected = mysql_select_db('geteasyf_entreprise', $link);
        
        if (!$db_selected) {
        
            $msg =  'Could not connect: ' . mysql_error();
            $result = array('success' => 0, 'msg' => $msg);
            echo json_encode($result);
            exit;
            
        }else{
        
            $query = 'SELECT email FROM members WHERE email = "'.$email.'" ';
            $result = mysql_query($query); 
            $row = mysql_fetch_array($result); 
           
            $num_results = mysql_num_rows($result); 
             
            
            if ($num_results == 0){
                
                $name = !empty($_REQUEST['field']['Name']['required']['Name']) ? $_REQUEST['field']['Name']['required']['Name'] : 'CUSTOMER' ;
                $message = !empty($_REQUEST['field']['Message']['optional']['Message']) ? $_REQUEST['field']['Message']['optional']['Message'] : 'NEWLETTER' ;
                $date   = date('Y-m-d H:i:s');
                
                $query_insert = 'INSERT INTO members (name, email, message, created) VALUES("'.$name.'", "'.$email.'", "'.$message.'", "'.$date.'")';
                 $result = mysql_query($query_insert);
            }
           
            
        }
    }
    mysql_close($link);
    
    if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");
    
    $name     = !empty($_REQUEST['field']['Name']['required']['Name']) ? $_REQUEST['field']['Name']['required']['Name'] : 'CUSTOMER' ;
    $comments = !empty($_REQUEST['field']['Message']['optional']['Message']) ? $_REQUEST['field']['Message']['optional']['Message'] : 'NEWLETTER' ;
    
      /*  if(trim($email) == '') {
	    $msg =  'Email Was Empty';
	    $result = array('success' => 0, 'msg' => $msg);
            echo json_encode($result);
            exit;
	} else if(!isEmail($email)) {
	    $msg =  'Invalid Email Address';
	    $result = array('success' => 0, 'msg' => $msg);
            echo json_encode($result);
            exit;
	}*/
    
    	$address = "support@geteasyentreprise.com";
	$received_subject = 'You\'ve been contacted by ' . $name . '.';
	
	$received_body = "$name contacted you." . PHP_EOL . PHP_EOL;
	$received_content = "\"$comments\"" . PHP_EOL . PHP_EOL;
	$received_reply = "Reply to $name $email or call his/her phone: $phone";
	
	$message = wordwrap( $received_body . $received_content . $received_reply, 100 );
	
	$header = "From: $email" . PHP_EOL;
	$header .= "Reply-To: $email" . PHP_EOL;
    
    	if(mail($address, $received_subject, $message, $header)) {

	// Email has sent successfully, echo a success page.

	    $msg =  'Email Was sent successfully ';
            $result = array('success' => 1, 'msg' => $msg);
            echo json_encode($result);
            exit;

	} else {
	
	    $msg =  'Error: sending Email';
            $result = array('success' => 0, 'msg' => $msg);
            echo json_encode($result);
            exit;
	
	}
   

}else{

    $msg =  'Email Was Empty or Not Received';
    $result = array('success' => 0, 'msg' => $msg);
    echo json_encode($result);exit;
    
}












 
/*function isEmail($email) {
	return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
} 
exit;*/