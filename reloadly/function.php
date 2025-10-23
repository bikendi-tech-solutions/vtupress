<?php
function getReloadlyToken($clientId, $clientSecret, $isSandbox = true) {
    // Sandbox auth host uses auth.reloadly.com (same for sandbox/live but endpoints vary — check your dashboard)
    $url = "https://auth.reloadly.com/oauth/token";
    $post = json_encode([
        "client_id" => $clientId,
        "client_secret" => $clientSecret,
        "grant_type" => "client_credentials",
        "audience" => "https://topups.reloadly.com"
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    $resp = curl_exec($ch);
    if (curl_errno($ch)) { throw new Exception(curl_error($ch)); }
    curl_close($ch);

    $json = json_decode($resp, true);
    if (!empty($json['access_token'])) return $json['access_token'];
    throw new Exception("Could not get token: " . $resp);
}


// returns operator JSON or throws
function autoDetectOperator($token, $phoneNumberLocal, $isoCountryCode) {
    // Example: phoneNumberLocal = "8012345678", isoCountryCode = "NG" or "GH"
    $url = "https://topups.reloadly.com/operators/auto-detect?phoneNumber={$phoneNumberLocal}&countryCode={$isoCountryCode}";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$token}",
        "Accept: application/com.reloadly.topups-v1+json"
    ]);
    $resp = curl_exec($ch);
    if (curl_errno($ch)) { throw new Exception(curl_error($ch)); }
    curl_close($ch);
    $j = json_decode($resp, true);
    if (empty($j)) throw new Exception("Auto-detect failed: {$resp}");
    return $j; // contains operatorId, operatorName, etc.
}


function makeAirtimeTopup($token, $operatorId, $countryIso, $localNumber, $amount, $currency = null) {
    // amount as string/number. If you want local currency, you can set currency = "NGN" or "GHS" (see docs).
    $url = "https://topups.reloadly.com/topups";
    $body = [
        "operatorId" => (int)$operatorId,
        "amount" => (string)$amount,
        "recipientPhone" => [
            "countryCode" => $countryIso, // e.g. "NG" or "GH"
            "number" => $localNumber      // local part, e.g. "8012345678"
        ]
    ];
    if ($currency) $body['currency'] = $currency;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$token}",
        "Accept: application/com.reloadly.topups-v1+json",
        "Content-Type: application/json"
    ]);
    $resp = curl_exec($ch);
    if (curl_errno($ch)) throw new Exception(curl_error($ch));
    curl_close($ch);
    return json_decode($resp, true);
}

function listOperatorProducts($token, $operatorId) {
    $url = "https://topups.reloadly.com/operators/{$operatorId}/products";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$token}",
        "Accept: application/com.reloadly.topups-v1+json"
    ]);
    $resp = curl_exec($ch);
    if (curl_errno($ch)) throw new Exception(curl_error($ch));
    curl_close($ch);
    return json_decode($resp, true); // array of products: productId, name, type (DATA/AIRTIME), etc.
}

// purchase a data product by productId
function buyDataProduct($token, $productId, $countryIso, $localNumber) {
    $url = "https://topups.reloadly.com/topups";
    $body = [
        "productId" => (int)$productId,
        "recipientPhone" => [
            "countryCode" => $countryIso,
            "number" => $localNumber
        ]
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$token}",
        "Accept: application/com.reloadly.topups-v1+json",
        "Content-Type: application/json"
    ]);
    $resp = curl_exec($ch);
    if (curl_errno($ch)) throw new Exception(curl_error($ch));
    curl_close($ch);
    return json_decode($resp, true);
}

$clientId = 'YOUR_CLIENT_ID';
$clientSecret = 'YOUR_CLIENT_SECRET';

if(isset($_GET["airtime"])){
    $country = $_GET["country"] ?? "NG";
    $phone = $_GET["phone"] ?? "8012345678";

    $token = getReloadlyToken($clientId, $clientSecret);

    $Operator = autoDetectOperator($token, $phone, $country);

    if(!isset($Operator['operatorId'])){
        print_r($Operator);
        die("Could not detect operator for {$phone} in {$country}");
    }

    $res = makeAirtimeTopup($token, $Operator['operatorId'], $country, $phone, 200, 'NGN');
    print_r($res);
    
}
elseif(isset($_GET["data"])){
    $country = $_GET["country"] ?? "NG";
    $phone = $_GET["phone"] ?? "8012345678";

    $token = getReloadlyToken($clientId, $clientSecret);

    $Operator = autoDetectOperator($token, $phone, $country);

    if(!isset($Operator['operatorId'])){
        print_r($Operator);
        die("Could not detect operator for {$phone} in {$country}");
    }

    $products = listOperatorProducts($token, $Operator['operatorId']);
    if(empty($products)){
        die("No products found for operator {$Operator['operatorName']}");
    }

    // Just pick the first DATA product we find
    $dataProductId = null;
    foreach($products as $prod){
        if(isset($prod['type']) && strtoupper($prod['type']) === 'DATA'){
            echo "DATA product: {$prod['productId']} - {$prod['name']} - {$prod['amount']} {$prod['currency']}\n";
            $dataProductId = $prod['productId'];
            break;
        }
    }
    if(!$dataProductId){
        die("No DATA products found for operator {$Operator['operatorName']}");
    }

    $res = buyDataProduct($token, $dataProductId, $country, $phone);
    print_r($res);
}