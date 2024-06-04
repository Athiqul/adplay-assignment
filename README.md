## PHP Developer Task: Implementing Real-Time Bidding (RTB) Banner Campaign Response

Objective:
You are tasked with developing a PHP script to handle bid requests and generate appropriate banner
campaign responses for Real-Time Bidding (RTB) scenarios. The script should utilize the provided bid
request JSON and campaign array to select and return the most suitable banner campaign based on the
bid request parameters.

### Requirements:

#### Bid Request Handling:

Parse the incoming bid request JSON to extract relevant parameters such as device information, geo-
location, ad format, bid floor, etc.
Ensure proper validation and error handling for the incoming bid request.

#### Campaign Selection:

Compare the bid request parameters with the campaigns available in the provided campaign array.
Select the campaign that best matches the bid request criteria. Consider factors such as device
compatibility, geographical targeting, bid floor, etc.
If multiple campaigns are eligible, choose the one with the highest bid price.

#### Banner Campaign Response:

Generate a JSON response containing the details of the selected campaign, including the campaign
name, advertiser, creative type, image URL, landing page URL, etc.
Ensure that the response complies with the RTB standards and includes necessary fields such as the bid
price, ad ID, creative ID, etc.

#### Testing:

Develop test cases to validate the functionality of the script.
Test the script with different sample bid requests to verify correct campaign selection and response
generation.

#### Additional Guidelines:

Utilize appropriate PHP libraries or frameworks for efficient JSON parsing and response generation.
Follow best practices for clean and maintainable code, including proper documentation and coding
standards.
Consider scalability and performance optimizations in the implementation.
Ensure security measures are implemented to prevent vulnerabilities such as injection attacks or data
leaks.

#### Deliverables:

PHP script implementing the bid request handling and banner campaign response generation.
Documentation detailing the script functionality, usage instructions, and any assumptions made during
development.
Test cases and results demonstrating the correctness of the implemented solution.
Resources:

Provided bid request JSON and campaign array.
RTB specifications and guidelines (referenced in the task description).
Development environment with PHP installed for testing and implementation.


### Test Case Output

#### Success Response Generation
 {
     "campaignName": "Test_Banner_13th-31st_march_Developer",
     "advertiser": "TestGP",
     "creativeType": "1",
     "creativeId": 167629,
     "id": "myB92gUhMdC5DUxndq3yAg",
     "imp": [
       {
         "id": "1",
         "bidid": "665eeb5b95c7f",
         "wmin": "3",
         "hmin": "2",
         "wmax": "3",
         "hmax": "2",
         "crid": 167629,
         "adomain": [
           "https://adplaytechnology.com/"
         ],
         "iurl": "https://adplaytechnology.com/",
         "nurl": "https://s3-ap-southeast-1.amazonaws.com/elasticbeanstalk-ap-southeast-1-5410920200615/CampaignFile/20240117030213/D300x250/e63324c6f222208f1dc66d3e2daaaf06.png",
         "mimes": [
           "image/jpeg"
         ],
         "bidprice": 0.4
       }
     ]
   }


#### If No Matching Campaing Data
{ "id": "myB92gUhMdC5DUxndq3yAg", "seatbid": [] }


#### If Error Format Json
{"errror":"Invalid Request"}