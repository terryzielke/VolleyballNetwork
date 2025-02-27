<style>
#wp-admin-bar-zcomp_dropdown-default{
	padding: 0 !important;
}
#wp-admin-bar-zcomp_dropdown-default li{
	background: hsla(0,0%,0%,0);
	border-bottom: 1px solid hsla(0,0%,0%,.5);
	transition: background .2s linear;
}#wp-admin-bar-zcomp_dropdown-default li:hover{
	background: hsla(0,0%,0%,.1);
}
#wp-admin-bar-zcomp_dropdown-default li b{
	position: relative;
	display: block;
	cursor: pointer !important;
}
#wp-admin-bar-zcomp_dropdown-default li b span.colorCode{
	position: absolute;
	z-index: 1;
	top: 0;
	left: 0;
	width: 25px;
	height: 25px;
}
#wp-admin-bar-zcomp_dropdown-default li b span.colorName{
	position: relative;
	z-index: 2;
	padding-left: 30px;
	line-height: 25px;
	text-shadow: 0 0 3px hsla(0,0%,0%,.5);
}

.zcomp-settings-wrapper div.color{
	position: relative;
	padding: 80px 20px 20px;
}
.zcomp-settings-wrapper div.color input.color_name{
	position: absolute;
	top: 20px;
	left: 20px;
	padding: 10px 20px;
	width: calc(100% - 40px);
	height: 40px;
	line-height: 20px;
	-webkit-appearance: none;
	background: hsla(0,0%,100%,.3);
	border: none !important;
	border-bottom: 1px solid hsla(0,0%,0%,.2) !important;
	border-radius: 0;
	box-shadow: none !important;
	color: hsla(0,0%,0%,1);
	font-size: 20px;
	font-weight: 600;
}
.zcomp-settings-wrapper div.color .wp-picker-clear{
	height: 30px !important;
	line-height: 30px !important;
	margin-left: 5px;
	border-radius: 5px !important;
}
</style>

<h1>Company Information</h1>

<form method="post" action="options.php">
<?php
settings_errors();

// select settings group
settings_fields( 'zcomp_settings' );
do_settings_sections( 'zcomp_settings' );

// get current settings
$company_name 				= esc_attr( get_option('company_name'));
$company_slogan 			= esc_attr( get_option('company_slogan'));
$company_logo 				= esc_attr( get_option('company_logo'));
$company_address 			= esc_attr( get_option('company_address'));

$company_phone 				= esc_attr( get_option('company_phone'));
$company_fax 				= esc_attr( get_option('company_fax'));
$company_email 				= esc_attr( get_option('company_email'));

$zcomp_monday 				= esc_attr( get_option('zcomp_monday'));
$zcomp_tuesday 				= esc_attr( get_option('zcomp_tuesday'));
$zcomp_wednesday 			= esc_attr( get_option('zcomp_wednesday'));
$zcomp_thursday 			= esc_attr( get_option('zcomp_thursday'));
$zcomp_friday 				= esc_attr( get_option('zcomp_friday'));
$zcomp_saturday				= esc_attr( get_option('zcomp_saturday'));
$zcomp_sunday 				= esc_attr( get_option('zcomp_sunday'));
$zcomp_holidays				= esc_attr( get_option('zcomp_holidays'));

$zcomp_color_name_one		= esc_attr( get_option('zcomp_color_name_one'));
$zcomp_color_code_one		= esc_attr( get_option('zcomp_color_code_one'));
$zcomp_color_name_two		= esc_attr( get_option('zcomp_color_name_two'));
$zcomp_color_code_two 		= esc_attr( get_option('zcomp_color_code_two'));
$zcomp_color_name_three 	= esc_attr( get_option('zcomp_color_name_three'));
$zcomp_color_code_three 	= esc_attr( get_option('zcomp_color_code_three'));
$zcomp_color_name_four 		= esc_attr( get_option('zcomp_color_name_four'));
$zcomp_color_code_four 		= esc_attr( get_option('zcomp_color_code_four'));
$zcomp_color_name_five 		= esc_attr( get_option('zcomp_color_name_five'));
$zcomp_color_code_five 		= esc_attr( get_option('zcomp_color_code_five'));
?>
	
