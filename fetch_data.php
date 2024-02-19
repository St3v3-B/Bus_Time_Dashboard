<?php
$dom = new DOMDocument;
libxml_use_internal_errors(true);

// Veronderstelt dat de HTML-file geladen is
$dom->loadHTMLFile('https://9292.nl/doetinchem/bushalte-graafschap-college');

$xpath = new DOMXPath($dom);

// XPath queries voor relevante data 
$timeElements = $xpath->query("//td[@data-label='Tijd']");
$destinationElements = $xpath->query("//*[contains(@data-label, 'Richting')]");
$companyElements = $xpath->query("//*[contains(@data-label, 'Reisdetails')]");

$busData = [];

for ($i = 0; $i < $timeElements->length; $i++) {
    $destinationText = trim($destinationElements->item($i)->nodeValue);
    $companyText = trim($companyElements->item($i)->nodeValue);
    
    preg_match("/Bus\s(\d+)/", $destinationText, $matches);
    if (!empty($matches)) {
        $busNumber = $matches[1];

        // Verwijder "Bus", busnummer, en "Richting"
        $destination = preg_replace(["/Bus\s\d+\s/", "/ \s/"], "", $destinationText);

        $timeElement = $timeElements->item($i);
        $originalTimeElement = $xpath->query(".//del", $timeElement);
        $delayedTimeElement = $xpath->query(".//span[@class='orangetxt']", $timeElement);

        if ($originalTimeElement->length > 0) {
            $originalTime = strtotime(trim($originalTimeElement->item(0)->nodeValue));
            $delayedTime = $delayedTimeElement->length > 0 ? strtotime(trim($delayedTimeElement->item(0)->nodeValue)) : null;

            if ($delayedTime !== null) {
                $delay = round(($delayedTime - $originalTime) / 60); // Vertraging in minuten
                $delayText = "+$delay min";
                $originalTime = date('H:i', $originalTime);
                $time = "<span class='delay'>$originalTime $delayText</span>";
            } else {
                $originalTime = date('H:i', $originalTime);
                $time = $originalTime;
            }
        } else {
            $time = trim($timeElement->nodeValue);
        }

        // Pas filtering toe op basis van busnummer
        if (!isset($busData[$busNumber])) {
            $busData[$busNumber] = [];
        }

        if (count($busData[$busNumber]) < 3) { // Beperk tot 3 rijen per bus
            $busData[$busNumber][] = "<tr><td>{$time}</td><td>{$destination}</td><td>{$companyText}</td></tr>";
        }
    }
}

// Functie voor het weergeven van de tabel
function displayTable($busNumber, $rows) {
    echo "<div class='table-container'>";
    echo "<h2>Bus {$busNumber}</h2>";
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Tijd</th><th>Bestemming</th><th>Materieel</th></tr></thead>";
    echo "<tbody>";

    foreach ($rows as $row) {
        echo $row;
    }

    // Voeg lege rijen toe als er minder dan 3 zijn
    for ($i = count($rows); $i < 3; $i++) {
        echo "<tr><td colspan='3'>&nbsp;</td></tr>";
    }

    echo "</tbody></table>";
    echo "</div>";
}

// Weergeef tabellen voor elke bus
foreach ($busData as $busNumber => $dataRows) {
    displayTable($busNumber, $dataRows);
}
?>