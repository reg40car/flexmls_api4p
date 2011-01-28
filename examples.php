<?php

require_once("flexmlsAPI.php");

// set up the initial connection with our API key and secret
$api = new flexmlsAPI("api_key_goes_here", "api_secret_goes_here");

// set an ApplicationName which identifies us
$api->SetApplicationName("PHPAPIExamples/1.0");

// issue the request to authenticate with the API
$result = $api->Authenticate();
if ($result === false) {
	api_error_thrown($api);
}




/*
 * Retrieve information about the user and MLS the user belongs to
 */

echo "<b>GetSystemInfo</b><br>\n";
$result = $api->GetSystemInfo();
if ($result === false) {
	api_error_thrown($api);
}

echo "Name: {$result['Name']}<br>\n";
echo "MLS: {$result['Mls']}<br>\n";
//echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";



/*
 * Retrieve a list of the property types the MLS supports
 */

echo "<b>GetPropertyTypes</b><br>\n";
$result = $api->GetPropertyTypes();
if ($result === false) {
	api_error_thrown($api);
}

foreach ($result as $code => $name) {
	echo "Property Type '{$code}' stands for {$name}<br>\n";
}
//echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";



/*
 * Retrieve a list of the StandardFields you can retrieve along with some attributes of each
 */

echo "<b>GetStandardFields</b><br>\n";
$result = $api->GetStandardFields();
if ($result === false) {
	api_error_thrown($api);
}

foreach ($result as $name => $attributes) {
	echo "Field {$name} exists";
	if ($attributes['Searchable'] == true) {
		echo " and is searchable";
	}
	echo "<br>\n";
}
//echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";




/*
 * Retrieve some local market statistics
 */

echo "<b>GetMarketStats</b><br>\n";
$result = $api->GetMarketStats("price");
if ($result === false) {
	api_error_thrown($api);
}

echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";




/*
 * Retrieve IDX listings back from the API
 */

echo "<b>GetListings</b><br>\n";
$result = $api->GetListings();
if ($result === false) {
	api_error_thrown($api);
}

foreach ($result as $listing) {
	echo "MLS#: {$listing['StandardFields']['ListingId']} listed for {$listing['StandardFields']['ListPrice']}<br>\n";
}
//echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";





/*
 * Retrieve IDX listings back from the API using some parameters
 */

echo "<b>GetListings (with parameters)</b><br>\n";

$parameters = array(
		"_expand" => "Photos,OpenHouses",
		"_filter" => "City Eq 'Fargo' And PropertyType Eq 'A'",
		"_limit" => 3,
		"_pagination" => true
);

$result = $api->GetListings($parameters);
if ($result === false) {
	api_error_thrown($api);
}

echo "Total matching records found: {$api->last_count}<br>\n";
foreach ($result as $listing) {
	echo "MLS#: {$listing['StandardFields']['ListingId']} listed for {$listing['StandardFields']['ListPrice']}<br>\n";
}
//echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";





/*
 * Retrieve just my listings (based on the API key's user)
 */

echo "<b>GetMyListings</b><br>\n";
$result = $api->GetMyListings();
if ($result === false) {
	api_error_thrown($api);
}

foreach ($result as $listing) {
	echo "MLS#: {$listing['StandardFields']['ListingId']} listed for {$listing['StandardFields']['ListPrice']}<br>\n";
}
//echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";







/*
 * Retrieve a list of photos for a single property
 * @param string Id value returned from GetListings() or GetMyListings()
 */

echo "<b>GetListingPhotos</b><br>\n";
$result = $api->GetListingPhotos("20100728162705970089000000");
if ($result === false) {
	api_error_thrown($api);
}

$photo_count = 0;
foreach ($result as $photo) {
	$photo_count++;
	echo "Photo #{$photo_count} with caption '{$photo['Caption']}' has largest size available at {$photo['UriLarge']}<br>\n";
}
//echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";




/*
 * Retrieve a list of all of the current user's created IDX links within flexmls(r)
 */

echo "<b>GetIDXLinks</b><br>\n";
$result = $api->GetIDXLinks();
if ($result === false) {
	api_error_thrown($api);
}

foreach ($result as $link) {
	echo "Link with the name of '{$link['Name']}' goes to {$link['Uri']}<br>\n";
}
//echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";









/*
 * Retrieve this user's flexmls Connect preferences
 */

echo "<b>GetConnectPrefs</b><br>\n";
$result = $api->GetConnectPrefs();
if ($result === false) {
	api_error_thrown($api);
}

echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";







/*
 * Retrieve this user's contact list
 * @param string (optional) Name of the contact group to limit results to
 */

echo "<b>GetContacts</b><br>\n";
$result = $api->GetContacts();
if ($result === false) {
	api_error_thrown($api);
}

foreach ($result as $contact) {
	echo "Contact '{$contact['DisplayName']}' can be emailed at {$contact['PrimaryEmail']}<br>\n";
}
//echo "<pre>". print_r($result, true) ."</pre><br>\n";
echo "<br>\n\n";












/*
 * Create a new contact within flexmls(r)
 */

echo "<b>SendContact</b><br>\n";

$new_contact = array(
	"DisplayName" => "Example Contact",
	"PrimaryEmail" => "apiexample@flexmls.com",
	"PrimaryPhoneNumber" => "888-123-4567",
	"HomeStreetAddress" => "123 S. Main St",
	"HomeLocality" => "Fargo",
	"HomeRegion" => "ND",
	"HomePostalCode" => "58104",
	"Tag" => "Example Group"
);

//$result = $api->SendContact($new_contact);

if ($result === false) {
	echo "Creating contact failed.<br>\n";
	api_error_thrown($api);
}




function api_error_thrown($api) {
	echo "API Error Code: {$api->last_error_code}<br>\n";
	echo "API Error Message: {$api->last_error_mess}<br>\n";
	exit;
}
