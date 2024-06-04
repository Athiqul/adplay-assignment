<?php 
 
 //Provided Bid Request in JSON
 $bidJson=require_once('./bidRequest.php');

 $bid = parseJsonData($bidJson);

// Provided campaign array
$campaignsJson = require_once('./campaigns.php');

$campaigns=parseJsonData($campaignsJson);
//die();

//var_dump($bidJson);
// Function to parse the JSON data Handling Invalid Json Request
function parseJsonData($data) {
    $decodedData = json_decode($data,true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      //throw new Exception("Invalid Request Format: " . json_last_error_msg());
      return json_encode(['error' => 'Invalid Request']);
    }
    return $decodedData;
  }


  // Function to select the best campaign
  function campainSelection($bidRequest, $campaigns) {
    $selectedCampaign = null;
    $highestBid = 0;
  
    // Loop through each campaign
    foreach ($campaigns as $campaign) {
  
        // Check dimensions
    $imp=$bidRequest['imp'][0];
    list($campaignWidth, $campaignHeight) = explode('x', $campaign['dimension']);
    if ($campaignWidth != $imp['banner']['w'] || $campaignHeight != $imp['banner']['h']) {
       continue;
    }
      // Check device compatibility
      $deviceOs = $bidRequest['device']['os'];
      $allowedOs = explode(',', $campaign['hs_os']);
      
      if (!in_array(ucfirst($deviceOs), $allowedOs)) {
        continue;
      }
  
      // Check geographical targeting (if specified in campaign)
      
      if ($campaign['country'] !== '' && $campaign['country'] !== $bidRequest['device']['geo']['country']) {
        continue;
      }

      //Check city
      if ($campaign['city']!== '' && $campaign['city']!== $bidRequest['device']['geo']['city']) {
        continue;
      }
  
      // Check bid floor
      $bidFloor = $bidRequest['imp'][0]['bidfloor'];
      if ($campaign['price'] < $bidFloor) {
        continue;
      }
  
      // Check if a higher bid is found
      if ($campaign['price'] > $highestBid) {
        $selectedCampaign = $campaign;
        $highestBid = $campaign['price'];
      }
    }
  
    return $selectedCampaign;
   }


  // Function to generate the response JSON
  function generateResponseJson($selectedCampaign,$bidRequest) {
    if (!$selectedCampaign) {
      return null;
    }
  
    $response = [
       'campaignName'=>$selectedCampaign['campaignname'],
       'advertiser'=>$selectedCampaign['advertiser'],
       'creativeType'=>$selectedCampaign['creative_type'],
       'creativeId'=>$selectedCampaign['creative_id'],
      'id' => $bidRequest['id'],
      'imp' => [
        [
          'id' => $bidRequest['imp'][0]['id'],
          'bidid' => uniqid(), // Generate unique bid ID
          'wmin' => $selectedCampaign['dimension'][0], // Minimum width
          'hmin' => $selectedCampaign['dimension'][1], // Minimum height,
          'wmax' => $selectedCampaign['dimension'][0], // Maximum width
          'hmax' => $selectedCampaign['dimension'][1], // Maximum height
          'crid' => $selectedCampaign['creative_id'], // Creative ID
          'adomain' => [
            $selectedCampaign['tld'] // Advertiser domain
          ],
          'iurl' => $selectedCampaign['url'], // Landing page URL
          'nurl' => $selectedCampaign['image_url'], // Image URL
          'mimes' => ['image/jpeg'], // MIME type (assuming image format)
          'bidprice' => $selectedCampaign['price'] // Bid price
        ]
      ]
    ];
  
    return json_encode($response);
   }


  try {
   
  
    // Select the best campaign based on bid request criteria
    $selectedCampaign = campainSelection($bid, $campaigns);
  
    // Generate the response JSON
    $responseJson = generateResponseJson($selectedCampaign,$bid);
  
    if ($responseJson) {
      header('Content-Type: application/json');
      echo $responseJson;
    } else {
      // No matching campaign found, send an empty seatBid response
      echo json_encode(['id' => $bid['id'], 'seatbid' => []],JSON_PRETTY_PRINT);
    }
  } catch (Exception $e) {
    // Handle any errors that occurred
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()],JSON_PRETTY_PRINT);
  }


?>