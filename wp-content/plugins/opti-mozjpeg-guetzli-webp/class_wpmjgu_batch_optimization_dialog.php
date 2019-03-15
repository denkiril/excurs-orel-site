<?php

/*  This code was written by Ihor Slyva
    http://ihorsl.com 
    License GPLv2 */


namespace wpmjgu;


class class_wpmjgu_batch_optimization_dialog
{

public function the_html()
{
    global $wpmjgu_func;
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
?>
<form method='post' action='?page=<?php print $wpmjgu_func->url_file->PLUGIN_SLUG; ?>&tab=batch_optimization'>
    <table class="form-table">
    <tbody>
        <tr>
            <th scope="row"><?php _e('Target'); ?></th>
            <td>
            <select name="target">
                <option value="notoptimized"><?php _e('Images not previously optimized'); ?></option>
                <option value="reoptimize"><?php _e('Reoptimize all images'); ?></option>
            </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Intensive resources consumption mode'); ?></th>
            <td>
                <input type="checkbox" id="t_intensive_resources_consumption_mode" name="intensive_resources_consumption_mode" <?php checked(get_option("wpmjgu_batch_optimization_intensive_resources_consumption_mode", false), true, 'checked'); ?> onclick="js_intensive_resources_consumption_mode_on_click();">
                <label for="t_intensive_resources_consumption_mode"></label>
            </td>
        </tr>
        <tr id="t_simultaneous_processes_tr" class="displaynone">
            <th scope="row"><?php _e('Simultaneous processes'); ?></th>
            <td>
            <select name="max_simultaneous_processes">
                <option value="4" <?php selected(get_option("wpmjgu_batch_optimization_max_simultaneous_processes", 4), 2, 'selected'); ?>>2</option>                
                <option value="4" <?php selected(get_option("wpmjgu_batch_optimization_max_simultaneous_processes", 4), 4, 'selected'); ?>>4</option>
                <option value="4" <?php selected(get_option("wpmjgu_batch_optimization_max_simultaneous_processes", 4), 6, 'selected'); ?>>6</option>
                <option value="8" <?php selected(get_option("wpmjgu_batch_optimization_max_simultaneous_processes", 4), 8, 'selected'); ?>>8</option>
                <option value="16" <?php selected(get_option("wpmjgu_batch_optimization_max_simultaneous_processes", 4), 16, 'selected'); ?>>16</option>
                <option value="32"  <?php selected(get_option("wpmjgu_batch_optimization_max_simultaneous_processes", 4), 32, 'selected'); ?>>32</option>                
            </select>
            </td>
        </tr>        
    </tbody>    
    </table>
    <script>
        function js_intensive_resources_consumption_mode_on_click()
        {
            if (document.getElementById("t_intensive_resources_consumption_mode").checked)
            {
                document.getElementById("t_simultaneous_processes_tr").classList.remove("displaynone");
            }
            else
            {
                document.getElementById("t_simultaneous_processes_tr").classList.add("displaynone");
            }    
        }
        js_intensive_resources_consumption_mode_on_click();
    </script>    
    <p><?php _e('Notice: Please make a backup of your site before running "Batch optimization". Backup both files and database.'); ?></p>
    <br>
    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Run batch optimization'); ?>">    
    <br>
    <p id="feedback_p"><?php _e("Your feedback is very important for plugin's author.<br><a href='https://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/home/#contact' target='_blank'>Click here</a> to send him a message about your experience with Opti MozJpeg Guetzli WebP,<br> and your ideas, how to make the plugin better."); ?></p>    
</form>    
<?php        
    }    
    else if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        set_time_limit(3600);

        if (isset($_POST['intensive_resources_consumption_mode']))
        {
            update_option("wpmjgu_batch_optimization_intensive_resources_consumption_mode", true);
            update_option("wpmjgu_batch_optimization_max_simultaneous_processes", $_POST['max_simultaneous_processes']);
        }    
        else
        {
            update_option("wpmjgu_batch_optimization_intensive_resources_consumption_mode", false);
        }           

        
        global $ALL_WPMJGU_SETTINGS;
        $ALL_WPMJGU_SETTINGS = $wpmjgu_func->get_all_wpmjgu_settings();

        
        if (( $error = $wpmjgu_func->validate->batchOptimization($ALL_WPMJGU_SETTINGS) ))
        {
            print "ERROR: " . $error; return;
        }
       
        
        if ($_POST['target'] === "notoptimized")
        {
            define('SKIP_OPTIMIZED', true);
        }
        else
        {
            define('SKIP_OPTIMIZED', false);
        }
        
