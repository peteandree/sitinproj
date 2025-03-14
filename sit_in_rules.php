<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sit-in Rules</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <div class="rules-card">
            <h2>Rules and Regulations</h2>
            <h3>University of Cebu</h3>
            <h4>COLLEGE OF INFORMATION & COMPUTER STUDIES</h4>
            <h3>LABORATORY RULES AND REGULATIONS</h3>

            <div class="rules-content">
                <p>To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
                <ol>
    <li>Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans, and other personal pieces of equipment must be switched off.</li>
    <li>Games are not allowed inside the lab. This includes computer-related games, card games, and other games that may disturb the operation of the lab.</li>
    <li>Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing software are strictly prohibited.</li>
    <li>Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</li>
    <li>Deleting computer files and changing the set-up of the computer is a major offense.</li>
    <li>Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</li>
    <li>Observe proper decorum while inside the laboratory.</li>
    <ol type="a">
        <li>Do not get inside the lab unless the instructor is present.</li>
        <li>All bags, knapsacks, and the likes must be deposited at the counter.</li>
        <li>Follow the seating arrangement of your instructor.</li>
        <li>At the end of class, all software programs must be closed.</li>
        <li>Return all chairs to their proper places after using.</li>
    </ol>
    <li>Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</li>
    <li>Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</li>
    <li>Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</li>
    <li>For serious offenses, the lab personnel may call the Civil Security Office (CSU) for assistance.</li>
    <li>Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant, or instructor immediately.</li>
</ol>
                
            <h3>DISCIPLINARY ACTION</h3>
                <ul>
                    <li>First Offense - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</li>
                    <li>Second and Subsequent Offenses - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</li>
                </ul>   
            </div>
        </div>
    </div>

    <style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Set full-height background for the page */
        body {
            display: flex;
            height: 100vh;
            background: #f4f4f4;
        }

        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        /* Sidebar title */
        .sidebar h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 22px;
        }

        /* Sidebar links */
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        /* Sidebar link hover effect */
        .sidebar a:hover {
            background: #34495e;
        }

        /* Main content area */
        .content {
            margin-left: 270px; /* Adjusted for sidebar width */
            padding: 20px;
            width: calc(100% - 270px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card container for rules */
        .rules-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 2000px; /* Increased width */
            max-height: 3000px; /* Increased height */
            text-align: center;
        }

        /* Content inside rules-card */
        .rules-content {
            text-align: left;
            max-height: 600px; /* Increased height */
            overflow-y: auto; /* Enables vertical scrolling if content is too long */
            padding-right: 10px;
        }

        /* List styles */
        .rules-content ol {
            padding-left: 20px;
        }

        /* Header styles */
        h2 {
            background: #2c3e50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            font-size: 24px; /* Increased font size */
        }

        h3, h4 {
            margin-top: 12px;
            font-size: 20px; /* Slightly larger */
        }

        /* Text content styles */
        p, ol li, ul li {
            font-size: 18px; /* Increased readability */
        }

        /* Indent for nested lists */
        ol[type="a"] {
            padding-left: 30px;
        }

    </style>
</body>
</html>
