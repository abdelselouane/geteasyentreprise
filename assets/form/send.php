<?php
//echo $_REQUEST['field'][1].'<br>';
//echo '<pre>'; print_r($_REQUEST); echo '</pre>'; exit;
/*$post = $_POST;
$msg =  'email not received';
$result = array('success' => 0, 'msg' => $msg, 'post' => $_REQUEST['field']);
echo json_encode($result);exit;*/


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
    $link = mysql_connect('localhost', 'root', '');
    if (!$link) {
        //die('Could not connect: ' . mysql_error());
        $result = array('success' => 0);
        echo json_encode($result);
        exit;
    }else{
        $db_selected = mysql_select_db('geteasyf_entreprise', $link);
        if (!$db_selected) {
           // die ('Can\'t use Data Base : ' . mysql_error());
            $result = array('success' => 0);
            echo json_encode($result);
            exit;
        }else{
        
            $query = 'SELECT email FROM members WHERE email = "'.$email.'" ';
            $result = mysql_query($query); 
            $row = mysql_fetch_array($result); 
           
            $num_results = mysql_num_rows($result); 
             
            
            if ($num_results == 0){
                
                $name = !empty($_REQUEST['field']['Name']['required']['Name']) ? $_REQUEST['field']['Name']['required']['Name'] : '' ;
                $message = !empty($_REQUEST['field']['Message']['optional']['Message']) ? $_REQUEST['field']['Message']['optional']['Message'] : '' ;
                $date   = date('Y-m-d H:i:s');
                
                $query_insert = 'INSERT INTO members (name, email, message, created) VALUES("'.$name.'", "'.$email.'", "'.$message.'", "'.$date.'")';
                 $result = mysql_query($query_insert);
            }
           
            
        }
    }
    mysql_close($link);

}else{
    $msg =  'email not received';
    $result = array('success' => 0, 'msg' => $msg);
    echo json_encode($result);exit;
}
 
//exit;
require_once "config.php";

//we set up subject
$mail->Subject = isset($_REQUEST['email_subject']) ? $_REQUEST['email_subject'] : "Message from site";

//let's validate and return errors if required
$data = $mail->validateDynamic(array('required_error' => $requiredMessage, 'email_error' => $invalidEmail), $_REQUEST);

//let's make sure we have valid data
//if (!$data['errors'] && (!isset($_REQUEST['js']) || $_REQUEST['js'] != 1)) {
//$data['errors']['global'] = 'Javascript is required. Please try again';
//}

if ($data['errors']) {
	echo json_encode(array('errors' => $data['errors']));
	exit;
}

$html = '<body style="margin: 10px;">
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
  <h2>' . $mail->Subject . '</h2>
';

foreach ($data['fields'] as $label => $val) {
	$html .= '<p>' . $label . ': ' . $val . '</p>';
}

$html .= '</div></body>';

$mail->setup($html, $_REQUEST, array());

$result = array('success' => 1);
if (!$mail->Send()) {
	$result['success'] = 0;
}

echo json_encode($result);
exit;