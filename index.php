<?php

    /* 
    Plugin Name: wpStockvault
    Description: Allows you to display the latest images of a specific user on stockvault.net
    Author: Stephan Gerlach
    Version: 1.1.2
    Author URI: http://www.computersniffer.com
    */  

    add_action('admin_menu', 'wpStockvault_menu');
    function wpStockvault_menu() {
        add_menu_page('wpStockvault', 'wpStockvault', 'administrator', 'wpStockvault_guide', 'wpStockvault_guide');
    }  
    function wpStockvault_guide() {
        echo '<div class="wrap">';
        ?>
        <div style="float: right; margin-top: 25px;">
        <p>Do you like this Plugin? <br />Say thank you via a small donation. <br />Thanks.</p>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="LBS4WWDZRC3HW">
<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form>


        </div>
        <?php
        echo '<h2>wpStockvault Guide</h2>';
        echo '<p>This plugin makes use of the Stockvault.net API - check <a href="http://stockvault.net/api" target="_blank">here</a> for details</p>';
        echo '<p>Before you can publish any images you will need the userid of the desired user. (hint: can be found in the url if you look at someones profile ie 95049)</p>';
        echo '<h4>Basic</h4>';
        echo '<p>A basic display of images would look like this <code>[stockvault userid="95049"]</code></p>';
        echo '<h4>Custom style and class</h4>';
        echo '<p>You can also add some custom class and style attributes to each image like this <code>[stockvault userid="95049" class="classname" style="padding: 5px;"]</code></p>';
        echo '<h4>Control the link target</h4>';
        echo '<p>You can also control the link target of the image <code>[stockvault userid="95049" target="_blank"]</code></p>';
        echo '<h4>Add some html</h4>';
        echo '<p>You can also add some html before and after each image like this.<code>[stockvault userid="95049" wrap_before="&lt;li&gt;" wrap_after="&lt;/li&gt;"]</code></p>';
        echo '<p>If the wrap before tag has any attributes please use \' instead of " <code>[stockvault userid="95049" wrap_before="&lt;li class=\'imagelist\'&gt;" wrap_after="&lt;/li&gt;"]</code></p>';
        echo '<h4>Add some more html</h4>';
        echo '<p>You can also add some more html before and after all of the images<code>[stockvault userid="95049" start="&lt;div&gt;" end="&lt;/div&gt;"]</code></p>';
        
        echo '<h3>Advanced API</h3>';
        echo '<p>The following options require an API key.</p>';
        echo '<h4>Search</h4>';
        echo '<p>In order to do a search you need to set the type, a search term and the API key
                <br /><code>[stockvault type="search" query="airplane" key="YOURKEY GOES HERE" target="_blank"]</code></p>';
        echo '<h4>All</h4>';
        echo '<p>In order to show all latest pictures you need to set the type, page number, number of pictures per page and the API key
                <br /><code>[stockvault type="all" page="1" perpage="30" key="YOURKEY GOES HERE" target="_blank"]</code></p>';

        echo '</div>';
    }
    
    add_shortcode( 'stockvault', 'wpStockvault_code' );
    function wpStockvault_code ($atts) {
        
        extract( shortcode_atts( array(
		  'userid' => '95049',
          'type' => 'byUser',
          'style' => '',
          'class' => '',
          'wrap_before' => '',
          'wrap_after' => '',
          'target' => '_blank',
          'start' => '',
          'end' => '',
          'key' => '',
          'query' => '',
          'page' => '1',
          'perpage' => '20'
        ), $atts ) );
        
        if ($type == 'byUser') {
            $xml = @simplexml_load_file('http://stockvault.net/api/xml/?type='.$type.'&query='.$userid);
        }
        else if ($type == 'search') {
            $xml = @simplexml_load_file('http://stockvault.net/api/xml/?type='.$type.'&query='.$query.'&apikey='.$key);
        }
        else if ($type == 'all') {
            $xml = @simplexml_load_file('http://stockvault.net/api/xml/?type='.$type.'&p='.$page.'&perpage='.$perpage.'&apikey='.$key);
        }
        
        $html .= $start;
        foreach ($xml->photo as $photo) {
            $html .= $wrap_before.'<a href="'.$photo->photoPageLink.'" title="'.$photo->description.'" target=""><img src="'.$photo->thumbnailLink.'" style="'.$style.'" class="'.$class.'" /></a>'.$wrap_after; 
        }
        $html .= $end;
        return $html;
     }
?>