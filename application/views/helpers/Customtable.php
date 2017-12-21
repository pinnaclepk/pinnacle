<?php

class Zend_View_Helper_Customtable extends Zend_View_Helper_Abstract {

    private $_translate;

    public function Customtable($pagename, $data) {
        $this->_translate = Zend_Registry::get("translate");
        $str = '';
        switch ($pagename) {
            case "timesheetapproval":
                $str = $this->timesheetapproval($data);
                break;
            case "incidentreport":
                $str = $this->incidentreport($data);
                break;
            case "timesheetreport":
                if ($data['View'] === "0")
                    $str = $this->timesheetreport($data['data']);
                else
                    $str = $this->timesheetweekreport($data['data']);
                break;
        }
        return $str;
    }

    public function timesheetapproval($data) {
        //$translate = Zend_Registry::get("translate");
        $cnt = count($data);
        $str = '<table class="table table-bordered table-hover dataTable tablegridXajax tablesorter" id="gridTable" cellspacing="0" cellpadding="0" border="0">';
        $tbodystart = '';
        $tbodyend = '';
        $total = 0;
        $int = 0;
        $dec = 0;
        for ($i = 0; $i < $cnt; $i++) {

            if ($i == 0) {
                $str .= "<thead><tr class='row'>";
                $str .= "<th id='sortfalse'><input type='checkbox' name='selectall' id='selectall' onclick='checkall(this)'></th>";
                $str .= "<th id='sortfalse'>Sr #</th>";

                $headercnt = count($data[$i]);
                for ($h = 0; $h < $headercnt; $h++) {
                    $str .= '<th class="sorting header">' . $this->_translate->_($data[$i][$h]) . '</th>';
                }

                $str .= "</tr></thead>";
            } else {
                if ($tbodystart != '') {
                    $tbodystart = "<tbody>";
                }
                $str .= $tbodystart . "<tr class='row'>";
                //if ($data[$i]['ApprovalStatus'] === 'Unapproved')
                $str .= "<td nowrap='nowrap' align='center'><input type='checkbox' class='chk' name='chkApproval' id='chkApproval_" . $data[$i]['TimesheetID'] . "' value='" . $data[$i]['TimesheetID'] . "'></td>";
//                else
//                    $str .= "<td nowrap='nowrap' align='center'>&nbsp;</td>";
                $str .= "<td nowrap='nowrap'>" . $i . "</td>";
                for ($h = 0; $h < $headercnt; $h++) {
                    //foreach($data[$i] as $key => $value)  {
                    $str .= '<td nowrap="nowrap">' . $data[$i][$data[0][$h]] . '</td>';
                    if ($data[0][$h] == "TotalTime") {
                        //$total += str_replace(":",".",$data[$i][$data[0][$h]]);
                        //$total += str_replace($find, $replace, $data[$i][$data[0][$h]]);
                        $times = explode(":", $data[$i][$data[0][$h]]);
                        $int += $times[0];
                        $dec += $times[1];
                    }
                }
                $str .= "</tr>";
            }
        }
        if (($i - 1) == 0) {
            $str .= "<tbody><tr><td colspan='" . ($headercnt + 3) . "'> No Record Found </td></tr></tbody>";
        }
        if (($i - 1) > 0) {
            $addtowhole = floor($dec / 60);
            if ($addtowhole > 0)
                $int += $addtowhole;

            $decimal = floor($dec % 60);
            $total = $int . ":" . $decimal;

//            $find1 = array(".25", ".50", ".5", ".75");
//            $replace1 = array(":15", ":30", ":30", ":45");
            //$str .= '<td colspan="11" nowrap="nowrap" align="right"><strong>Total Hours : </strong></td><td nowrap="nowrap"><strong>' . str_replace($find1, $replace1, $total) . '</strong></td>';
            $str .= '<td colspan="12" nowrap="nowrap" align="right"><strong>Total Hours : </strong></td><td nowrap="nowrap"><strong>' . $total . '</strong></td>';
            $str .= '<td nowrap="nowrap">&nbsp;</td>';
            $str .= "</tbody>";
        }
        $str .= "</table>";
        return $str;
    }

