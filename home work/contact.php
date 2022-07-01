<!DOCTYPE html>
<html lang="en">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./style.css">
    <title>Contact us </title>
    <link rel="stylesheet" type="text/css" href="css/sponsor-additional.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>

<body>

    <div class="neonText">
        <h1> Contact us</h1>
    </div>

    <nav class="stickynav">
        <ul class="menu">
            <li><a href="#">Home</a></li>
            <li><a href="#">Events</a></li>
            <li><a href="#which_games">Which Games</a></li>
            <li><a href="#">Sponsors</a></li>
            <li><a herf="#">Contact us</a></li>
            <li><a href="https://discord.gg/q3hEvcKSf9">Discord</a></li>
        </ul>
    </nav>

    <div class="form24">
        <p>Send a message and we will get back to you within 24 hours.</p>

        <!-- Begin page header-->




        <?php
              if(isset($_POST['submit'])){
              $name = htmlspecialchars(stripslashes(trim($_POST['name'])));
              $subject = htmlspecialchars(stripslashes(trim($_POST['subject'])));
              $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
              $message = htmlspecialchars(stripslashes(trim($_POST['message'])));
              if(!preg_match("/^[A-Za-z .'-]+$/", $name)){
              $name_error = 'Invalid name';
              }
              if(!preg_match("/^[A-Za-z .'-]+$/", $subject)){
              $subject_error = 'Invalid subject';
              }
              if(!preg_match("/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/", $email)){
              $email_error = 'Invalid email';
              }
              if(strlen($message) === 0){
              $message_error = 'Your message should not be empty';
              }
              }
              ?>

        <form class='contact-from' role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
            method="POST">

            <input type="text" name="name" class="contact-form input" placeholder="Your Name">
            <p><?php if(isset($name_error)) echo $name_error; ?></p>

            <input type="text" name="subject" class="contact-form input" placeholder="subject">
            <p><?php if(isset($subject_error)) echo $subject_error; ?></p>

            <input type="text" name="email" class="contact-form input" placeholder="E-mail">
            <p><?php if(isset($email_error)) echo $email_error; ?></p>

            <textarea name="message" class="contact-form textarea" rows="5" placeholder="Message"></textarea>

            <p><?php if(isset($message_error)) echo $message_error; ?></p>
            <button type="submit" name="submit" value="Submit">button</button>
            <?php 
                if(isset($_POST['submit']) && !isset($name_error) && !isset($subject_error) && !isset($email_error) && !isset($message_error)){
                $to = 'ianh241203@gmail.com'; // edit here
                $body = " Name: $name\n E-mail: $email\n Message:\n $message";
                if(mail($to, $subject, $body)){
                echo '<p style="color: blue">Message sent</p>';
                }else{
                echo '<p>Error occurred, please try again later</p>';
                }
                }
                //tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt
            ?>

        </form>

        <div class="infosResults"></div>
        <script src="https://www.gstatic.com/firebasejs/7.17.1/firebase.js"></script>
        <script src="app.js"></script>
    </div>






    <!-- For the popup -->


    <script src="css/sponsor.js"> </script>

</body>

</html>