<?php

/*  This code was written by Ihor Slyva
    http://ihorsl.com 
    License GPLv2 */


namespace wpmjgu;

class class_wpmjgu_settings_dialog
{

public $validation_error = false;    

public function the_html()
{        
    print '<p>Notice: This plugin is quite complicated tool. Please, read <a target="_blank" href="http://ihorsl.com/en/wordpress-opti-mozjpeg-guetzli-webp/brief-man/"><i>this brief manual</i></a> before using it.</p>';
    print "<form method='post' action='options.php'>";
    settings_fields("wpmjgu_settins_section");
    do_settings_sections("opti-mozjpeg-guetzli-webp");
    submit_button();
    print "</form>";
    ?>
    <script>
        js_wpmjgu_mode_on_change();
        js_wpmjgu_jpeg_encoder_location_on_change();
    </script>    
    <?php
}

public function settins_section_content()
{
    
}


//---------------------------------------



public function plugin_working_directory_show()
{
    global $wpmjgu_func;
    
    $value = $this->load_setting("wpmjgu_plugin_working_directory", $wpmjgu_func->url_file->PLUGIN_DEFAULT_WORKING_DIRECTORY);

    ?>
<input type="text" name="wpmjgu_plugin_working_directory" id="wpmjgu_plugin_working_directory" value="<?php print $value; ?>" />
    <?php    
}


public function plugin_working_directory_check($path)
{
    global $wpmjgu_func;
    
    $path = trim($path);
    $error = $wpmjgu_func->validate->working_directory($path);
    if ($error)
    {
        return $this->validation_error("wpmjgu_plugin_working_directory", $path, $error);
    }    
    return $this->validation_ok("wpmjgu_plugin_working_directory", $path);
}


//---------------------------------------


public function mode_show()
{
    ?>
<select name="wpmjgu_mode" id="wpmjgu_mode" onchange="js_wpmjgu_mode_on_change();">
    <option value="mozjpeg" <?php selected(get_option('wpmjgu_mode'), "mozjpeg"); ?>>Mozilla MozJpeg</option>
    <option value="guetzli" <?php selected(get_option('wpmjgu_mode'), "guetzli"); ?>>Google Guetzli</option>
    <option value="webp_duplicate" <?php selected(get_option('wpmjgu_mode'), "webp_duplicate"); ?>>Google WebP (duplicates generation)</option>    
    <?php //<option value="mozjpeg_vs_webp" <?php selected(get_option('wpmjgu_mode'), "mozjpeg_vs_webp"); ? >>Mozilla MozJpeg smaller then Google WebP</option> ?>       
</select>    
<script>
    function js_wpmjgu_mode_on_change()
    {
        switch (document.getElementById("wpmjgu_mode").value)
        {
            case 'mozjpeg':
                document.getElementById("wpmjgu_default_jpeg_quality").parentNode.parentNode.style.display = "table-row";
                document.getElementById("wpmjgu_mozjpeg_encoder_directory").parentNode.parentNode.style.display = "table-row";
                document.getElementById("wpmjgu_guetzli_encoder_directory").parentNode.parentNode.style.display = "none";
                document.getElementById("wpmjgu_webp_encoder_directory").parentNode.parentNode.style.display = "none";
            break;    
            
            case 'guetzli':
                document.getElementById("wpmjgu_default_jpeg_quality").parentNode.parentNode.style.display = "table-row";
                document.getElementById("wpmjgu_mozjpeg_encoder_directory").parentNode.parentNode.style.display = "none";
                document.getElementById("wpmjgu_guetzli_encoder_directory").parentNode.parentNode.style.display = "table-row";
                document.getElementById("wpmjgu_webp_encoder_directory").parentNode.parentNode.style.display = "none";
            break;                
            
            case 'webp_duplicate':
                document.getElementById("wpmjgu_default_jpeg_quality").parentNode.parentNode.style.display = "table-row";
                document.getElementById("wpmjgu_mozjpeg_encoder_directory").parentNode.parentNode.style.display = "none";
                document.getElementById("wpmjgu_guetzli_encoder_directory").parentNode.parentNode.style.display = "none";
                document.getElementById("wpmjgu_webp_encoder_directory").parentNode.parentNode.style.display = "table-row";
            break;                            
        }
    }
</script>

    <?php
}

public function mode_check($data)
{
    return $data;
}



//---------------------------------------



public function path_filter_show()
{
    global $wpmjgu_func;
    
    $wpmjgu_path_filter = get_option("wpmjgu_path_filter");
    ?>
<script src="<?php print plugins_url('/other_includes/FileSaver.js', __FILE__ ); ?>"></script>
<script>

    var js_wpmjgu_path_filter_array = [];
    var js_wpmjgu_path_filter_selected_index = -1;

    function js_wpmjgu_pf_prompt(js_pattern, js_quality)
    {
        var js_new_pattern = prompt("Enter path pattern with * or ? wildcards", js_pattern);
        if (js_new_pattern === null) { return false; }
        
        js_new_pattern = js_new_pattern.trim();
        if (js_new_pattern.length === 0) { return false; }
        
        do
        {
            var js_new_quality = js_quality;
            js_new_quality = prompt("Enter quality for this path pattern. Or leave empty to exclude this pattern files from optimization.", js_new_quality);
            if (js_new_quality === null) { return false; }
            if (js_new_quality.trim().length === 0)
            {
                js_new_quality = 'SKIP';
                break;
            }  
        } while (isNaN(js_new_quality) || js_new_quality > 100 || js_new_quality < 1)    
            
        return { pattern: js_new_pattern, quality: js_new_quality  };   
                
    }

    function js_wpmjgu_path_filter_array_after_change()
    {
        var js_wpmjgu_tag_path_filter_div = document.getElementById("wpmjgu_pf_items_div");
        var js_wpmjgu_new_html = "";
        
        for (var js_wpmjgu_i = 0; js_wpmjgu_path_filter_array[js_wpmjgu_i] !== undefined; js_wpmjgu_i++)
        {

            if (js_wpmjgu_i === js_wpmjgu_path_filter_selected_index)
            {
                var js_selected = ' selected';
            }
            else
            {
                var js_selected = '';                
            }    
            
            js_wpmjgu_new_html += "<div class='wpmjgu_pf_item" + js_selected + "' onclick='js_wpmjgu_pf_item_on_click.call(this);' data-index='" + js_wpmjgu_i + "'><p class='wpmjgu_pf_item_pattern'>" + js_wpmjgu_path_filter_array[js_wpmjgu_i].pattern + "</p><p class='wpmjgu_pf_item_quality'>" + js_wpmjgu_path_filter_array[js_wpmjgu_i].quality + "</p></div>";
        }
        
        js_wpmjgu_tag_path_filter_div.innerHTML = js_wpmjgu_new_html;
        var js_tags_items = js_wpmjgu_tag_path_filter_div.childNodes;
        
        if (js_wpmjgu_path_filter_array.length > 2)
        {
            var js_new_container_height = js_tags_items[js_tags_items.length - 1].offsetTop - js_tags_items[0].offsetTop + 60;
    
            if (js_new_container_height < 150) { js_new_container_height = 150; }
            js_wpmjgu_tag_path_filter_div.style.height = js_new_container_height + "px";
        }    
        
        document.getElementById("wpmjgu_path_filter").value = js_wpmjgu_pf_pack_data();
        js_wpmjgu_pf_control_buttons_show_hide();
    }





    function js_wpmjgu_pf_control_buttons_show_hide()
    {
        if (js_wpmjgu_path_filter_selected_index < 0 || js_wpmjgu_path_filter_array.length === 0)
        {
            document.getElementById("wpmjgu_pf_ctrl_button_first").setAttribute("disabled", "disabled");
            document.getElementById("wpmjgu_pf_ctrl_button_last").setAttribute("disabled", "disabled");            
            document.getElementById("wpmjgu_pf_ctrl_button_left").setAttribute("disabled", "disabled");
            document.getElementById("wpmjgu_pf_ctrl_button_right").setAttribute("disabled", "disabled");                        
            document.getElementById("wpmjgu_pf_ctrl_button_edit").setAttribute("disabled", "disabled");
            document.getElementById("wpmjgu_pf_ctrl_button_delete").setAttribute("disabled", "disabled");            
            return;
        }    


        if (js_wpmjgu_path_filter_selected_index === 0)
        {
            document.getElementById("wpmjgu_pf_ctrl_button_first").setAttribute("disabled", "disabled");
            document.getElementById("wpmjgu_pf_ctrl_button_left").setAttribute("disabled", "disabled"); 
        }
        else
        {
            document.getElementById("wpmjgu_pf_ctrl_button_first").removeAttribute("disabled");
            document.getElementById("wpmjgu_pf_ctrl_button_left").removeAttribute("disabled");           
        }


        if (js_wpmjgu_path_filter_selected_index === js_wpmjgu_path_filter_array.length - 1)
        {
            document.getElementById("wpmjgu_pf_ctrl_button_last").setAttribute("disabled", "disabled");
            document.getElementById("wpmjgu_pf_ctrl_button_right").setAttribute("disabled", "disabled");            
        }
        else
        {
            document.getElementById("wpmjgu_pf_ctrl_button_last").removeAttribute("disabled");
            document.getElementById("wpmjgu_pf_ctrl_button_right").removeAttribute("disabled");
        }

        document.getElementById("wpmjgu_pf_ctrl_button_edit").removeAttribute("disabled");
        document.getElementById("wpmjgu_pf_ctrl_button_delete").removeAttribute("disabled");
    }

    function js_wpmjgu_pf_pack_data()
    {
        var js_packed = '';
        for (var js_i in js_wpmjgu_path_filter_array)
        {
            var js_pattern = js_wpmjgu_path_filter_array[js_i].pattern;
            var js_quality = js_wpmjgu_path_filter_array[js_i].quality;
            js_pattern = js_pattern.replace(/,/g , '&#x002C;');
            
            js_packed += js_pattern + ',' + js_quality + "\n";
        }

        return js_packed;
    }

    function js_wpmjgu_pf_load_data(js_data)
    {
        js_wpmjgu_path_filter_array = [];
        js_wpmjgu_path_filter_selected_index = -1;
        
        var js_id = -1;
        var js_rows = js_data.split("\n");
        js_rows.map(function (js_row)
        {
            js_row = js_row.trim();
            if (js_row.length)
            {
                js_row_splited = js_row.split(",");
                var js_pattern = js_row_splited[0].trim();
                var js_quality = js_row_splited[1].trim();
                
                if (js_pattern.length && js_quality.length)
                {
                    js_pattern = js_pattern.replace(/&#x002C;/g, ',');
                    js_id++;
                    js_wpmjgu_path_filter_array.push({ pattern: js_pattern, quality: js_quality  });
                }    
            }    
        });
        
        js_wpmjgu_path_filter_array_after_change();
    }

    function wpmjgu_pf_ctrl_button_import_on_click()
    {
        document.getElementById("wpmjgu_pf_fileinput").click();        
    }

    function wpmjgu_pf_fileinput_on_change(js_event)
    {
        var js_r = new FileReader();
        var js_f = js_event.target.files[0];
        js_r.onload = function(js_e)
        { 
            var js_contents = js_e.target.result;
            js_wpmjgu_pf_load_data(js_contents);  
        };
        js_r.readAsText(js_f);
    }

    function wpmjgu_pf_ctrl_button_export_on_click()
    {
        var js_blob = new Blob([js_wpmjgu_pf_pack_data()], {type: "text/plain;charset=utf-8"});
        saveAs(js_blob, "path_filter.csv");
    }

    function wpmjgu_pf_ctrl_button_add_on_click()
    {

        var js_new_item = js_wpmjgu_pf_prompt("", "");
        if (js_new_item === false) { return; }

        js_wpmjgu_path_filter_selected_index = js_wpmjgu_path_filter_array.length;
        js_wpmjgu_path_filter_array.push(js_new_item);
        
        js_wpmjgu_path_filter_array_after_change();
    }

    function js_wpmjgu_pf_item_on_click()
    {
        js_wpmjgu_path_filter_selected_index = parseInt(this.getAttribute('data-index'));
        js_wpmjgu_path_filter_array_after_change();
    }



    function js_wpmjgu_pf_ctrl_button_delete_on_click()
    {
        js_wpmjgu_path_filter_array = js_wpmjgu_path_filter_array.filter(function(js_item, js_index)
        { 
            if (js_index !== js_wpmjgu_path_filter_selected_index) { return js_item; }	
        });
        
        js_wpmjgu_path_filter_selected_index = -1;
        js_wpmjgu_path_filter_array_after_change();

    }
    
    function js_wpmjgu_pf_ctrl_button_first_on_click()
    {
        if (js_wpmjgu_path_filter_selected_index === 0) { return; }
        var js_current_item_temp = js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index];
        js_wpmjgu_path_filter_array = js_wpmjgu_path_filter_array.filter(function(js_item, js_index)
        { 
            if (js_index !== js_wpmjgu_path_filter_selected_index) { return js_item; }	
        });
        js_wpmjgu_path_filter_array.unshift(js_current_item_temp);
        js_wpmjgu_path_filter_selected_index = 0;
        
        js_wpmjgu_path_filter_array_after_change();
    }
    
    function js_wpmjgu_pf_ctrl_button_last_on_click()
    {
        if (js_wpmjgu_path_filter_selected_index >= js_wpmjgu_path_filter_array.length - 1) { return; }
        var js_current_item_temp = js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index];
        js_wpmjgu_path_filter_array = js_wpmjgu_path_filter_array.filter(function(js_item, js_index)
        { 
            if (js_index !== js_wpmjgu_path_filter_selected_index) { return js_item; }	
        });
        js_wpmjgu_path_filter_array.push(js_current_item_temp);
        js_wpmjgu_path_filter_selected_index = js_wpmjgu_path_filter_array.length - 1;
        
        js_wpmjgu_path_filter_array_after_change();
    }    

