<?php
session_start();
//calculating the time (in minutes) slept

$n = $_POST['mytimes'];
$m = $_POST['mytimew'];
$s = $_POST['rating'];
$delimiter = ":";

$username = $_SESSION['username'];

$partS = explode($delimiter, $n);
$hourS = intval($partS[0]);
$minuteS = intval($partS[1]);
$timeS = $hourS * 60 + $minuteS;

$partW = explode($delimiter, $m);
$hourW = intval($partW[0]);
$minuteW = intval($partW[1]);
$timeW = $hourW * 60 + $minuteW;

$time = $timeW - $timeS;

if ($time < 0) {
    $time = 1440 + $time;
}

$ctime=$time;

//echo $time;

//getting the system date
$currentDate = date('Y-m-d');

$db['host'] = "localhost";
$db['user'] = "root";
$db['pass'] = "daria";
$db['name'] = "sleeptacker";

$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    //echo "Connected successfully";
}

$query = "INSERT INTO sleep (username, date, time, quality) values ('$username', '$currentDate', '$time', '$s')";
$conn->query($query);

$lastSevenDays = array();
for ($i = 6; $i >= 0; $i--) {
    $lastSevenDays[] = date("Y-m-d", strtotime("-$i days"));
}
//echo $currentDate;
//echo $lastSevenDays[0];
//echo $lastSevenDays[6];


$query = "SELECT date, time, quality FROM sleep WHERE username = '$username' AND date BETWEEN '$lastSevenDays[0]' AND '$lastSevenDays[5]'";
$result = mysqli_query($conn, $query);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

$timeArray = array_fill(0, 6, 0);
$qualityArray = array_fill(0, 6, 0);

foreach ($rows as $row) {
    $date = $row['date'];
    $time = intval($row['time']);
    $quality = floatval($row['quality']);
    
    $index = array_search($date, $lastSevenDays);
    if ($index !== false) {
        $timeArray[$index] += $time;
        $qualityArray[$index] += $quality;
    }
}

$timeArray[6]=$ctime; 
$qualityArray[6]=$s;


//print_r($timeArray);
//print_r($qualityArray);

echo '<script>';
echo 'localStorage.setItem("times", ' . json_encode($timeArray) . ');';
echo '</script>';

echo '<script>';
echo 'localStorage.setItem("stars", ' . json_encode($qualityArray) . ');';
echo '</script>';


echo '<a href="userpage.html">Click here to continue</a>';
?>
