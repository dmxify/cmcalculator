<?php
      $apiKey = "07a99363f925400394ef0a27fabe34c9";
      $ip = $_SERVER['REMOTE_ADDR'];

      function track_get_decoded_location()
      {
          $decodedLocation = array();
          try {
              global $apiKey;
              global $ip;
              $location = get_geolocation($apiKey, $ip);
              $decodedLocation = json_decode($location, true);
          } catch (Exception $e) {
          }
          return $decodedLocation;
      }

      function track_insert_client_log()
      {
          try {
              global $apiKey;
              global $ip;
              $location = get_geolocation($apiKey, $ip);
              $decodedLocation = json_decode($location, true);


              $stmt = $mysqli->prepare("INSERT INTO client_log (ip, country_name, state_prov, city, latitude, longitude, country_flag, timezone_offset, time_current, time_current_unix) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
              $stmt->bind_param("sssssssiss", $decodedLocation['ip'], $decodedLocation['country_name'], $decodedLocation['state_prov'], $decodedLocation['city'], $decodedLocation['latitude'], $decodedLocation['longitude'], $decodedLocation['country_flag'], $decodedLocation['time_zone']['offset'], $decodedLocation['time_zone']['current_time'], $decodedLocation['time_zone']['current_time_unix']);
              $stmt->execute();
          } catch (Exception $e) {
          } finally {
              $stmt->close();
          }
      }

      function get_geolocation($apiKey, $ip, $lang = "en", $fields = "*", $excludes = "")
      {
          $url = "https://api.ipgeolocation.io/ipgeo?apiKey=".$apiKey."&ip=".$ip."&lang=".$lang."&fields=".$fields."&excludes=".$excludes;
          $cURL = curl_init();

          curl_setopt($cURL, CURLOPT_URL, $url);
          curl_setopt($cURL, CURLOPT_HTTPGET, true);
          curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Accept: application/json'
      ));
          return curl_exec($cURL);
      }
