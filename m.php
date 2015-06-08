<?php
  $post = $_POST;
  if($post['url']!==''){
    return;
  }
  $phone="";
  $email= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);
  $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);
  $messageForm = filter_var($_POST['message'], FILTER_SANITIZE_STRING,FILTER_FLAG_STRIP_LOW);

  //initialize $errors to nothing so we can populate it later.
  $errors = '';

  //Set the pattern for our regex check.
  $pattern='/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/';

  $email_to = 'liz@chefliz.ca';
  //$email_to = 'rick@calderonline.com';
  $subject = 'Emailed from Chef Liz contact form';
  if(!$email)
  {
    $errors .= 'Email is required!<br />';
  }
  if(!$name)
  {
    $errors .='Name is required!<br />';
  }
  if(!$messageForm)
  {
    $errors .='A Message is required!<br />';
  }
  if(!preg_match($pattern,$email))
  {
    $errors='Please enter a valid email address!<br />';
  }
  
  //Check to see if we had any errors and return them to the form if we did.
  //the $_SESSION variables is how we return the data to the form, to display errors and repopulate the form for the user.
  if($errors !='')
  {
    //print(json_encode(array("message"=>$errors, "status"=>"error")));
    print($errors);
    return false;
  } else {
    $message= 'Name: '.$name."\r\n";
    $message.=  'Email: '.$email."\r\n";
    $message.=  'Phone: '.$phone."\r\n";
    $message.=  'Message: '.$messageForm."\r\n";
    $headers = 'From: '.$email."\r\n".
    'Reply-To: '.$email."\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
  //We're going to put the email call in an if, that way if something goes wrong on the server side we get a message
    if(mail($email_to, $subject, $message, $headers))
    {
    //Clear the session variables and return a message.
    // you could use session_destroy() as well, like you did session_start above but if you were using sessions for anything else
    //that would be a bad thing.
    //If you wanted to go to a thank you page add the following without the // comment tag
    //print(json_encode(array("message" => "Thank you for your message, I'll get back to you soon!", "status"=>"success")));
      print("Thank you for your message, I'll get back to you soon!");
    } else {
    print(json_encode(array("message"=>"Something went wrong, please try again later", "status"=>"error")));
    }
  }
