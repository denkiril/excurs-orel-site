<?php

/*  This code was written by Ihor Slyva
    http://ihorsl.com 
    License GPLv2 */


namespace wpmjgu;


class class_wpmjgu_batch_revert_dialog
{

public function the_html()
{
    global $wpmjgu_func;
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET')
    {
?>
<form method='post' action='?page=<?php print $wpmjgu_func->url_file->PLUGIN_SLUG; ?>&tab=batch_revert'>
    <p><?php _e('You can completely revert all changes this plugin have made in your website. Just click "Start revert".'); ?></p>
    <br>
    <input type="submit" name="submit" id="submit" class="button button-primary" value="Start revert">
    <br>
    <p id="feedback_p"><?php _e("Your feedback is very important for plugin's author.<br><a href='https://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/home/#contact' target='_blank'>Click here</a> to send him a message about your experience with Opti MozJpeg Guetzli WebP,<br> and your ideas, how to make the plugin better."); ?></p>        
</form>    
<?php        
    }    
    else if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        set_time_limit(3600);

        global $ALL_WPMJGU_SETTINGS;
        $ALL_WPMJGU_SETTINGS = $wpmjgu_func->get_all_wpmjgu_settings();        
        
        if (( $error = $wpmjgu_func->validate->batchRevert($ALL_WPMJGU_SETTINGS) ))
        {
            print "ERROR: " . $error; return;
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
        
        $id_array = array();
        foreach ($attachments as $attachment)
        {
            
            $current_url = $wpmjgu_func->url_file->get_attachment_url($attachment->ID, $attachment->guid);
            if (! $current_url) { continue; }
            
            $current_path = $wpmjgu_func->url_file->wpuploads_url_to_path($current_url);
            $initial_path = $wpmjgu_func->url_file->get_attachment_initial_path($attachment->ID, $current_path, ORIGINAL_IMAGES_DIRECTORY);
            
            $initial_filetype = $wpmjgu_func->url_file->path_to_typeflag($initial_path);
            if ($initial_filetype !== $wpmjgu_func->url_file->GIF   &&   $initial_filetype !== $wpmjgu_func->url_file->PNG   &&   $initial_filetype !== $wpmjgu_func->url_file->JPG) { continue; }

            if ($wpmjgu_func->url_file->has_backup($attachment->ID, $current_path, ORIGINAL_IMAGES_DIRECTORY)
                || $wpmjgu_func->url_file->has_webp_duplicates($current_path) )
            {
                $id_array[] = $attachment->ID;
            }        

        }

        
        //---------------------------------
        print "<script>\n";
        print "js_id_array = [];\n";
        foreach ($id_array as $id)
        {
            print "js_id_array.push( '" . $id . "');\n";
        }    
        
        print "</script>\n";

?>
<table class="form-table batch_revert_results_table">
<tbody>
    <tr>
        <th scope="row">Attachments to revert</th>
        <td id="break_button_cell"><?php print count($id_array); ?><input type="button" class="button" id="break_batch_revert" value="Stop optimization"></td>
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
        <td><span id="batch_revert_status">Working</span></td>
    </tr>       
</tbody>    
</table>
<br><br>

<script src="<?php print plugins_url('/other_includes/FileSaver.js', __FILE__ ); ?>"></script>
<input type="button" class="button" id="button_save_log" value="Save console log" onclick="js_save_log();">

<div id="console"></div>
<script>
    
    function js_batch_revert_next()
    {


        if (window.break_batch_revert === true)
        {
            var js_tag_button = document.getElementById("break_batch_revert");
            js_tag_button.removeAttribute("disabled");
            js_tag_button.value = "Continue revert";
            document.getElementById("batch_revert_status").innerHTML = "Breaked";
            window.onbeforeunload = null;
            return;
        }
        
        if (js_id_array.length === 0 )
        {
            window.onbeforeunload = null;
            js_batch_revert_on_complete();
            return;
        }    
        
        var js_xmlhttp = new XMLHttpRequest();
        var js_fd = new FormData();
<?php
foreach ($ALL_WPMJGU_SETTINGS as $name => $value)
{
    print "     js_fd.append('$name', '" . urlencode($value) . "');\n";
}
?>
        var js_id = js_id_array.pop();
        js_fd.append('id', js_id);
        js_fd.append('action', "wpmjgu_batch_revert_worker"); 

        js_xmlhttp.onreadystatechange = function()
        {
        
            if (js_xmlhttp.readyState === 4 && js_xmlhttp.status === 200)
            {
            
                var js_output = js_xmlhttp.responseText;
                js_output = js_output.replace(/\r/g , '');
                js_output = js_output.replace(/\n/g , '<br>');                
                
                js_console(js_output); 

                if (js_output.indexOf("WPMJGUERROR") !== -1)
                {
                    var js_tag_label_witherror = document.getElementById("label_witherror");
                    js_tag_label_witherror.innerHTML = parseInt(js_tag_label_witherror.innerHTML) + 1;
                }    
                else
                {
                    var js_tag_label_done = document.getElementById("label_done");
                    js_tag_label_done.innerHTML = parseInt(js_tag_label_done.innerHTML) + 1;                    
                }    
                
                js_batch_revert_next();

            }
        };
        js_xmlhttp.open("POST", ajaxurl, true);
        js_xmlhttp.send(js_fd);
    }



function js_batch_stop_revert()
{
    var js_tag_button = document.getElementById("break_batch_revert");
    window.break_batch_revert = true;
    js_tag_button.setAttribute("disabled", "disabled");
    document.getElementById("batch_revert_status").innerHTML = "Breaking. Waiting for the current file to befineshed";        
    js_tag_button.onclick = js_batch_continue_revert;
}

function js_batch_continue_revert()
{
    var js_tag_button = document.getElementById("break_batch_revert");    
    window.break_batch_revert = false;    
    js_tag_button.value = "Stop revert";
    js_tag_button.onclick = js_batch_stop_revert;
    document.getElementById("batch_revert_status").innerHTML = "Working";    
    
    window.onbeforeunload = function(){ return 'Revert in progress. Stop it before exiting this page!'; };
    js_batch_revert_next();
}

function js_batch_revert_on_complete()
{
    var js_tag_button = document.getElementById("break_batch_revert");
    js_tag_button.style.visibility = "hidden";
    document.getElementById("batch_revert_status").innerHTML = "Complete";
    
    js_console("<br>\n<br>\n<br>\n---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n<br>\n<br>\n");
    js_console("Batch revert finished at " + new Date().toLocaleString());
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
    saveAs(js_blob, "wpmjgu_batch_revert_log.txt");
}

js_console('Batch revert started at ' + new Date().toLocaleString());
js_batch_continue_revert();

</script>


<?php
        
    }
    
}

}
