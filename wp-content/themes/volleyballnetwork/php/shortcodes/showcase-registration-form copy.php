<?php
function zd_vbn_registration_form_shortcode(){
  
  // SET ERRORS VARIABLE
	$errors = '';
  
	if(isset($_REQUEST['submit_vbn_resume_form'])){
	
		$to = 'hello@volleyballnetwork.org, terry@zielke.design';
		$from = $_POST['coach_email']; 
		$name = $_POST['coach_name'];
		
		$headers = array('Content-Type: text/html; charset=UTF-8','From: '.$name.' <'.$from);
		$subject = 'Resume Submission';
		

		//Get the uploaded file information
		$name_of_uploaded_video_file = basename($_FILES['uploaded_video_file']['name']);
		// get file size
		$size_of_uploaded_video_file = $_FILES["uploaded_video_file"]["size"]/1024; //size in KBs
		// max file size
		$max_allowed_file_size = 100; // size in KB
		// check file size
		if($size_of_uploaded_video_file > $max_allowed_file_size ){
			$errors .= " Size of file should be less than $max_allowed_file_size KB.<br>";
		}
		//get the file extension
		$type_of_uploaded_video_file = substr($name_of_uploaded_video_file, strrpos($name_of_uploaded_video_file, '.') + 1);
		// validate the file extensions
		$allowed_extensions = array("doc", "docx", "txt", "pdf");
		// check file extention
		$allowed_ext = false;
		for($i=0; $i<sizeof($allowed_extensions); $i++){
			if(strcasecmp($allowed_extensions[$i],$type_of_uploaded_video_file) == 0){
				$allowed_ext = true;
			}
		}
		if(!$allowed_ext){
			$errors .= "File type must be one of the following: ".implode(',',$allowed_extensions).'<br>';
		}
		//copy the temp. uploaded file to uploads folder
		$path_of_uploaded_video_file = $upload_folder . $name_of_uploaded_video_file;
		$tmp_path = $_FILES["uploaded_video_file"]["tmp_name"];
		if(is_uploaded_video_file($tmp_path)){
		
			$filename = basename($_FILES["uploaded_video_file"]["name"]);
			rename($filename, '/wp-content/uploads/resumes/'.$filename);
			
			if(!copy($tmp_path,$path_of_uploaded_video_file)){
				$errors .= ' Error while copying the uploaded file.<br>';
			}
		}

		
		// validate email address
    if (filter_var($from, FILTER_VALIDATE_EMAIL)) {
      // do nothing if the email address is valid
		}
		else{
			$errors .= ' Email address is not a valid format.<br>';
		}
		
		// validate name field
		if(!$name){
  		$errors .= ' Name field is empty.<br>';
		}
		
		/*
			IF THERE ARE NO ERRORS
		*/
		if($errors == ''){
			
			// message to vbn
			$msg = '
			<html>
				<body style="margin:0;padding:0;">
					<table style="border-spacing:0;border-collapse: collapse;width:100%;margin-bottom: 40px;">
  					<tr style="width:100%;background:#eeeeee;">
  					  <th style="text-align:left; border-bottom:1px solid #eee;color:black;vertical-align:top;padding:5px;">Showcase Registration Submission</th>
  					  <td style="text-align:left; border-bottom:1px solid #eee;color:black;vertical-align:top;padding:5px;"></td>
  					</tr>
						<tr style="width:100%;background:#ffffff;">
							<th style="text-align:left; border-bottom:1px solid #eee;color:black;vertical-align:top;padding:5px;width:200px;"><strong style="white-space:nowrap;">Applicant Name </strong></th>
							<td style="border-bottom:1px solid #eee;color:black;vertical-align:top;padding:5px;">'.$_REQUEST['coach_name'].'</td>
						</tr>
						<tr style="width:100%;background:#eeeeee;">
							<th style="border-bottom:1px solid #eee; text-align:left;vertical-align:top;padding:5px;width:200px;"><strong style="white-space:nowrap;">Email </strong></th>
							<td style="border-bottom:1px solid #eee;color:black;vertical-align:top;padding:5px;">'.$_REQUEST['coach_email'].'</td>
						</tr>
						<tr style="width:100%;background:#ffffff;">
							<th style="border-bottom:1px solid #eee;color:black; text-align:left;vertical-align:top;padding:5px;width:200px;"><strong style="white-space:nowrap;">Cover Letter </strong></th>
							<td style="border-bottom:1px solid #eee;color:black;vertical-align:top;padding:5px;">'.$_REQUEST['cv_introduction'].'</td>
						</tr>
					</table>
				</body>
			</html>';
		
			$mail_attachment = array($path_of_uploaded_video_file);   
			
			wp_mail($to, $subject, $msg, $headers, $mail_attachment);
		
      unlink($filename);
		}
	}
	
	$HTML = '<section class="custom-form">';

						if(isset($_REQUEST['submit_vbn_resume_form'])){
  						if ($errors == '') {
  							// success message
							  $HTML .= '<div class="form-notice green">Thank you for your registration. Your email has been received and is being processed. We will be in touch shortly.</div>';
							}
							else{
  							// error message
  							$HTML .= '<div class="form-notice red">'.$errors.'</div>';
							}
						}
					
            $HTML .= '
            <form action="" method="post" enctype="multipart/form-data">
            	<fieldset>
            		<table class="cols_2">
            			<tr>
            				<td class="col col_1">
								<label for="coach_name">Name</label>
            					<input type="text" name="coach_name" id="coach_name" maxlength="30" placeholder="Name" required>
            				</td>
            				<td class="col col_2">
								<label for="coach_email">Email</label>
            					<input  type="text" name="coach_email" id="coach_email" maxlength="50" placeholder="Email" required>
            				</td>
            			</tr>
            		</table>

            		<table class="cols_2">
            			<tr>
            				<td class="col col_1">
								<label for="coach_university_name">University Name</label>
            					<input type="text" name="coach_university_name" id="coach_university_name" maxlength="30" required>
            				</td>
            				<td class="col col_2">
								<label for="coach_university_position">University Position</label>
            					<input  type="text" name="coach_university_position" id="coach_university_position" maxlength="50" required>
            				</td>
            			</tr>
            		</table>
            	
            		<table class="cols_1">
            			<tr>
            				<td class="col col_1">
								<label for="coach_instagram_id">CV</label>
            					<input  type="text" name="coach_instagram_id" id="coach_instagram_id" maxlength="50" required>
            				</td>
            			</tr>
            		</table>
            	
            		<table class="cols_1">
            			<tr>
            				<td class="col col_1">
								<label for="cv_introduction">CV</label>
            					<textarea name="cv_introduction" id="cv_introduction" maxlength="2000" placeholder="Please enter your CV here..."></textarea>
            				</td>
            			</tr>
            		</table>
            	
            		<table class="cols_1" style="margin-bottom:10px !important;">
            			<tr>
            				<td class="col col_1">
            					<div id="fileupload">
            					<div class="fileupload">
            						<label style="margin-bottom: 7px;">Attach Introduction Video</label>
            						<input type="file" name="uploaded_video_file" id="uploaded_video_file">
            					</div>
            					</div>
            					<sup>File must be a .MOV, .M4V, .MKV, or .MP4.</sup>
            				</td>
            			</tr>
            		</table>
            	
            		<table class="cols_1" style="margin-bottom:10px !important;">
            			<tr>
            				<td class="col col_1">
            					<div id="fileupload">
            					<div class="fileupload">
            						<label style="margin-bottom: 7px;">Attach Profile Photo</label>
            						<input type="file" name="uploaded_image_file" id="uploaded_image_file">
            					</div>
            					</div>
            					<sup>File must be a .JPG, or .PNG.</sup>
            				</td>
            			</tr>
            		</table>
            		
            	</fieldset>
            	<input type="submit" name="submit_vbn_resume_form" id="submit_vbn_resume_form" class="btn solid submit_button" value="Register">
            </form>
          </section>';

return $HTML;
}
add_shortcode('vbn_registration_form', 'zd_vbn_registration_form_shortcode');
?>