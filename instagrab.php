<?php
/**
* Plugin Name: Instagrab
* Description: A lightweight plugin for showcasing an Instagram feed
* Author: Sean Ryan
* Version: 1.0
**/

  function instagrab_create_api_call($api_url) {
    $curl_conn = curl_init();
    curl_setopt($curl_conn, CURLOPT_URL, $api_url); //Instagram API URL to fetch from
    curl_setopt($curl_conn, CURLOPT_RETURNTRANSFER, 1); // return dont print results
    curl_setopt($curl_conn, CURLOPT_TIMEOUT, 20);
    $json_response = curl_exec($curl_conn);
    curl_close($curl_conn); //Close connection
    return json_decode($json_response); //decode and return data
  }

  function instagrab_get_feed() {
    $content .='<div class="instagrab-stream">';
    $auth_token = get_option('instagrab_auth_token', '');
    $username = get_option('instagrab_username', '');
    $image_response = instagrab_create_api_call("https://api.instagram.com/v1/users/self/media/recent?access_token=" . $auth_token);

    foreach ($image_response->data as $post) {
      $content .= '<img class="grab" src="' . $post->images->thumbnail-> url .'" /></a>';
    }

    $content .="</div>";

    return $content;
  }


  add_shortcode('instagrab-feed', 'instagrab_get_feed');

  function instagrab_admin_menu_option() {
    add_menu_page('Instagrab Settings', 'Instagrab Settings', 'manage_options', 'instagrab-admin-menu', 'instagrab_settings_page', '', 10);
  }

  add_action('admin_menu', 'instagrab_admin_menu_option');

  function instagrab_settings_page() {
    if(array_key_exists('submit_auth_token_update',$_POST)) {
        update_option('instagrab_auth_token',$_POST['auth_token']);
        update_option('instagrab_username',$_POST['ig_username']);

        ?>
        <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"><strong>Settings have been updated.</strong></div>
        <?php
    }

    $auth_token = get_option('instagrab_auth_token','none');
    $ig_username = get_option('instagrab_username','none');
    ?>
    <div class="wrap">
        <h2>Instagrab Settings</h2>
        <form method="post" action="">
        <label for="auth_token">Instagram Access Token</label>
        <textarea class="large-text" name="auth_token"><?php print $auth_token; ?></textarea>
        <label for="ig_username">Instagram Username</label>
        <textarea class="large-text" name="ig_username"><?php print $ig_username; ?></textarea>
        <input type="submit" name="submit_auth_token_update" class="button button-primary" value="Update Settings"/>
      </form>
    </div>
    <?php
  }

 ?>
