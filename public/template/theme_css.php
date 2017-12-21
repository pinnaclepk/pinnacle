<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

  $Defaultcolor = "#004766";
  //$Defaultcolor = "#FFFFFF";
?>
<style>
.single_color,
.upper_menu,
.single_color .login_page
{
	background-color:<?php echo $Defaultcolor; ?>;
}
.single_color .menu_nav ul li ul,
.single_color .menu_nav ul#Menu1 li ul,
.single_color .menu_nav ul li:hover ul,
.single_color .menu_nav ul#Menu1 li:hover ul,
.single_color input[type=submit],
.single_color input[type=button],
.single_color input[type=reset]
{
	border:1px solid <?php echo $Defaultcolor; ?>;
}
.single_color .menu_nav ul li:hover ul li a:hover,
.single_color .ui-tabs .ui-tabs-nav li.ui-tabs-active a
{
	color: <?php echo $Defaultcolor; ?> !important;
}
.single_color .new_fieldset_container,
.single_color .ui-tabs-panel
{
	border-color:<?php echo $Defaultcolor; ?>;
}
.single_color .ui-tabs .ui-tabs-nav li.ui-tabs-active
{
	border-top: 3px solid <?php echo $Defaultcolor; ?> !important;
	border-left:1px solid <?php echo $Defaultcolor; ?>;
	border-right:1px solid <?php echo $Defaultcolor; ?>;
}
.single_color .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active 
{ 
	border-bottom: 1px  solid <?php echo $Defaultcolor; ?> !important;  
}

</style>