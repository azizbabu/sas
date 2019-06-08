<?php

use App\Country;
/**
 * We will use this function for styling validation error message
 * @param string
 * @return string
 */
function validation_error($message, $elementId = '', $optional = false) {
    $myMessage = $message == "" ? "*" : "[$message]";
    if ($message == '' && $optional == true) {
        $myMessage = '';
    }
    
    $elmId = $elementId != '' ? "id=ve-" . trim($elementId) : '';
    return "<small $elmId class='validation-error'>$myMessage</small>";
}

function validationHints() {
    return "<small class='validation-error-hints pull-left'><i>All fields marked with an asterisk (*) are required.</i></small>";
}

/**
 * Display pagination summery
 *
 * @param int $totalData
 * @param int $dataPerPage
 * @param int $currentPage
 */
function getPaginationSummery($totalData, $dataPerPage, $currentPage) {
    $paginationSummery = "";
    if ($totalData > $dataPerPage) {
        if ($currentPage == 1) {
            $paginationSummery = "Showing 1 to $dataPerPage records of $totalData";
        } else {
            if (($totalData - $currentPage * $dataPerPage) > $dataPerPage) {
                $from = ($currentPage - 1) * $dataPerPage + 1;
                $to = $currentPage * $dataPerPage;
                $paginationSummery = "Showing $from to $to records of $totalData";
            } else {
                $from = ($currentPage - 1) * $dataPerPage + 1;
                $to = ($totalData - ($currentPage - 1) * $dataPerPage) + ($currentPage - 1) * $dataPerPage;
                $paginationSummery = "Showing $from to $to records of $totalData";
            }
        }
    }
    return $paginationSummery;
}


function toastMessage($message, $type = 'success') {
    return ['message' => $message, 'type' => $type];
}

function formatAmount($value) {
    return number_format((float) $value, 2, '.', '');
}

/**
 * Get total working days
 *
 * @return int
 */
function getWorkingDays($year=null, $month=null, $ignore=[])
{
    if($year == null) {
        $year = date('Y');
    }
    if($month == null) {
        $month = date('n');
    }
    if(getOption('day_off')) {
        $ignore = explode(',', getOption('day_off'));
    }
    $count = 0;
    $counter = mktime(0, 0, 0, $month, 1, $year);
    while (date("n", $counter) == $month) {
        if (in_array(date("w", $counter), $ignore) == false) {
            $count++;
        }
        $counter = strtotime("+1 day", $counter);
    }
    return $count;
}

/**
* Description: This function will return app build info
* @return string App Build Info
*/
function app_build_info(){
    $build_path = base_path('build.json');
    if (file_exists($build_path)) {
        $file_handle = fopen($build_path, "r");
        $build_info_data = fread($file_handle, filesize($build_path));
        fclose($file_handle);
        $build_info = json_decode($build_info_data, true);
        if(is_array ( $build_info )){
            $output = "";
            if( array_key_exists('build_number', $build_info) && array_key_exists('build_date', $build_info) ){
                $output .=  "v".$build_info["build_number"].".".$build_info["build_date"];
            }
            if( array_key_exists('build_branch', $build_info) && !empty($build_info["build_branch"]) ){
                $output .= " | Branch: ".$build_info["build_branch"];
            }
                
            return $output;
        }
    }
}

function getMonthlyTaskAssinedDate($day_number, $day_off)
{
    $datetime = \Carbon::now()->addDays($day_number);
    $total_day_off = count($day_off);
    if(\Carbon::now()->addDays($day_number)->diffInDays(\Carbon::now()->addDays($day_number)->startOfMonth()) == 0) {
        if(in_array($datetime->format('w'), $day_off)) {
            for ($i=1; $i <= $total_day_off; $i++) { 
                $next_datetime = $datetime->addDays($i);
                if(!in_array($next_datetime->format('w'), $day_off)) {
                    $datetime = $next_datetime;
                    break;
                } 
            }
        }

        return $datetime;
    }else {
        return false;
    }
}

function getLastMonthlyTaskAssinedDate($day_number, $day_off)
{
    $datetime = Carbon::now()->subMonths(1)->startOfMonth();
    $total_day_off = count($day_off);
    if(Carbon::now()->subMonths(1)->startOfMonth()->diffInDays(Carbon::now()->subMonths(1)->startOfMonth()->addDays($day_number)) == 0) {
        if(in_array($datetime->format('w'), $day_off)) {
            for ($i=1; $i <= $total_day_off; $i++) { 
                $next_datetime = $datetime->addDays($i);
                if(!in_array($next_datetime->format('w'), $day_off)) {
                    $datetime = $next_datetime;
                    break;
                } 
            }
        }

        return $datetime;
    }else {
        return false;
    }
}

function getSubMenus($model_name, $model_objects, array $model_ids, $url_path, $is_parent=false, $sub_menu_name='Submenu')
{
    $sub_menus = '';
    static $child_arr = [];

    if($is_parent) {
        $sub_menus .= '<li class="dropdown-submenu">
                    <a class="test waves-effect waves-light" tabindex="-1" href="#">'. $sub_menu_name .' <span class="caret caret-right"></span></a>
                    <ul class="dropdown-menu" style="display: block;">';
    }
    
    $i =1;   
    foreach($model_objects as $object) {
        
        if($model_name == 'Department') {
            $parent_ids = $object->getParentDepartmentIds($object);

            if(!array_intersect($parent_ids, $model_ids)) {
                // $children_ids = array_intersect($children_ids, $model_ids);
                // $model_ids = array_diff($model_ids, $children_ids);
                $children = $object->children;
                
                // $menu_childs
                
                if($children->isNotEmpty()) {
                    $sub_menus .= '<li class="dropdown-submenu">
                            <a class="test waves-effect waves-light" tabindex="-1" href="#">'. $object->title .' <span class="caret caret-right"></span></a>
                            <ul class="dropdown-menu" style="display: block;">';
                    
                    $sub_menus .= getSubMenus($model_name, $children, $model_ids, $url_path);

                    $sub_menus .= '</ul>
                          </li>';        
                }else {
                    $sub_menus .= '<li><a href="'. url($url_path.'/'.$object->id) .'">'. $object->title .'</a></li>';
                }
            }
        }else {
            return false;
        }
    }   

    if($is_parent) {
        $sub_menus .= '</ul>
                  </li>';
    }
    
    return $sub_menus;
}