        define('ORIGINAL_IMAGES_DIRECTORY', $ALL_WPMJGU_SETTINGS['wpmjgu_plugin_working_directory'] . DIRECTORY_SEPARATOR . $wpmjgu_func->url_file->ORIGINAL_IMAGES_SUBDIRECTORY);

        
        $args =  array(
                        'post_type' => 'attachment',
                        'numberposts' => -1,
                        'post_status' => null,
                        'post_parent' => null,
                   ); 
	
        $attachments = get_posts($args);

	if (count($attachments) === 0)
	{        
            print "No attachments found";
            return;
        }    
        
        $skipped_log = '';
        $id_array = array();
        foreach ($attachments as $attachment)
        {
            $current_url = $wpmjgu_func->url_file->get_attachment_url($attachment->ID, $attachment->guid);
            if (! $current_url) { continue; }
            
            $current_path = $wpmjgu_func->url_file->wpuploads_url_to_path($current_url);  
            $current_filetype = $wpmjgu_func->url_file->path_to_typeflag($current_url);
            $initial_path = $wpmjgu_func->url_file->get_attachment_initial_path($attachment->ID, $current_path, ORIGINAL_IMAGES_DIRECTORY);
            
            if ($current_filetype === $wpmjgu_func->url_file->GIF  &&  $ALL_WPMJGU_SETTINGS['wpmjgu_mode'] !== "webp_duplicate") {
                continue;
            } else if ($current_filetype !== $wpmjgu_func->url_file->GIF  &&  $current_filetype !== $wpmjgu_func->url_file->PNG   &&   $current_filetype !== $wpmjgu_func->url_file->JPG) {
                continue;
            }
            


            if (SKIP_OPTIMIZED)
            {
                
                if ($ALL_WPMJGU_SETTINGS['wpmjgu_mode'] === "webp_duplicate")
                {
                    if (get_post_meta($attachment->ID, 'wpmjgu_webp_duplicate', true) === 'true' )  {  continue;  }
                }
                else
                {    
                    if (get_post_meta($attachment->ID, 'wpmjgu_optimized', true) === 'true' )  {  continue;  }
                }
            }    
            

            
            $pathfilter_result = $wpmjgu_func->url_file->path_filter_apply($initial_path, $ALL_WPMJGU_SETTINGS['wpmjgu_path_filter']);
            if ($pathfilter_result['error']) {
                $skipped_log .= "ERROR: Faild to apply Path filter to file " . $initial_path . "\n";
                continue;
            }        

            if ($pathfilter_result['quality'] === 'SKIP')
            {
                $skipped_log .=  "\n\n---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n\n";
                $skipped_log .= __("Attachment ID") . ": " . $attachment->ID . "\n";
                $skipped_log .= __("Current url") . ": " . $current_url . "\n"; 
                $skipped_log .= __("Current path") . ": " . $current_path . "\n"; 
                $skipped_log .= __("Initial path") . ": " . $current_path . "\n";                
                $skipped_log .= __("Path filter was applied to initial path") . "\n";
                $skipped_log .= $pathfilter_result['out'];
                
                continue;
            }              
            
            
            $id_array[] = $attachment->ID;
            
        }

        
        //---------------------------------
        print "<script>\n";
        print "js_id_array = [];\n";
        foreach ($id_array as $id)
        {
            print "js_id_array.push(" . $id . ");\n";
        }    
        
