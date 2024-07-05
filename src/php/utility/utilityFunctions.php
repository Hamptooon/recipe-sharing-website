<?php
    function calculateTotalCookingTime($hours, $minutes) {
        return $hours * 60 + $minutes;
    }
    function showAlert($messageTitle){
        session_start();
        if(isset($_SESSION[$messageTitle]) && $_SESSION[$messageTitle]){
            echo '
                <div class="alert">' .
                    $_SESSION[$messageTitle] . 
                    '<div class="alert__close-btn" onclick="this.parentElement.style.display=\'none\';">Ок</div>
                </div>';
        }
        unset($_SESSION[$messageTitle]); 
    }

    function deleteFile($file_path){
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
?>