    function js_wpmjgu_pf_ctrl_button_left_on_click()
    {
        if (js_wpmjgu_path_filter_selected_index === 0) { return; }
        
        var js_current_item_temp = js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index];
        js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index] = js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index - 1];
        js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index - 1] = js_current_item_temp;
        
        js_wpmjgu_path_filter_selected_index = js_wpmjgu_path_filter_selected_index - 1;
        
        js_wpmjgu_path_filter_array_after_change();        
   }
   
    function js_wpmjgu_pf_ctrl_button_right_on_click()
    {
        if (js_wpmjgu_path_filter_selected_index >= js_wpmjgu_path_filter_array.length - 1) { return; }
        
        var js_current_item_temp = js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index];
        js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index] = js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index + 1];
        js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index + 1] = js_current_item_temp;
        
        js_wpmjgu_path_filter_selected_index = js_wpmjgu_path_filter_selected_index + 1;
        
        js_wpmjgu_path_filter_array_after_change();        
   } 
   
    function js_wpmjgu_pf_ctrl_button_edit_on_click()
    {
        var js_current_item = js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index];
        if (js_current_item.quality === 'SKIP') { js_current_item.quality = ''; }
        
        var js_edited_item = js_wpmjgu_pf_prompt(js_current_item.pattern, js_current_item.quality);
        if (js_edited_item === false)
        {
            return;
        }
        else
        {
            js_wpmjgu_path_filter_array[js_wpmjgu_path_filter_selected_index] = js_edited_item;
        }    
        
        js_wpmjgu_path_filter_array_after_change();         
    }
    
 
