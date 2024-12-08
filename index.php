<?php
require 'vendor/autoload.php';

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberToTimeZonesMapper;

function getPhoneNumberRegion($phoneNumber, $defaultRegion = 'US') {
    try {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $numberProto = $phoneUtil->parse($phoneNumber, $defaultRegion);

        // Get the region code (e.g., US, KE, IN)
        $regionCode = $phoneUtil->getRegionCodeForNumber($numberProto);

        // Get the time zone(s) for the phone number
        $timeZoneMapper = PhoneNumberToTimeZonesMapper::getInstance();
        $timeZones = $timeZoneMapper->getTimeZonesForNumber($numberProto);

        return [
            'region' => $regionCode,
            'timeZones' => $timeZones,
            'formatted' => $phoneUtil->format($numberProto, PhoneNumberFormat::INTERNATIONAL),
        ];
    } catch (\libphonenumber\NumberParseException $e) {
        return [
            'error' => 'Invalid phone number: ' . $e->getMessage()
        ];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $phone = $_POST['number']; 
   $result = getPhoneNumberRegion($phone);


if (isset($result['error'])) {
    echo $result['error'];
} else {
    echo "Region: " . $result['region'] . PHP_EOL;
    echo "Time Zones: " . implode(', ', $result['timeZones']) . PHP_EOL;
    echo "Formatted: " . $result['formatted'] . PHP_EOL;
}
}
?>
<form action="" method="post">
<input type="text" id="number" placeholder="andika namba"><br>
  <button type="submit">send</button>
</form>
