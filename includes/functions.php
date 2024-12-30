<?php
// includes/functions.php

/**
 * Send email notification
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $message Email message
 * @return bool Success status
 */
function sendEmailNotification($to, $subject, $message) {
    // Email headers
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: School Management System <noreply@school.com>',
        'Reply-To: noreply@school.com',
        'X-Mailer: PHP/' . phpversion()
    );

    // Convert message to HTML
    $htmlMessage = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #007bff; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .footer { text-align: center; padding: 20px; font-size: 0.8em; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>School Management System</h2>
            </div>
            <div class='content'>
                $message
            </div>
            <div class='footer'>
                <p>This is an automated message, please do not reply.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Send email
    return mail($to, $subject, $htmlMessage, implode("\r\n", $headers));
}

/**
 * Generate random string
 * 
 * @param int $length Length of string
 * @return string Random string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $string;
}

/**
 * Format date for display
 * 
 * @param string $date Date string
 * @return string Formatted date
 */
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

/**
 * Clean and validate file name
 * 
 * @param string $fileName Original file name
 * @return string Cleaned file name
 */
function cleanFileName($fileName) {
    // Remove any path information
    $fileName = basename($fileName);
    // Remove special characters
    $fileName = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
    // Ensure unique filename
    return time() . '_' . $fileName;
}

/**
 * Validate file upload
 * 
 * @param array $file $_FILES array element
 * @param array $allowedTypes Allowed mime types
 * @param int $maxSize Maximum file size in bytes
 * @return array [success, message]
 */
function validateFileUpload($file, $allowedTypes, $maxSize = 5242880) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [false, 'File upload failed'];
    }

    if (!in_array($file['type'], $allowedTypes)) {
        return [false, 'Invalid file type'];
    }

    if ($file['size'] > $maxSize) {
        return [false, 'File too large'];
    }

    return [true, 'File is valid'];
}