</script>
<div id="wpmjgu_pf_controls_div">
    <input type="button" class=" button" id="wpmjgu_pf_ctrl_button_first" title="Make first" value="&#x219e;" disabled onclick="js_wpmjgu_pf_ctrl_button_first_on_click();">
    <input type="button" class="button" id="wpmjgu_pf_ctrl_button_last"  title="Make last" value="&#x21a0;" disabled onclick="js_wpmjgu_pf_ctrl_button_last_on_click();">
    <input type="button" class="button" id="wpmjgu_pf_ctrl_button_left"  title="Move left" value="&#x21fd;" disabled onclick="js_wpmjgu_pf_ctrl_button_left_on_click();">
    <input type="button" class="button" id="wpmjgu_pf_ctrl_button_right"  title="Move right" value="&#x21fe;" disabled onclick="js_wpmjgu_pf_ctrl_button_right_on_click();">
    <input type="button" class="button" id="wpmjgu_pf_ctrl_button_add"  title="And path filter" value="Add" onclick="wpmjgu_pf_ctrl_button_add_on_click();">    
    <input type="button" class="button" id="wpmjgu_pf_ctrl_button_edit"  title="Edit selected path filter" value="Edit" disabled onclick="js_wpmjgu_pf_ctrl_button_edit_on_click();">        
    <input type="button" class="button" id="wpmjgu_pf_ctrl_button_delete"  title="Delete selected path filter" value="Delete" disabled onclick="js_wpmjgu_pf_ctrl_button_delete_on_click();">        
    <input type="button" class="button" id="wpmjgu_pf_ctrl_button_import"  title="Import path filter rules from file" value="Import" onclick="wpmjgu_pf_ctrl_button_import_on_click();">        
    <input type="button" class="button" id="wpmjgu_pf_ctrl_button_export"  title="Export path filter rules to file" value="Export" onclick="wpmjgu_pf_ctrl_button_export_on_click();">            
