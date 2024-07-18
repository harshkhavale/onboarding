<?php
function confirm_query($result_set, $conn)
{
    if (!$result_set) {
        die("Database query failed: " . mysqli_error($conn));
    }
}
function get_all_subjects($conn, $public = true)
{
    $query = "SELECT * ";
    $query .= "FROM subjects ";
    if ($public) {
        $query .= "WHERE visible = 1 ";
    }
    $query .= "ORDER BY position ASC";
    $subject_set = mysqli_query($conn, $query);
    confirm_query($subject_set, $conn);
    return $subject_set;
}

function get_pages_for_subject($conn, $subject_id, $public = true)
{
    $query = "SELECT * ";
    $query .= "FROM pages ";
    $query .= "WHERE subject_id = {$subject_id} ";
    if ($public) {
        $query .= "AND visible = 1 ";
    }
    $query .= "ORDER BY position ASC";
    $page_set = mysqli_query($conn, $query);
    confirm_query($page_set, $conn);
    return $page_set;
}


function get_subject_by_id($sub_id, $conn)
{
    $query = "SELECT * FROM subjects WHERE id={$sub_id}";
    $result_set = mysqli_query($conn, $query);
    confirm_query($result_set, $conn);
    if ($subject = mysqli_fetch_array($result_set)) {
        return $subject;
    } else {
        return NULL;
    }
}
function get_page_by_id($page_id, $conn)
{
    $query = "SELECT * FROM pages WHERE id={$page_id}";
    $result_set = mysqli_query($conn, $query);
    confirm_query($result_set, $conn);
    if ($page = mysqli_fetch_array($result_set)) {
        return $page;
    } else {
        return NULL;
    }
}
function find_selected_page($conn)
{
    global $sel_subj, $sel_subject_data, $sel_page, $sel_page_data;
    $sel_subj = 0;
    $sel_subject_data = null;
    $sel_page = 0;
    $sel_page_data = null;
    if (isset($_GET['subj'])) {
        $sel_subj = $_GET["subj"];
        $sel_subject_data = get_subject_by_id($sel_subj, $conn);
        $sel_page = 0;
        $sel_page_data = null;
    } elseif (isset($_GET['page'])) {
        $sel_subj = 0;
        $sel_subject_data = null;
        $sel_page = $_GET["page"];
        $sel_page_data = get_page_by_id($sel_page, $conn);
    }
}

function navigation($conn, $sel_subject, $sel_page)
{
    $output = "<ul style=\"font-size: small;\" class=\"subjects\">";

    $subject_set = get_all_subjects($conn,false);

    while ($subjects = mysqli_fetch_assoc($subject_set)) {
        $output .= "<li";
        if ($subjects["id"] == $sel_subject) {
            $output .=  "class=\" selected\"";
        }
        $output .= "><a class='text-dark' href='edit_subject.php?subj={$subjects["id"]}'>{$subjects["menu_name"]}</a>";
        $page_set = get_pages_for_subject($conn, $subjects["id"],false);

        $output .= "<ul class=\"pages\">";

        while ($page = mysqli_fetch_assoc($page_set)) {
            $output .= "<li";
            if ($page["id"] == $sel_page) {
                $output .= "class=\"\"";
            }
            $output .= "><a class='text-secondary text-decoration-none' href='edit_page.php?page={$page["id"]}'>{$page["menu_name"]}</a></li>";
        }
        $output .= "</ul>";
        $output .= "</li>";
    }
    $output .= "</ul>";
    return $output;
}
function redirect_to($location = NULL)
{
    if ($location != NULL) {
        header("Location: " . $location);
        exit;
    }
}
function public_navigation($conn, $sel_subject, $sel_page)
{
    $output = "<ul style=\"font-size: small;\" class=\"subjects\">";

    $subject_set = get_all_subjects($conn, true);

    while ($subjects = mysqli_fetch_assoc($subject_set)) {
        $output .= "<li";
        if ($subjects["id"] == $sel_subject) {
            $output .=  "class=\" selected\"";
        }
        $output .= "><a class='text-dark text-decoration-none fs-6' href='subject.php?subj={$subjects["id"]}'>{$subjects["menu_name"]}</a>";
        $page_set = get_pages_for_subject($conn, $subjects["id"],true);

        $output .= "<ul class=\"pages\">";

        while ($page = mysqli_fetch_assoc($page_set)) {
            $output .= "<li";
            if ($page["id"] == $sel_page) {
                $output .= "class=\"\"";
            }
            $output .= "><a class='text-decoration-none text-secondary' href='page.php?page={$page["id"]}'>{$page["menu_name"]}</a></li>";
        }
        $output .= "</ul>";
        $output .= "</li>";
    }
    $output .= "</ul>";
    return $output;
}