    public function incidentreport($data) {
        //$translate = Zend_Registry::get("translate");
        $cnt = count($data);
        $str = '<div class="box box-info"><div class="box-body" >';
        $str .= '<div class="box-header with-border">';
        $str .= '<h3 class="box-title"> Incident Details';
        $str .= '</h3></div>';
        if ($cnt > 0) {
            $headercnt = count($data[0]);
            for ($i = 1; $i < $cnt; $i++) {
                $str .= '<div class="box-header with-border">';
                $str .= '<h3 class="box-title" style="background-color: #efefef; width: 100%; padding:10px;"> Detail Incident ' . ($i);
                $str .= '</h3></div>';
                $str .= "<div style='width:50%;float:left;'>";
                $str .= "<table class='table'>";
                for ($j = 0; $j < 5; $j++) {
                    $str .= "<tr class='row'><th>" . $this->_translate->_($data[0][$j]) . "</th>";
                    $str .= "<td>";
                    $str .= $data[$i][$data[0][$j]];
                    $str .= "</td></tr>";
                }
                $str .= "</table>";
                $str .= "</div>";
                $str .= "<div style='width:50%;float:left;'>";
                $str .= "<table class='table'>";
                for ($j = 5; $j <= 9; $j++) {
                    $str .= "<tr class='row'><th>" . $this->_translate->_($data[0][$j]) . "</th>";
                    $str .= "<td>";
                    $str .= $data[$i][$data[0][$j]];
                    $str .= "</td></tr>";
                }
                $str .= "<tr class='row'><th>&nbsp;</th><td>&nbsp;</td></tr>";
                $str .= "</table>";
                $str .= "</div>";
            }
        } else {
            $str .= "No Incident Reported";
        }
        $str .= "</div></div>";
        return $str;
    }

    public function timesheetreport($data) {

        //$translate = Zend_Registry::get("translate");
        $cnt = count($data);
        $str = '<table class="table table-bordered table-hover dataTable tablegridXajax tablesorter" id="gridTable" cellspacing="0" cellpadding="0" border="0">';
        $tbodystart = '';
        $tbodyend = '';
        $total = 0;
        $int = 0;
        $dec = 0;
        $mbtotal = 0;
        $mbint = 0;
        $mbdec = 0;
        for ($i = 0; $i < $cnt; $i++) {

            if ($i == 0) {
                $str .= "<thead><tr class='row'>";
                //$str .= "<th id='sortfalse'><input type='checkbox' name='selectall' id='selectall' onclick='checkall(this)'></th>";
                $str .= "<th id='sortfalse'>Sr #</th>";

                $headercnt = count($data[$i]);
                for ($h = 0; $h < $headercnt; $h++) {
                    $str .= '<th class="sorting header">' . $this->_translate->_($data[$i][$h]) . '</th>';
                }

                $str .= "</tr></thead>";
            } else {
                if ($tbodystart != '') {
                    $tbodystart = "<tbody>";
                }
                $str .= $tbodystart . "<tr class='row'>";
//                if ($data[$i]['ApprovalStatus'] === 'Unapproved')
//                    $str .= "<td nowrap='nowrap' align='center'><input type='checkbox' class='chk' name='chkApproval' id='chkApproval_" . $data[$i]['TimesheetID'] . "' value='" . $data[$i]['TimesheetID'] . "'></td>";
//                else
//                    $str .= "<td nowrap='nowrap' align='center'>&nbsp;</td>";
                $str .= "<td nowrap='nowrap'>" . $i . "</td>";
                for ($h = 0; $h < $headercnt; $h++) {
                    //foreach($data[$i] as $key => $value)  {

                    if ($data[0][$h] == "TotalTime") {
                        //$total += str_replace(":",".",$data[$i][$data[0][$h]]);
                        //$total += str_replace($find, $replace, $data[$i][$data[0][$h]]);
                        $times = explode(":", $data[$i][$data[0][$h]]);
                        $int += $times[0];
                        $dec += $times[1];
                        $str .= '<td nowrap="nowrap">' . $data[$i][$data[0][$h]] . '</td>';
                    } else if ($data[0][$h] == "MealBreakTime") {
                        //$total += str_replace(":",".",$data[$i][$data[0][$h]]);
                        //$total += str_replace($find, $replace, $data[$i][$data[0][$h]]);
                        if(!empty($data[$i][$data[0][$h]]))
                            $mbtime = date("H:i", mktime(0, $data[$i][$data[0][$h]], 0, 0, 0, 0));
                        else
                            $mbtime = date("H:i", mktime(0, 0, 0, 0, 0, 0));
                        $times = explode(":", $mbtime);
                        $mbint += $times[0];
                        $mbdec += $times[1];
                        $str .= '<td nowrap="nowrap">' . $mbtime . '</td>';
                    } else {
                        $str .= '<td nowrap="nowrap">' . $data[$i][$data[0][$h]] . '</td>';
                    }
                }
                $str .= "</tr>";
            }
        }
        if (($i - 1) == 0) {
            $str .= "<tbody><tr><td colspan='" . ($headercnt + 2) . "'> No Record Found </td></tr></tbody>";
        }
        if (($i - 1) > 0) {
            $addtowhole = floor($dec / 60);
            if ($addtowhole > 0)
                $int += $addtowhole;

            $decimal = floor($dec % 60);
            $total = str_pad($int,2,0,0) . ":" . str_pad($decimal,2,0,0);

            $mbaddtowhole = floor($mbdec / 60);
            if ($mbaddtowhole > 0)
                $mbint += $mbaddtowhole;

            $mbdecimal = floor($mbdec % 60);
            $mbtotal = str_pad($mbint,2,0,0) . ":" . str_pad($mbdecimal,2,0,0);

//            $find1 = array(".25", ".50", ".5", ".75");
//            $replace1 = array(":15", ":30", ":30", ":45");
            //$str .= '<td colspan="11" nowrap="nowrap" align="right"><strong>Total Hours : </strong></td><td nowrap="nowrap"><strong>' . str_replace($find1, $replace1, $total) . '</strong></td>';
            $str .= '<td colspan="8" nowrap="nowrap" align="right"><strong>Total Hours : </strong></td><td nowrap="nowrap"><strong>' . $total . '</strong></td>';
            $str .= '<td nowrap="nowrap"><strong>' . $mbtotal . '</strong></td>';
            $str .= "</tbody>";
        }
        $str .= "</table>";
        return $str;
    }