</div>
<div id="wpmjgu_pf_items_div"></div>

<input type="file" accept=".csv" id="wpmjgu_pf_fileinput" onchange="wpmjgu_pf_fileinput_on_change.call(this, event);" />

<input type="hidden" name="wpmjgu_path_filter" id="wpmjgu_path_filter" value="<?php print $wpmjgu_path_filter ?>" />
<script>js_wpmjgu_pf_load_data(document.getElementById("wpmjgu_path_filter").value);</script>
    <?php    
}

public function path_filter_check($data)
{
    return $data;
}



//---------------------------------------



public function jpeg_compression_min_benefit_show()
{
    $wpmjgu_jpeg_compression_min_benefit = $this->load_setting('wpmjgu_jpeg_compression_min_benefit', 10);
    ?>
<input type="text" name="wpmjgu_jpeg_compression_min_benefit" id="wpmjgu_jpeg_compression_min_benefit" value="<?php print $wpmjgu_jpeg_compression_min_benefit; ?>" />
    <?php       
}

public function jpeg_compression_min_benefit_check($benefit)
{
    $encoder = get_option("wpmjgu_mode");
    if ($encoder === 'mozjpeg-jpegtran') 
    {
        return $this->validation_ok("wpmjgu_jpeg_compression_min_benefit", $benefit);
    }    

    global $wpmjgu_func;
    
    $benefit = trim($benefit);
    $benefit = rtrim($benefit, "%");
    $error = $wpmjgu_func->validate->jpeg_compression_min_benefit($benefit);
    if ($error)
    {
        return $this->validation_error("wpmjgu_jpeg_compression_min_benefit", $benefit, $error);
    }
    else
    {
        return $this->validation_ok("wpmjgu_jpeg_compression_min_benefit", $benefit);
    }
    
}



