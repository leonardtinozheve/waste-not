<?php
// Turn on all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// Require the connection file
require "connection.php";

function create_member($name, $phone, $email, $password, $school)
{
    global $conn;

    $query = "SELECT * FROM member WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0)
    {
        return "Email already exists";
    }

    $member_id = uniqid('member_');
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO member (member_id, name, phone, email,school, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $member_id, $name, $phone, $email, $school, $hashed_password);

    // 
    if($stmt->execute())
    {
        return true;
    }
    else
    {
        return $stmt->error;
    }
}


function login($email, $password)
{
    global $conn;

    $sql = "SELECT * FROM member WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['member_id'] = $row['member_id'];
            return true;
        }
    }
    return false;
}
function logout()
{
    session_destroy();
    header('Location: login.php');
}

function add_item($item_name, $item_quantity, $item_category, $member_id, $desctiption, $image_url){
    global $conn;

    // add image to the images folder
    $item_id = uniqid('item_');
    $status = 1;
    $sql = "INSERT INTO items (item_id, name, quantity, member_id, category,description, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssissssi', $item_id, $item_name, $item_quantity, $member_id, $item_category,$desctiption, $image_url, $status);

    if($stmt->execute())
    {
        return true;
    }
    else
    {
        return false;
    }
}

function get_items_to_shop($member_id)
{
    global $conn;

    $sql = "SELECT * FROM items WHERE status = 1 AND member_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    else
    {
        return [];
    }
}

function get_my_items($member_id)
{
    global $conn;

    $sql = "SELECT * FROM items WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    else
    {
        return [];
    }
}

function get_item_info($item_id)
{
    global $conn;

    $sql = "SELECT * FROM items WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        return $result->fetch_assoc();
    }
    else
    {
        return [];
    }
}

function update_item($item_id, $item_name, $item_quantity, $item_category, $member_id)
{
    global $conn;

    $sql = "UPDATE items SET quantity = ? WHERE item_id = ? AND member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $item_quantity,  $item_id, $member_id);

    if($stmt->execute())
    {
        return true;
    }
    else
    {
        return false;
    }
}

function get_requests_to_me($member_id, $school)
{
    global $conn;

    $sql = "SELECT * FROM requests WHERE member_to = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    else
    {
        return [];
    }
}

function get_requests_from_me($member_id, $school)
{
    global $conn;

    $sql = "SELECT * FROM requests WHERE member_from = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    else
    {
        return [];
    }
}

function update_requests($request_id, $status)
{
    global $conn;

    $sql = "UPDATE requests SET status = ? WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $status, $request_id);

    if($stmt->execute())
    {
        return true;
    }
    else
    {
        return false;
    }
}

function get_request_info($request_id)
{
    global $conn;

    $sql = "SELECT * FROM requests WHERE request_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        return $result->fetch_assoc();
    }
    else
    {
        return [];
    }
}

function get_member_details($email){
    global $conn;

    $sql = "SELECT name, phone, member_id, school, email FROM member WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        return $result->fetch_assoc();
    }
    else
    {
        return [];
    }
}

function get_member_details_by_id($member_id){
    global $conn;

    $sql = "SELECT name, phone, member_id, school, email FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        return $result->fetch_assoc();
    }
    else
    {
        return [];
    }
}

function get_seller_details($seller_id){
    global $conn;

    $sql = "SELECT name, phone, member_id, school, email FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        return $result->fetch_assoc();
    }
    else
    {
        return [];
    }
}

function create_request($member_from, $member_to, $item_id, $quantity)
{
    global $conn;

    $request_id = uniqid('request_');
    $status = 0; // Assuming 0 is the default status for a new request
    $sql = "INSERT INTO requests (request_id, member_from, member_to, item_id, quantity, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssii', $request_id, $member_from, $member_to, $item_id, $quantity, $status);

    if($stmt->execute())
    {
        return true;
    }
    else
    {
        return false;
    }
}

function who_is_member($member_id)
{
    global $conn;

    $sql = "SELECT name FROM member WHERE member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    else
    {
        return null;
    }
}

function who_is_item($item_id)
{
    global $conn;

    $sql = "SELECT name FROM items WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    else
    {
        return null;
    }
}

function delete_item($item_id, $member_id)
{
    global $conn;

    $sql = "DELETE FROM items WHERE item_id = ? AND member_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $item_id, $member_id);

    if($stmt->execute())
    {
        return true;
    }
    else
    {
        return false;
    }
}
?>