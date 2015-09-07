<?php

/*
Controller name: Contact
Controller description: Basic contact form handling
*/

class JSON_API_Contact_Controller
{
    public function send_message()
    {
        global $json_api;
        $name = $json_api->query->name;
        $email = $json_api->query->email;
        $message = $json_api->query->message;
        if (!$name || !$email || !$message) {
            $json_api->error("Please fill out all form fields.");
        }
        if ($email) {
            sanitize_email($email);
        }
        if (!is_email($email)) {
            $json_api->error("Please provide a valid email.");
        }
        $email_headers = 'From: ' . $name . '<' . $email . '>' . "\r\n";
        $admin_email = get_option('admin_email');
        wp_mail($admin_email, 'Contact form submission', $message, $email_headers);
        return array(
            'name' => $name,
            'email' => $email,
            'message' => $message
        );
    }
}

?>