        print "</script>\n";

?>

<table class="form-table batch_optimization_results_table">
<tbody>
    <tr>
        <th scope="row">Attachments to optimize</th>
        <td id="break_button_cell"><?php print count($id_array); ?><input type="button" class="button" id="break_batch_optimization" value="Stop optimization"></td>
    </tr>
    <tr>
        <th scope="row">Done</th>
        <td><span id="label_done">0</span></td>
    </tr>    
    <tr>
        <th scope="row">With error</th>
        <td><span id="label_witherror">0</span></td>
    </tr>        
    <tr>
        <th scope="row">Status</th>
        <td><span id="batch_optimization_status">Working</span></td>
    </tr>       
</tbody>    
</table>
<br><br>
<?php
    if ($ALL_WPMJGU_SETTINGS['wpmjgu_mode'] === "guetzli")
    {
        print "<p id='lable_guetzli_slow'>Notice: Google Guetzli is terribly slow. Be patient and wait until first output appears on the console.</p>\n";
    }
?>

<script src="<?php print plugins_url('/other_includes/FileSaver.js', __FILE__ ); ?>"></script>
<input type="button" class="button" id="button_save_log" value="Save console log" onclick="js_save_log();">

<div id="console"></div>
<p id='wpmjgu-share' <?php if (rand(0,9) === 0) { print "class='wpmjgu-share-bold'"; } ?>>
    If you like this plugin, please, share information about it in the internet. It would be the best contribution. Google Ads are too expensive for this plugin author.<br>
    <a class='wpmjgu-share-link' target='_blank' href='http://ihorsl.com/redirect.php?t=wpmjgu-facebook-share'>
        <img class='wpmjgu-share-icon' src='<?= plugins_url( 'images/facebook-share.png', __FILE__ ) ?>'>
    </a>
    <a class='wpmjgu-share-link' target='_blank' href='http://ihorsl.com/redirect.php?t=wpmjgu-twitter-share'>
        <img class='wpmjgu-share-icon' src='<?= plugins_url( 'images/twitter-share.png', __FILE__ ) ?>'>
    </a>    
</p>

<script>

window.ALL_WPMJGU_SETTINGS = [];

<?php
foreach ($ALL_WPMJGU_SETTINGS as $name => $value)
{
    print "window.ALL_WPMJGU_SETTINGS['$name'] = '" . urlencode($value) . "';\n";
}
?>


    if (window.ALL_WPMJGU_SETTINGS['wpmjgu_batch_optimization_intensive_resources_consumption_mode'])
    {
        window.max_simultaneous_processes = parseInt(window.ALL_WPMJGU_SETTINGS['wpmjgu_batch_optimization_max_simultaneous_processes']);
    }
    else
    {
        window.max_simultaneous_processes = 1;
    }    

    window.active_processes = 0;

