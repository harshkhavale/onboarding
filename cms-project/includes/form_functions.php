<?php
function check_required_fields($required_array, $post_array) {
    $errors = array();
    foreach($required_array as $fieldname) {
        if (!isset($post_array[$fieldname]) || empty(trim($post_array[$fieldname]))) {
            $errors[] = $fieldname . " is required.";
        }
    }
    return $errors;
}
function check_max_field_length($field_length_array, $post_array) {
    $errors = array();
    foreach($field_length_array as $fieldname => $maxlength) {
        if (strlen(trim($post_array[$fieldname])) > $maxlength) {
            $errors[] = $fieldname . " can only be " . $maxlength . " characters long.";
        }
    }
    return $errors;
}
?>