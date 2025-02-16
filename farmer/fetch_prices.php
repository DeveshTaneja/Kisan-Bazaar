

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = isset($_POST['location']) ? $_POST['location'] : 'Unknown Location';
    $crop = isset($_POST['crop']) ? $_POST['crop'] : 'Unknown Crop';

    // Load the CSV file
    $file = fopen("../MSP.csv", "r"); // Replace with actual CSV file path
    $found = false;

    while (($row = fgetcsv($file)) !== false) {
        // Assuming CSV structure: Crop Name in Column A (index 0), Location in Column B (index 1), Price in Column C (index 2)
        if (stripos($row[3], $crop) !== false && stripos($row[1], $location) !== false) {
            $price = $row[6]; // Price in Column C
            echo "Current Minimum Selling Price in $location for $crop is ₹$price <br>";
            $recommendedPrice = $row[9];
            echo "The Recommended Price is: ₹$recommendedPrice";
            $found = true;
            break;
        }
    }
    
    fclose($file);

    if (!$found) {
        echo "Current Minimum Selling Price in $location for $crop is ₹Not Available";
        echo "Recommended Price in $location for $crop is ₹Not Available";
    }
}
?>