//---------------------------------------



public function default_jpeg_quality_show()
{
    $wpmjgu_default_jpeg_quality = $this->load_setting('wpmjgu_default_jpeg_quality', "75");
    ?>
<input type="text" name="wpmjgu_default_jpeg_quality" id="wpmjgu_default_jpeg_quality" value="<?php print $wpmjgu_default_jpeg_quality; ?>" />
    <?php       
}

public function default_jpeg_quality_check($quality)
{
    $encoder = get_option("wpmjgu_mode");
    if ($encoder === 'mozjpeg-jpegtran') 
    {
        return $this->validation_ok("wpmjgu_default_jpeg_quality", $quality);
    }    

    global $wpmjgu_func;
    $quality = trim($quality);
    
    $error = $wpmjgu_func->validate->default_jpeg_quality($encoder, $quality);
    if ($error)
    {
        return $this->validation_error("wpmjgu_default_jpeg_quality", $quality, $error);
    }
    else
    {
        return $this->validation_ok("wpmjgu_default_jpeg_quality", $quality);
    }
    
    
}



//------------------------------------------



public function jpeg_encoder_location_show()
{
    $wpmjgu_jpeg_encoder_location = $this->load_setting('wpmjgu_jpeg_encoder_location');

    ?>
<select name="wpmjgu_jpeg_encoder_location" id="wpmjgu_jpeg_encoder_location" onchange="js_wpmjgu_jpeg_encoder_location_on_change();">
    <option value="local" <?php selected($wpmjgu_jpeg_encoder_location, "local"); ?>>Local</option>
    <option value="remote" <?php selected($wpmjgu_jpeg_encoder_location, "remote"); ?>>Remote (through ssh)</option>
</select>    
<script>
    function js_wpmjgu_jpeg_encoder_location_on_change()
    {
        if (document.getElementById("wpmjgu_jpeg_encoder_location").value === "local")
        {
            document.getElementById("wpmjgu_ssh_server").parentNode.parentNode.style.display = "none";
            document.getElementById("wpmjgu_ssh_port").parentNode.parentNode.style.display = "none";            
            document.getElementById("wpmjgu_ssh_username").parentNode.parentNode.style.display = "none";
            document.getElementById("wpmjgu_ssh_password").parentNode.parentNode.style.display = "none";                        
            document.getElementById("wpmjgu_ssh_working_directory").parentNode.parentNode.style.display = "none";                                    
        }
        else
        {
            document.getElementById("wpmjgu_ssh_server").parentNode.parentNode.style.display = "table-row";
            document.getElementById("wpmjgu_ssh_port").parentNode.parentNode.style.display = "table-row";            
            document.getElementById("wpmjgu_ssh_username").parentNode.parentNode.style.display = "table-row";
            document.getElementById("wpmjgu_ssh_password").parentNode.parentNode.style.display = "table-row";                        
            document.getElementById("wpmjgu_ssh_working_directory").parentNode.parentNode.style.display = "table-row";
        }    
    }
</script>

    <?php
}

