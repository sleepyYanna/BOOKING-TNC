<?php
session_start();
include 'bkconnection.php';

$isSuccess = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $ownerName = $_POST["ownerName"];
    $petName = $_POST["petName"];
    $breed = $_POST["breed"];
    $mobileNum = $_POST["mobileNum"];
    $reserveDate = $_POST["reserveDate"];
    $reserveTime = $_POST["reserveTime"];
    $groomer = $_POST["groomer"];
    $note = $_POST["note"];

    $stmt = $conn->prepare("INSERT INTO petgrooming_data 
        (ownerName, petName, breed, mobileNum, reserveDate, reserveTime, groomer, note) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("ssssssss", $ownerName, $petName, $breed, $mobileNum, $reserveDate, $reserveTime, $groomer, $note);

    if ($stmt->execute()) {
        $isSuccess = true;
        $_SESSION['isSuccess'] = true; // Set session variable to show success pop-up
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Grooming Appointment</title>
    <link rel="stylesheet" href="bookingstyle.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="img/IMG_5589.PNG" alt="Tub N' Cup Logo" width="800px" height="123.62px">
            <nav>
                <a href="#">Home</a>
                <a href="#">Reserve a Schedule</a>
                <a href="#">Grab a drink</a>
            </nav>
        </header>

        <div class="booking-form">
            <div class="tab">
                <img src="img/paw.png" alt="Paw Icon">
            </div>
            <h2>BOOKING FORM</h2>
            <hr style="border: 3px solid #E4C267; margin: 20px 0;">

            <form action="#" method="post">
                <label for="ownerName">NAME OF OWNER:</label>
                <input type="text" id="ownerName" name="ownerName" required>

                <label for="petName">PET NAME:</label>
                <input type="text" id="petName" name="petName" required>

                <label for="breed">BREED:</label>
                <input type="text" id="breed" name="breed" required>

                <label for="mobileNum">MOBILE NUMBER:</label>
                <input type="tel" id="mobileNum" name="mobileNum" required>
                <label for="reserveDate">RESERVE DATE:</label>
                <input type="date" id="reserveDate" name="reserveDate" required onchange="adjustTimeOptions()">

                <label for="reserveTime">RESERVE TIME:</label>
                <select id="reserveTime" name="reserveTime" required>
                    <option value="">Select a time</option>
                </select>

                <label for="groomer">GROOMER PREFERENCE:</label>
                <select id="groomer" name="groomer">
                    <option value="any">Anyone</option>
                    <option value="Aries">Aries</option>
                    <option value="Dennis">Dennis</option>
                    <option value="Swabe">Swabe</option>
                    <option value="Mark">Mark</option>
                </select>

                <label for="note">NOTE:</label>
                <textarea id="note" name="note" rows="3"></textarea>

                <div class="button-container">
                    <button type="submit" class="submit-btn" name="submit">SUBMIT FORM</button>
                </div>
            </form>
        </div>

        <!-- Display pop-up if form was successfully submitted -->
        <?php if (isset($_SESSION['isSuccess']) && $_SESSION['isSuccess']): ?>
            <div id="popup-container" class="popup-container active">
                <div class="popup-box">
                    <p>You have successfully booked an appointment for your pet!</p>
                    <p>Please anticipate a call from us for confirmation.</p>
                    <button class="close-btn" onclick="closePopup()">I understand</button>
                </div>
            </div>
            <?php unset($_SESSION['isSuccess']); ?> <!-- Unset session variable after showing the popup -->
        <?php endif; ?>

        <footer>
            <p>Pet Owners and Non-Pet Owners are welcome!</p>
        </footer>
    </div>

    <script src="booking.js"></script>
</body>
</html>
