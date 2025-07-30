<?php
$to = "gracian.anton@gmail.com"; // Replace with a real email
$subject = "WAMP Email Test";
$message = "If you’re reading this, it worked!";
$headers = "From: basil_anton@yahoo.ca";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ Mail sent successfully.";
} else {
    echo "❌ Mail sending failed.";
}
?>