public function jpeg_encoder_location_check($jpeg_encoder_location)
{

    
    if ($jpeg_encoder_location === "remote")
    {
        if ( !class_exists("\phpseclib\Net\SSH2") )
        {
            return $this->validation_error("wpmjgu_jpeg_encoder_location", $jpeg_encoder_location, 'phpseclib not included' );
        }    
    }    

    return $this->validation_ok("wpmjgu_jpeg_encoder_location", $jpeg_encoder_location);
    
}




//------------------------------------------



public function ssh_server_show()
{
    $wpmjgu_ssh_server = $this->load_setting('wpmjgu_ssh_server');
    ?>
<input type="text" name="wpmjgu_ssh_server" id="wpmjgu_ssh_server" value="<?php print $wpmjgu_ssh_server; ?>" />
    <?php       
}

public function ssh_server_check($sshserver)
{
    $wpmjgu_jpeg_encoder_location = get_option("wpmjgu_jpeg_encoder_location");
    if ($wpmjgu_jpeg_encoder_location === 'local') 
    {
        return $this->validation_ok("wpmjgu_ssh_server", $sshserver);
    }    

    global $wpmjgu_func;
    $sshserver = trim($sshserver);
    
    $error = $wpmjgu_func->validate->ssh_server($sshserver);
    if ($error)
    {
        return $this->validation_error("wpmjgu_ssh_server", $sshserver, $error);
    }
    else
    {
        return $this->validation_ok("wpmjgu_ssh_server", $sshserver);
    }
    
    
}



//------------------------------------------



public function ssh_port_show()
{
    $wpmjgu_ssh_port = $this->load_setting('wpmjgu_ssh_port', 22);
    ?>
<input type="text" name="wpmjgu_ssh_port" id="wpmjgu_ssh_port" value="<?php print $wpmjgu_ssh_port; ?>" />
    <?php       
}


public function ssh_port_check($sshport)
{
    $wpmjgu_jpeg_encoder_location = get_option("wpmjgu_jpeg_encoder_location");
    if ($wpmjgu_jpeg_encoder_location === 'local') 
    {
        return $this->validation_ok("wpmjgu_ssh_server", $sshport);
    }    

    global $wpmjgu_func;
    $sshport = trim($sshport);
    
    $error = $wpmjgu_func->validate->ssh_port($sshport);
    if ($error)
    {
        return $this->validation_error("wpmjgu_ssh_port", $sshport, $error);
    }
    else
    {
        return $this->validation_ok("wpmjgu_ssh_port", $sshport);
    }
    
    
}



//------------------------------------------



public function ssh_username_show()
{
    $wpmjgu_ssh_username = $this->load_setting('wpmjgu_ssh_username');
    ?>
<input type="text" name="wpmjgu_ssh_username" id="wpmjgu_ssh_username" value="<?php print $wpmjgu_ssh_username; ?>" />
    <?php       
}

public function ssh_username_check($ssh_username)
{
    $wpmjgu_jpeg_encoder_location = get_option("wpmjgu_jpeg_encoder_location");
    if ($wpmjgu_jpeg_encoder_location === 'local') 
    {
        return $this->validation_ok("wpmjgu_ssh_username", $ssh_username);
    }    

    global $wpmjgu_func;
    $ssh_username = trim($ssh_username);
    
    $error = $wpmjgu_func->validate->ssh_username($ssh_username);
    if ($error)
    {
        return $this->validation_error("wpmjgu_ssh_username", $ssh_username, $error);
    }
    else
    {
        return $this->validation_ok("wpmjgu_ssh_username", $ssh_username);
    }
    
    
}



//------------------------------------------



