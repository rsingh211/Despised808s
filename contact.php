<?php 
$title = 'Contact'; 
include 'header.php'; 
?>
<section class="contact-section">
  <h1>Contact Us</h1>
  <p>Have questions or need support? Reach out to us!</p>
  <form action="contact.php" method="post">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="message" placeholder="Your Message" required></textarea>
    <button type="submit" class="btn">Send Message</button>
  </form>
</section>
<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    // Handle sending email (e.g., with PHPMailer or mail())
    echo "<p>Thank you for contacting us, $name! We'll get back to you soon.</p>";
}
include 'footer.php'; 
?>
