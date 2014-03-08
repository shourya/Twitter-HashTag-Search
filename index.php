<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php   
                /**
                * HASH TAG SEARCH for twitter : Simple PHP script to search and store twittes with some hashtag
                * PHP version 5.3.10
                * @author   Shourya Sohaney <shourya.sohaney@gmail.com>
                */
        
        
                //INCLUDING twitter authentication wrapper 
                //
                require_once('TwitterAPIExchange.php'); //spacial thanks to James Mallison <me@j7mbo.co.uk> for Twitter-API-PHP : Simple PHP wrapper for the v1.1 API  available at  http://github.com/j7mbo/twitter-api-php
               
                 
               
                
                //parameters for the Database Connection 
                //CHANGE AS PER YOUR DATABASE SETTINGS
                $host="localhost";
                $username="root";
                $password="root";
                $dbname="bookmyshow";
               
                // Create connection
                $db_connection=mysqli_connect($host,$username,$password,$dbname);

                // Check connection
                if (mysqli_connect_errno())
                  {
                  echo "Failed to connect to MySQL: " . mysqli_connect_error();
                  }

                  //twitter API authentication parameters generated from dev.twitter.com
                  //CHANGE AS PER YOUR TWITTER AUTHENTICATION SETTINGS
                  $settings = array(
                        'oauth_access_token' => "<YOUR ACCESS TOKEN>",
                        'oauth_access_token_secret' => "<TOKEN SECRET>",
                        'consumer_key' => "<CONSUMER KEY>",
                        'consumer_secret' => "<CONSUMER SECRET>"
                         );
                
                 $url = 'https://api.twitter.com/1.1/search/tweets.json';                           //search url
                 
                 
                 $searchKey="< SEARCH_KEY >";//Replace with the hashtag to search eg.bookmyshow
                 $getfield = '?q=#'.$searchkey.'&include_entities=false&result_type=recent&count=15';  //search parameters
                 $requestMethod = 'GET';                                                            //request method

                 $twitter = new TwitterAPIExchange($settings);                                      //new object of twitter wrapper 
                 $response =json_decode( $twitter->setGetfield($getfield)                           //decoding JSON data retrived from TWITTER
                                    ->buildOauth($url, $requestMethod)                              //passing parameters to twitter wrapper object 
                                    ->performRequest());


                  
                                /**********RUN ONCE TO CREATE TABLE******************
                                // Create table
                                 $sql="CREATE TABLE twitter_tags(tweet_id CHAR(18) primary key,text varchar(140) ,retweet_count INT(5),favourites_count INT(5),created_at CHAR(35)";

                                 // Execute query
                                if (mysqli_query($db_connection,$sql))
                                {
                                echo "Table twitter_tags created successfully";
                                }
                                 else
                                {
                                echo "Error creating table: " . mysqli_error($con);
                                }
                                /*****************************************************/                 
                 
                 
                        
               //  var_dump($response);
                //parsing through response and extracting required values such as id,text,retweeet count ,favourates count and created at 
               foreach($response->statuses as $tweet)
                {
               //insert values into the table twitter_tags  
                $sql='INSERT IGNORE INTO twitter_tags'.' set tweet_id="'.$tweet->id.'",text="'.$tweet->text.'",retweet_count='.$tweet->retweet_count.',favourites_count='.$tweet->user->favourites_count.',created_at="'.$tweet->created_at.'"';
                if (!mysqli_query($db_connection,$sql))
                {
                die('Error: ' . mysqli_error($db_connection));
                }; 
                   
               
                   
?>
    </body>
</html>

                  