    public function timesheetweekreport($data) {
        //$translate = Zend_Registry::get("translate");
        $cnt = count($data);
        $str = '<table class="table table-bordered table-hover dataTable tablegridXajax tablesorter" id="gridTable" cellspacing="0" cellpadding="0" border="0">';
        $tbodystart = '';
        $tbodyend = '';
        $total = 0;
        $int = array();
        $dec = array();
        $mbtotal = 0;
        $mbint = array();
        $mbdec = array();
        $tmpstr = '';
        for ($i = 0; $i < $cnt; $i++) {

            if ($i == 0) {
                $str .= "<thead><tr class='row'>";
                $str .= "<th id='sortfalse' rowspan='2'>Sr #</th>";

                $headercnt = count($data[$i]);
                for ($h = 0; $h < $headercnt; $h++) {

                    if (is_array($data[$i][$h])) {
                        if (array_key_exists("dates", $data[$i][$h])) {
                            $datecnt = count($data[$i][$h]['dates']);
                            for ($k = 0; $k < $datecnt; $k++) {
                                $str .= '<th class="sorting header" colspan="2">' . date('d/m/Y', strtotime($data[$i][$h]['dates'][$k])) . '<br/>' . date("l", strtotime($data[$i][$h]['dates'][$k])) . '</th>';
                                $tmpstr .= '<th>Total Hours</th><th>Break Hours</th>';
                            }
                        }
                    } else
                        $str .= '<th class="sorting header" rowspan="2">' . $this->_translate->_($data[$i][$h]) . '</th>';
                }

                $str .= "</tr>";
                $str .= "<tr><td colspan='5'></td>" . $tmpstr . "</tr>";
                $str .= "</thead>";
            } else {
                if ($tbodystart != '') {
                    $tbodystart = "<tbody>";
                }
                $str .= $tbodystart . "<tr class='row'>";
                $str .= "<td nowrap='nowrap'>" . $i . "</td>";
                for ($h = 0; $h < $headercnt; $h++) {

                    if (is_array($data[0][$h])) {
                        if (array_key_exists("dates", $data[0][$h])) {
                            for ($k = 0; $k < $datecnt; $k++) {
                                if (isset($data[$i][$data[0][$h]['dates'][$k]])) {
                                    $str .= '<td nowrap="nowrap">' . $data[$i][$data[0][$h]['dates'][$k]]['TotalTime'] . '</td>';
                                    $btime = !empty($data[$i][$data[0][$h]['dates'][$k]]['BreakTime']) ? $data[$i][$data[0][$h]['dates'][$k]]['BreakTime'] : 0;
                                    $mbtime = date("H:i", mktime(0, $btime, 0, 0, 0, 0));
                                    
                                    $str .= '<td nowrap="nowrap">' . $mbtime . '</td>';
                                    $times = explode(":", $data[$i][$data[0][$h]['dates'][$k]]['TotalTime']);

                                    $int[$k][] = $times[0];
                                    $dec[$k][] = $times[1];
                                    $times = explode(":", $mbtime);
                                    $mbint[$k][] = $times[0];
                                    $mbdec[$k][] = $times[1];
                                } else {
                                    $str .= '<td nowrap="nowrap"> - </td>';
                                    $str .= '<td nowrap="nowrap"> - </td>';
                                }
                            }
                        }
                    } else {
                        $str .= '<td nowrap="nowrap">' . $data[$i][$data[0][$h]] . '</td>';
                    }
                }
                $str .= "</tr>";
            }
        }
        if (($i - 1) == 0) {
            $str .= "<tbody><tr><td colspan='" . ($headercnt + ($datecnt * 2) + 1) . "'> No Record Found </td></tr></tbody>";
        }
        if (($i - 1) > 0) {
            $str .= '<td colspan="5" nowrap="nowrap" align="right"><strong>Total Hours : </strong></td>';
            //print_r($int);
            //foreach($int as $key => $val)

            for ($key = 0; $key < $datecnt; $key++) {
                //echo $key;
                if (isset($dec[$key])) {
                    $int[$key] = array_sum($int[$key]);
                    $addtowhole = floor(array_sum($dec[$key]) / 60);
                    if ($addtowhole > 0)
                        $int[$key] += $addtowhole;

                    $decimal = floor(array_sum($dec[$key]) % 60);
                    $total = str_pad($int[$key],2,0,0) . ":" . str_pad($decimal, 2, '0', 0);

                    $mbint[$key] = array_sum($mbint[$key]);
                    $mbaddtowhole = floor(array_sum($mbdec[$key]) / 60);
                    if ($mbaddtowhole > 0)
                        $mbint[$key] += $mbaddtowhole;

                    $mbdecimal = floor(array_sum($mbdec[$key]) % 60);
                    $mbtotal = str_pad($mbint[$key],2,0,0) . ":" . str_pad($mbdecimal, 2, '0');
                }
                else {
                    $total = "-";
                    $mbtotal = "-";
                }
                $str .= '<td nowrap="nowrap"><strong>' . $total . '</strong></td>';
                $str .= '<td nowrap="nowrap"><strong>' . $mbtotal . '</strong></td>';
            }
            $str .= "</tbody>";
        }
//        if (($i - 1) > 0) {
//            $addtowhole = floor($dec / 60);
//            if ($addtowhole > 0)
//                $int += $addtowhole;
//
//            $decimal = floor($dec % 60);
//            $total = $int . ":" . $decimal;
//
//            $mbaddtowhole = floor($mbdec / 60);
//            if ($mbaddtowhole > 0)
//                $mbint += $mbaddtowhole;
//
//            $mbdecimal = floor($mbdec % 60);
//            $mbtotal = $mbint . ":" . $mbdecimal;
//
////            $find1 = array(".25", ".50", ".5", ".75");
////            $replace1 = array(":15", ":30", ":30", ":45");
//            //$str .= '<td colspan="11" nowrap="nowrap" align="right"><strong>Total Hours : </strong></td><td nowrap="nowrap"><strong>' . str_replace($find1, $replace1, $total) . '</strong></td>';
//            $str .= '<td colspan="8" nowrap="nowrap" align="right"><strong>Total Hours : </strong></td><td nowrap="nowrap"><strong>' . $total . '</strong></td>';
//            $str .= '<td nowrap="nowrap"><strong>' . $mbtotal . '</strong></td>';
//        }
        $str .= "</table>";
        return $str;
    }

}

?>