<?php
session_start();
include("server.php");

// Check if the complaint ID is passed in the query string
if (isset($_GET['cmpid'])) {
    $cmpid = intval($_GET['cmpid']); // Sanitize input

    // Fetch the complaint status
    $query = "SELECT status FROM user_complaint WHERE cmpid = $cmpid";
    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status = intval($row['status']); // Ensure status is an integer
    } else {
        echo "<p>Complaint not found for ID: CMP" . str_pad($cmpid, 3, '0', STR_PAD_LEFT) . "</p>";
        exit();
    }
} else {
    echo "<p>Invalid complaint ID.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Complaint Progress</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .progress-container {
           
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            max-width: 600px;
            margin: 100px auto;
        }

        .progress-line {
            position: absolute;
            top: 33%;
            left: 10%;
            right: 10%;
            height: 4px;
            background-color: #ddd;
            z-index: -1;
            transform: translateY(-50%);
        }

        .progress-line.active {
            background-color: #007bff;
            transition: width 0.3s ease-in-out;
        }

        .progress-step {
            position: relative;
            width: 40px;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 50%;
            background-color: white;
            color: #ddd;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        .progress-step.complete {
            border-color: #007bff;
            background-color: #007bff;
            color: white;
        }

        .progress-step.active {
            border-color: #007bff;
            color: #007bff;
        }

        .description {
            margin-top: 10px;
            font-size: 14px;
            color: #6c757d;
            text-align: center;
        }

        .progress-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .back-button {
    width: 80px;
    padding: 10px;
    margin-top: 10px;
    margin-right: 90%;
    margin-left: 5px;
    background-color: #1f57ff;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.back-button:hover {
    background-color: #254eda;
}
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #3366ff;
    padding: 20px 20px;
    color: white;
}

header .header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

header .header-left h1 {
    font-size: 18px;
    margin: 0;
}

header .header-left span {
    font-size: 14px;
}

header .header-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

header .header-right i {
    font-size: 20px;
    position: relative;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
}
header .header-right i:hover {
    transform: translateY(-4px);
    color: #222831;
}

header .badge {
    position: absolute;
    top: -5px;
    right: -10px;
    background-color: red;
    color: white;
    font-size: 12px;
    border-radius: 50%;
    padding: 2px 5px;
}
  
    </style>
</head>
<body>
<header>
        <div class="header-left">
            <h1>TNEB Portal</h1>
        </div>
        <div class="header-right">
            <span><i class="fa-solid fa-phone-volume"></i>  94987 94987</span>
            <i class="fa-solid fa-bell" onclick="location.href='usernotification.php'"></i>
            <i class="fa-solid fa-user" onclick="location.href='userdetails.php'"></i>
            <i class="fa-solid fa-right-from-bracket" onclick="location.href='userlogin.html'"></i>
        </div>
    </header>
    <div class="button" onclick="location.href='usercomplaints.php'">
        <button type="submit" class="back-button">← Back</button>
    </div>
    <h1>Your  Complaint Progress</h1>
<div class="progress-container">
    
    <div class="progress-line"></div>
    <div class="progress-item">
        <div class="progress-step">1</div>
        <div class="description">Submit Complaint</div>
    </div>
    <div class="progress-item">
        <div class="progress-step">2</div>
        <div class="description">Complaint Accepted</div>
    </div>
    <div class="progress-item">
        <div class="progress-step">3</div>
        <div class="description">Processing</div>
    </div>
    <div class="progress-item">
        <div class="progress-step">4</div>
        <div class="description">Completed</div>
    </div>
</div>

<script>
    const steps = document.querySelectorAll('.progress-step');
    const progressLine = document.querySelector('.progress-line');

    // PHP embeds the status directly into JavaScript
    const status = <?php echo $status; ?>;

    function updateProgressBar(status) {
        steps.forEach((step, index) => {
            step.classList.remove('active', 'complete');

            if (status === 0 && index < 2) {
                step.classList.add('complete');
            }
            if (status === 1 && index < 3) {
                step.classList.add('complete');
            }
            if (status === 2) {
                step.classList.add('complete');
            }
        });

        // Adjust progress line width based on status
        let progressWidth = 0;
        if (status === 0) progressWidth = (3 / (steps.length - 1)) * 30;
        if (status === 1) progressWidth = (4 / (steps.length - 1)) * 45;
        if (status === 2) progressWidth = 82;

        progressLine.style.width = `${progressWidth}%`;
        progressLine.classList.add('active');
    }

    // Initialize progress bar
    updateProgressBar(status);
</script>
</body>
</html>