public function ssh_password_show()
{
    $wpmjgu_ssh_password = $this->load_setting('wpmjgu_ssh_password');
    ?>
<input type="password" name="wpmjgu_ssh_password" id="wpmjgu_ssh_password" value="<?php print $wpmjgu_ssh_password; ?>" />
    <?php       
}

public function ssh_password_check($ssh_password)
{
    $wpmjgu_jpeg_encoder_location = get_option("wpmjgu_jpeg_encoder_location");
    if ($wpmjgu_jpeg_encoder_location === 'local') 
    {
        return $this->validation_ok("wpmjgu_ssh_password", $ssh_password);
    }    

    global $wpmjgu_func;
    $ssh_password = trim($ssh_password);
    
    $error = $wpmjgu_func->validate->ssh_password($ssh_password);
    if ($error)
    {
        return $this->validation_error("wpmjgu_ssh_password", $ssh_password, $error);
    }
    else
    {
        return $this->validation_ok("wpmjgu_ssh_password", $ssh_password);
    }
    
    
}


//------------------------------------------



public function ssh_working_directory_show()
{
    $wpmjgu_ssh_working_directory = $this->load_setting('wpmjgu_ssh_working_directory');
    ?>
<input type="text" name="wpmjgu_ssh_working_directory" id="wpmjgu_ssh_working_directory" value="<?php print $wpmjgu_ssh_working_directory; ?>" />
    <?php       
}

public function ssh_working_directory_check($ssh_working_directory)
{
    $wpmjgu_jpeg_encoder_location = get_option("wpmjgu_jpeg_encoder_location");
    if ($wpmjgu_jpeg_encoder_location === 'local') 
    {
        return $this->validation_ok("wpmjgu_ssh_working_directory", $ssh_working_directory);
    }    

    global $wpmjgu_func;
    $ssh_working_directory = trim($ssh_working_directory);
    
    return $this->validation_ok("wpmjgu_ssh_working_directory", $ssh_working_directory);
    
    
}




//------------------------------------------



public function mozjpeg_encoder_directory_show()
{
    $wpmjgu_mozjpeg_encoder_directory = $this->load_setting('wpmjgu_mozjpeg_encoder_directory', '/opt/mozjpeg/bin');
    ?>
<input type="text" name="wpmjgu_mozjpeg_encoder_directory" id="wpmjgu_mozjpeg_encoder_directory" value="<?php print $wpmjgu_mozjpeg_encoder_directory; ?>" />
    <?php       
}


public function mozjpeg_encoder_directory_check($mozjpeg_encoder_directory)
{
    global $wpmjgu_func;
    $mozjpeg_encoder_directory = trim($mozjpeg_encoder_directory);
    $mozjpeg_encoder_directory = rtrim($mozjpeg_encoder_directory, "/");    

    if (! in_array(get_option("wpmjgu_mode"), array('mozjpeg', 'mozjpeg_vs_webp')) )
    {
        return $this->validation_ok("wpmjgu_mozjpeg_encoder_directory", $mozjpeg_encoder_directory);
    }    
    
    if (! $mozjpeg_encoder_directory)
    {
        return $this->validation_error("wpmjgu_mozjpeg_encoder_directory", $mozjpeg_encoder_directory, "Define directory with MozJpeg binaries");
    }    
    
    
    if (get_option("wpmjgu_jpeg_encoder_location") === 'local') 
    {    
        $error = $wpmjgu_func->validate->mozjpeg_encoder_directory_local($mozjpeg_encoder_directory);
        if ($error)
        {
            return $this->validation_error("wpmjgu_mozjpeg_encoder_directory", $mozjpeg_encoder_directory, $error);
        }
        else
        {
            return $this->validation_ok("wpmjgu_mozjpeg_encoder_directory", $mozjpeg_encoder_directory);
        }
    
    }
    else
    {
        // will be validated during ssh connection check
        return $this->validation_ok("wpmjgu_mozjpeg_encoder_directory", $mozjpeg_encoder_directory);
    }
    
}



//------------------------------------------



public function guetzli_encoder_directory_show()
{
    $wpmjgu_guetzli_encoder_directory = $this->load_setting('wpmjgu_guetzli_encoder_directory', '/opt/guetzli');
    ?>
<input type="text" name="wpmjgu_guetzli_encoder_directory" id="wpmjgu_guetzli_encoder_directory" value="<?php print $wpmjgu_guetzli_encoder_directory; ?>" />
    <?php       
}