<section class="zielke_admin">
	<label for="company_name">Company Name</label>
	<input type="text" name="company_name" id="company_name" value="<?=$company_name?>">
	
	<label for="company_slogan">Slogan</label>
	<input type="text" name="company_slogan" id="company_slogan" value="<?=$company_slogan?>">
	
	<figure id="zcomp_logo_wrapper">
		<img src="<?=$company_logo?>">
		<input type="hidden" name="company_logo" id="company_logo" value="<?=$company_logo?>">
	</figure>
	
	<label for="company_address">Street Address</label>
	<input type="text" name="company_address" id="company_address" value="<?=$company_address?>">
</section>


<section class="zielke_admin">
	<h2>Hours</h2>
</section>


<section class="zielke_admin">
	<div class="color color1" style="background-color: <?=($zcomp_color_code_one ? $zcomp_color_code_one : '#FFFFFF')?>;">
		<input type="text" name="zcomp_color_name_one" id="zcomp_color_name_one" class="color_name" value="<?=($zcomp_color_name_one ? $zcomp_color_name_one :'')?>" placeholder="Color Name">
		<input type="text" class="colorpicker" name="zcomp_color_code_one" id="zcomp_color_code_one" value="<?=($zcomp_color_code_one ? $zcomp_color_code_one : '#FFFFFF')?>">
	</div>
	
	<div class="color color2" style="background-color: <?=($zcomp_color_code_two ? $zcomp_color_code_two : '#00FF99')?>;">
		<input type="text" name="zcomp_color_name_two" id="zcomp_color_name_two" class="color_name" value="<?=($zcomp_color_name_two ? $zcomp_color_name_two :'')?>" placeholder="Color Name">
		<input type="text" class="colorpicker" name="zcomp_color_code_two" id="zcomp_color_code_two" value="<?=($zcomp_color_code_two ? $zcomp_color_code_two : '#00FF99')?>">
	</div>
	
	<div class="color color3" style="background-color: <?=($zcomp_color_code_three ? $zcomp_color_code_three : '#0099FF')?>;">
		<input type="text" name="zcomp_color_name_three" id="zcomp_color_name_three" class="color_name" value="<?=($zcomp_color_name_three ? $zcomp_color_name_three :'')?>" placeholder="Color Name">
		<input type="text" class="colorpicker" name="zcomp_color_code_three" id="zcomp_color_code_three" value="<?=($zcomp_color_code_three ? $zcomp_color_code_three : '#0099FF')?>">
	</div>
	
	<div class="color color4" style="background-color: <?=($zcomp_color_code_four ? $zcomp_color_code_four : '#FF3399')?>;">
		<input type="text" name="zcomp_color_name_four" id="zcomp_color_name_four" class="color_name" value="<?=($zcomp_color_name_four ? $zcomp_color_name_four :'')?>" placeholder="Color Name">
		<input type="text" class="colorpicker" name="zcomp_color_code_four" id="zcomp_color_code_four" value="<?=($zcomp_color_code_four ? $zcomp_color_code_four : '#FF3399')?>">
	</div>
	
	<div class="color color5" style="background-color: <?=($zcomp_color_code_five ? $zcomp_color_code_five : '#000000')?>;">
		<input type="text" name="zcomp_color_name_five" id="zcomp_color_name_five" class="color_name" value="<?=($zcomp_color_name_five ? $zcomp_color_name_five :'')?>" placeholder="Color Name">
		<input type="text" class="colorpicker" name="zcomp_color_code_five" id="zcomp_color_code_five" value="<?=($zcomp_color_code_five ? $zcomp_color_code_five : '#000000')?>">
	</div>
</section>

<?php submit_button('Save Colors','primary','zcomp_save_button'); ?>

</form>