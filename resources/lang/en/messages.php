<?php

// Ported directly from old's real resources/lang/en/messages.php
// (syndicate-website-master) - old's return_error() resolves the message
// key through Lang::get() before returning it, so this file's real text
// is part of the API's actual response body, not just an internal label.

return [

    'missing_parameter'     => 'Missing Parameters',
    'invalid_credentials'     => 'Invalid Credentials',
    'invalid_parameters'     => 'Invalid Parameters',
    'registration_error'     => 'Error in registration',
    'logout_success'     => 'You have been logged out successfully',
    'invalid_access_token'     => 'Invalid Access Token',
    'authentication_missing'     => 'Authentication Missing',
    'event_flagged'     => 'Event already flagged',
    'invalid_country'     => 'Invalid Country',
    'invalid_language'     => 'Invalid Language',
    'site_info_not_found'     => 'Site Info not found',
    'item_not_found'     => 'Item Not Found',
    'product_already_added'     => 'Product Already Added',
    'product_not_exist'     => 'Product does not exist in your library',
    'invalid_code'     => 'Invalid Code',
    'password_changed_success'     => 'Password successfully changed',
    'wrong_password'     => 'Wrong Password',
    'email_sent_success'     => 'Email sent successfully',
    'training_request_submitted_success'     => 'Training Request submitted successfully',
    'account_locked'     => 'Account Pending Verification',
    'warranty_submit_success'     => 'Warranty submitted successfully',
    'forgot_password' => 'Forgot Password',
    'reset_request' => 'You requested a password reset for your account',
    'reset_code' => 'Please use the following code in your application to reset your password',
    'sincerely' => 'Sincerely',
    'syndicate_team' => 'Syndicate Team',
    'activate_email' => 'Email Activation',
    'register_message' => 'Thank you for registering with Syndicate application!',
    'click_message' => 'Please click the below link to activate your account so you can start using our app.',
    'account_already_registered' => 'Account already Registered, please sign in',

    // Keys used by this project's ported endpoints that don't exist in
    // old's original file (old never had these exact flows) - added so
    // return_error() has real text to resolve instead of falling back to
    // the raw key.
    'invalid_parameter' => 'Invalid Parameter',

];