public function guetzli_encoder_directory_check($guetzli_encoder_directory)
{
    global $wpmjgu_func;
    $guetzli_encoder_directory = trim($guetzli_encoder_directory);
    $guetzli_encoder_directory = rtrim($guetzli_encoder_directory, "/");    

    if (! in_array(get_option("wpmjgu_mode"), array('guetzli')) )
    {
        return $this->validation_ok("wpmjgu_guetzli_encoder_directory", $guetzli_encoder_directory);
    }    
    
    if (! $guetzli_encoder_directory)
    {
        return $this->validation_error("wpmjgu_guetzli_encoder_directory", $guetzli_encoder_directory, "Define directory with MozJpeg binaries");
    }    
    
    
    if (get_option("wpmjgu_jpeg_encoder_location") === 'local') 
    {    
        $error = $wpmjgu_func->validate->guetzli_encoder_directory_local($guetzli_encoder_directory);
        if ($error)
        {
            return $this->validation_error("wpmjgu_guetzli_encoder_directory", $guetzli_encoder_directory, $error);
        }
        else
        {
            return $this->validation_ok("wpmjgu_guetzli_encoder_directory", $guetzli_encoder_directory);
        }
    
    }
    else
    {
        // will be validated during ssh connection check
        return $this->validation_ok("wpmjgu_guetzli_encoder_directory", $guetzli_encoder_directory);
    }
    
}



//------------------------------------------



public function webp_encoder_directory_show()
{
    $wpmjgu_webp_encoder_directory = $this->load_setting('wpmjgu_webp_encoder_directory', '/opt/webp/bin');
    ?>
<input type="text" name="wpmjgu_webp_encoder_directory" id="wpmjgu_webp_encoder_directory" value="<?php print $wpmjgu_webp_encoder_directory; ?>" />
    <?php       
}


public function webp_encoder_directory_check($webp_encoder_directory)
{
    global $wpmjgu_func;
    $webp_encoder_directory = trim($webp_encoder_directory);
    $webp_encoder_directory = rtrim($webp_encoder_directory, "/");    

    if (! in_array(get_option("wpmjgu_mode"), array('mozjpeg_vs_webp', 'webp_duplicate')) )
    {
        return $this->validation_ok("wpmjgu_webp_encoder_directory", $webp_encoder_directory);
    }    
    
    if (! $webp_encoder_directory)
    {
        return $this->validation_error("wpmjgu_webp_encoder_directory", $webp_encoder_directory, "Define directory with webpc binaries");
    }    
    
    
    if (get_option("wpmjgu_jpeg_encoder_location") === 'local') 
    {    
        $error = $wpmjgu_func->validate->webp_encoder_directory_local($webp_encoder_directory);
        if ($error)
        {
            return $this->validation_error("wpmjgu_webp_encoder_directory", $webp_encoder_directory, $error);
        }
        else
        {
            return $this->validation_ok("wpmjgu_webp_encoder_directory", $webp_encoder_directory);
        }
    
    }
    else
    {
        // will be validated during ssh connection check
        return $this->validation_ok("wpmjgu_webp_encoder_directoryy", $webp_encoder_directory);
    }
    
}



//---------------------------------------------------


public function settings_validation_error_show()
{
    $wpmjgu_settings_validation_error = $this->load_setting('wpmjgu_settings_validation_error');
    ?>
<input type="text" name="wpmjgu_settings_validation_error" id="wpmjgu_settings_validation_error" value="<?php print $wpmjgu_settings_validation_error; ?>" />
    <?php       
}


public function settings_validation_error_check($status)
{
    
    if ($this->validation_error)
    {
        return 'true';
    }
    else
    {
        return 'false';
    }
}        


public function validation_error($setting_name, $entered_value, $error)
{
    add_settings_error($setting_name, 'error', $error, 'error');
    update_option($setting_name . '_errorvalue', $entered_value);
    $this->validation_error = true;
    return get_option($setting_name);    
}        

public function validation_ok($setting_name, $entered_value)
{
    delete_option($setting_name . '_errorvalue');
    return $entered_value;    
} 

public function load_setting($setting_name, $default = "")
{
    $errorvalue = get_option($setting_name . '_errorvalue');
    if ($errorvalue!==false)
    {
        delete_option($setting_name . '_errorvalue');
        return $errorvalue;
    }

    $value = get_option($setting_name);
    if ($value === false)
    {
        return $default;
    }    
    else
    {
        return $value;
    }
    
}        

}






  