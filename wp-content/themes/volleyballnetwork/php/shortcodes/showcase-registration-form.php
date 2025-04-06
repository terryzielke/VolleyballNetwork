<?php
function zd_vbn_registration_form_shortcode(){
    $errors = '';
    
    if(isset($_REQUEST['submit_vbn_resume_form'])){
        $to = 'hello@volleyballnetwork.org, terry@zielke.design';
        $from = $_POST['coach_email'];
        $name = $_POST['coach_name'];
        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: '.$name.' <'.$from.'>');
        $subject = 'Resume Submission';
        $upload_folder = wp_upload_dir()['path'] . '/';
        
        $allowed_video_extensions = array("mov", "m4v", "mkv", "mp4");
        $allowed_image_extensions = array("jpg", "jpeg", "png");
        $max_video_size = 50000; // 50MB
        $max_image_size = 5000; // 5MB
        
        $attachments = [];
        
        // Function to handle file upload
        function handle_upload($file, $allowed_extensions, $max_size, $upload_folder, &$errors) {
            $file_name = basename($file['name']);
            $file_size = $file['size'] / 1024; // size in KB
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $tmp_path = $file['tmp_name'];
            $file_path = $upload_folder . $file_name;
            
            if (!in_array($file_ext, $allowed_extensions)) {
                $errors .= "Invalid file type for $file_name.<br>";
            }
            
            if ($file_size > $max_size) {
                $errors .= "Size of file $file_name should be less than $max_size KB.<br>";
            }
            
            if (empty($errors) && move_uploaded_file($tmp_path, $file_path)) {
                return $file_path;
            }
            
            return null;
        }
        
        // Handle video file
        if (!empty($_FILES['uploaded_video_file']['name'])) {
            $video_path = handle_upload($_FILES['uploaded_video_file'], $allowed_video_extensions, $max_video_size, $upload_folder, $errors);
            if ($video_path) {
                $attachments[] = $video_path;
            }
        }
        
        // Handle image file
        if (!empty($_FILES['uploaded_image_file']['name'])) {
            $image_path = handle_upload($_FILES['uploaded_image_file'], $allowed_image_extensions, $max_image_size, $upload_folder, $errors);
            if ($image_path) {
                $attachments[] = $image_path;
            }
        }
        
        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $errors .= 'Invalid email format.<br>';
        }
        if (!$name) {
            $errors .= 'Name field is empty.<br>';
        }
        
        if ($errors == '') {
            $msg = '<html><body><table><tr><th>Name</th><td>'.$name.'</td></tr>';
            $msg .= '<tr><th>Email</th><td>'.$from.'</td></tr>';
            $msg .= '<tr><th>Instagram ID</th><td>'.$_REQUEST['coach_instagram'].'</td></tr>';
            $msg .= '<tr><th>CV</th><td>'.$_REQUEST['cv_introduction'].'</td></tr></table></body></html>';
            
            wp_mail($to, $subject, $msg, $headers, $attachments);
            foreach ($attachments as $file) {
                unlink($file);
            }
        }
    }
    
    ob_start();
    ?>
    <section class="custom-form">
        <?php if (isset($_REQUEST['submit_vbn_resume_form'])): ?>
            <div class="form-notice <?= $errors == '' ? 'green' : 'red' ?>">
                <?= $errors == '' ? 'Thank you for your registration.' : $errors; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data" class="z-html-form">
            <fieldset>
                <label for="coach_name">Name</label>
                <input type="text" name="coach_name" id="coach_name" required>
                <label for="coach_email">Email</label>
                <input type="text" name="coach_email" id="coach_email" required>
                <label for="coach_instagram">Instagram ID</label>
                <input type="text" name="coach_instagram" id="coach_instagram" required>
                <label for="cv_introduction">CV</label>
                <textarea name="cv_introduction" id="cv_introduction"></textarea>
                <label>Attach Video</label>
                <input type="file" name="uploaded_video_file">
                <label>Attach Image</label>
                <input type="file" name="uploaded_image_file">
				<br>
                <input type="submit" name="submit_vbn_resume_form" value="Register" class="btn button blue">
            </fieldset>
        </form>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('vbn_registration_form', 'zd_vbn_registration_form_shortcode');
?>