    function js_batch_optimize_next()
    {

        if (js_id_array.length === 0)
        {
            if (window.active_processes === 0)
            {
                js_batch_optimization_on_complete();
                return;                        
            }
            else
            {
                return;
            }    
        } 
        
        //-----------------
        
        var js_xmlhttp = new XMLHttpRequest();
        var js_fd = new FormData();

        var js_id = js_id_array.pop();
        js_fd.append('id', js_id);
        js_fd.append('action', "wpmjgu_batch_optimization_worker");        
        for(js_key in window.ALL_WPMJGU_SETTINGS)
        {
            js_fd.append(js_key, window.ALL_WPMJGU_SETTINGS[js_key]);
        }
        

        js_xmlhttp.onreadystatechange = function()
        {
        
            if (js_xmlhttp.readyState === 4 && js_xmlhttp.status === 200)
            {
            
                var js_output = js_xmlhttp.responseText;
                js_output = js_output.replace(/\r/g , '');
                js_output = js_output.replace(/\n/g , '<br>');                
                
                js_console(js_output);

                if (js_xmlhttp.responseText.indexOf("WPMJGUERROR") !== -1)
                {
                    var js_tag_label_witherror = document.getElementById("label_witherror");
                    js_tag_label_witherror.innerHTML = parseInt(js_tag_label_witherror.innerHTML) + 1;
                }    
                else
                {
                    var js_tag_label_done = document.getElementById("label_done");
                    js_tag_label_done.innerHTML = parseInt(js_tag_label_done.innerHTML) + 1;                    
                }    
                
                window.active_processes--;
                
                //-------------------

                if (js_id_array.length === 0)
                {
                    if (window.active_processes === 0)
                    {
                        js_batch_optimization_on_complete();
                        return;                        
                    }
                    else
                    {
                        return;
                    }    
                } 

                if (window.break_batch_optimization === true)
                {
                    if (window.active_processes > 0)
                    {
                        return;
                    }
                    else
                    {
                        js_batch_on_stop_optimization();
                        return;                        
                    }    

                }            

                
                var js_create_addtitional_processes = window.max_simultaneous_processes - window.active_processes;
                if (js_create_addtitional_processes > js_id_array.length) { js_create_addtitional_processes = js_id_array.length }
                //console.log("create_addtitional_processes=" + js_create_addtitional_processes);

                for (var js_i = 0; js_i < js_create_addtitional_processes; js_i++)
                {
                    //console.log('+' + js_i);
                    js_batch_optimize_next();
                }    

            }
        };
        
        window.active_processes++;
        js_xmlhttp.open("POST", ajaxurl, true);
        js_xmlhttp.send(js_fd);
    }



function js_batch_stop_optimization()
{
    var js_tag_button = document.getElementById("break_batch_optimization");
    window.break_batch_optimization = true;
    js_tag_button.setAttribute("disabled", "disabled");
    js_tag_button.onclick = js_batch_continue_optimization;
        
    if (window.max_simultaneous_processes > 1)
    {    
        document.getElementById("batch_optimization_status").innerHTML = "Breaking. Waiting for the current file(s) to befineshed";        
    }
    else
    {
        document.getElementById("batch_optimization_status").innerHTML = "Breaking. Waiting for the current file to befineshed";        
    }    
    
}

function js_batch_on_stop_optimization()
{
    var js_tag_button = document.getElementById("break_batch_optimization");
    js_tag_button.removeAttribute("disabled");
    js_tag_button.value = "Continue optimization";
    document.getElementById("batch_optimization_status").innerHTML = "Breaked";
    window.onbeforeunload = null;    
}

function js_batch_continue_optimization()
{
    var js_tag_button = document.getElementById("break_batch_optimization");    
    window.break_batch_optimization = false;    
    js_tag_button.value = "Stop optimization";
    js_tag_button.onclick = js_batch_stop_optimization;
    document.getElementById("batch_optimization_status").innerHTML = "Working";    
    
    window.onbeforeunload = function(){ return 'Batch optimization in progress. Stop it before exiting this page!'; };
    js_batch_optimize_next();
}

function js_batch_optimization_on_complete()
{
    var js_tag_button = document.getElementById("break_batch_optimization");
    js_tag_button.style.visibility = "hidden";
    document.getElementById("batch_optimization_status").innerHTML = "Complete";
    window.onbeforeunload = null;
    
<?php
global $wpmjgu_func;
if ($ALL_WPMJGU_SETTINGS['wpmjgu_mode'] === "webp_duplicate")
{
    $message = <<<MESSAGE

   
-----------------------------------------------------------------------------------------
! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! !
-----------------------------------------------------------------------------------------
            
Webp duplicates generation complete
To enable webp images serving to webp compatible browsers, add this in your .htaccess
            
-----------------------------------------------------------------------------------------            
            
###
# BEGIN Opti MozJpeg Guetzli WebP
###
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTP_ACCEPT} image/webp
RewriteCond %{REQUEST_URI} .*(jpe?g|png|gif)$
RewriteCond %{REQUEST_FILENAME}\.webp -f
RewriteRule (.*) %{REQUEST_FILENAME}\.webp [T=image/webp,E=accept:1]
</IfModule>
<IfModule mod_headers.c>
Header append Vary Accept env=REDIRECT_accept
</IfModule>
AddType image/webp .webp
###
# END Opti MozJpeg Guetzli WebP
###
MESSAGE;
    $message = htmlspecialchars($message);
    $message = $wpmjgu_func->string->mbStrReplace("\n", '<br>', $message);
    $message = $wpmjgu_func->string->mbStrReplace("\r", '', $message);
    $message = $wpmjgu_func->string->mbStrReplace(" ", '&nbsp;', $message);
    
    print "js_console('$message');\n";
}    
?>

    js_console("<br>\n<br>\n<br>\n---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n<br>\n<br>\n");
    js_console("Batch optimization finished at " + new Date().toLocaleString());
    js_console("<br>\n<br>\n<br>\n");

}

function js_console(js_text)
{
    var js_tag_console = document.getElementById("console");
    var js_inner_div = document.createElement('div');
    js_inner_div.innerHTML = js_text;
    js_tag_console.appendChild(js_inner_div);
    //js_tag_wpmjgu_console.scrollTop = js_tag_wpmjgu_console.scrollHeight;    
}

function js_save_log()
{
    var js_tag_console = document.getElementById("console");
    var js_console_text = js_tag_console.innerText;
    js_console_text = js_console_text.replace(/\n/g , '\r\n');
    var js_blob = new Blob([js_console_text], {type: "text/plain;charset=utf-8"});
    saveAs(js_blob, "wpmjgu_batch_optimization_log.txt");
}

js_console('Batch optimization started at ' + new Date().toLocaleString());
js_console("<?= str_replace("\n", '<br>', htmlspecialchars($skipped_log)) ?>");
js_batch_continue_optimization();

</script>


<?php
        
    }
    
}

}
