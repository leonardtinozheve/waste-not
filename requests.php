<?php

require "utilities.php";

if(!isset($_SESSION['unique_email']))
{
    header('Location: login.php');
}

$email = $_SESSION['unique_email'];
$member_info = get_member_details($email);
$member_id = $member_info['member_id'];
$school = $member_info['school'];

$fromme = $_GET['fromme'] ?? 0;

if(isset($_GET['fromme']) && $_GET['fromme'] == 1){
    $requests = get_requests_to_me($member_id,$school );
}
else{
    $requests = get_requests_from_me($member_id, $school);
}

$fromme = isset($_GET['fromme']) ? $_GET['fromme'] : 0;

// echo print("<pre>".print_r($requests,true)."</pre>");


if(isset($_POST['accept'])){
    $request_id = $_POST['request_id'];
    $status = 1;
    
    echo update_requests($request_id, $status);
    header("Location: requests.php?fromme=$fromme");
}

if(isset($_POST['decline'])){
    $request_id = $_POST['request_id'];
    $status = 2;
    
    echo update_requests($request_id, $status);
    header("Location: requests.php?fromme=$fromme");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php if($fromme == 1): ?>
        <h2 class="mb-4">Requests Sent To Me</h2>
        <?php else: ?>
        <h2 class="mb-4">Requests Sent By Me</h2>
        <?php endif; ?>
        <h3>Pending Requests</h3>
        <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
            <?php if($fromme == 1): ?>
            <th scope="col">Requestor Name</th>
            <?php endif; ?>
            <th scope="col">Item Requested</th>
            <th scope="col">Quantity Requested</th>
            <th scope="col">Request Date</th>
            <?php if($fromme == 1): ?>
            <th scope="col">Action</th>
            <?php else: ?>
            <th scope="col">Status</th>
            <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach($requests as $request): ?>
            <?php if($request['status'] == 0): ?>
            <tr>
            <?php if($fromme == 1): ?>
            <td><?php echo who_is_member($request['member_from']); ?></td>
            <?php endif; ?>
            <td><?php echo who_is_item($request['item_id']); ?></td>
            <td><?php echo $request['quantity']; ?></td>
            <td><?php echo $request['updated_at']; ?></td>
            <?php if($fromme == 1): ?>
            <td>
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#acceptModal" data-request-id="<?php echo $request['request_id']; ?>">Accept</button>
            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#declineModal" data-request-id="<?php echo $request['request_id']; ?>">Decline</button>
            </td>
            <?php else: ?>
            <td>Pending</td>
            <?php endif; ?>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Accepted/Declined Requests</h3>
        <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
            <?php if($fromme == 1): ?>
            <th scope="col">Requestor Name</th>
            <?php endif; ?>
            <th scope="col">Item Requested</th>
            <th scope="col">Quantity Requested</th>
            <th scope="col">Request Date</th>
            <th scope="col">Status</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($requests as $request): ?>
            <?php if($request['status'] != 0): ?>
            <tr>
            <?php if($fromme == 1): ?>
            <td><?php echo who_is_member($request['member_from']); ?></td>
            <?php endif; ?>
            <td><?php echo who_is_item($request['item_id']); ?></td>
            <td><?php echo $request['quantity']; ?></td>
            <td><?php echo $request['updated_at']; ?></td>
            <td>
            <?php 
            switch ($request['status']) {
            case 1:
            echo "Approved";
            break;
            case 2:
            echo "Declined";
            break;
            }
            ?>
            </td>
            </tr>
            <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Accept Confirmation Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="acceptModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acceptModalLabel">
                    Confirm Accept - Request ID: <span id="acceptRequestIdTitle"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to accept this request?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="post">
                    <input type="hidden" name="request_id" id="acceptRequestId">
                    <input type="hidden" name="action" value="accept">
                    <button type="submit" class="btn btn-primary" name="accept">Accept</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Decline Confirmation Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">
                    Confirm Decline - Request ID: <span id="declineRequestIdTitle"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to decline this request?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form method="post" >
                    <input type="hidden" name="request_id" id="declineRequestId">
                    <input type="hidden" name="action" value="decline">
                    <button type="submit" class="btn btn-danger" name="decline">Decline</button>
                </form>
            </div>
        </div>
    </div>
</div>



        </table>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Attach click event listener to all buttons with data-toggle="modal"
    document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function () {
            // Get the request_id from the button
            const requestId = this.getAttribute('data-request-id');
            const targetModalId = this.getAttribute('data-target');
            const targetModal = document.querySelector(targetModalId);

            if (targetModal) {
                // Update modal title
                const modalTitle = targetModal.querySelector('.modal-title span');
                if (modalTitle) {
                    modalTitle.textContent = requestId;
                }

                // Update hidden input field
                const hiddenInput = targetModal.querySelector('input[name="request_id"]');
                if (hiddenInput) {
                    hiddenInput.value = requestId;
                }
            }
        });
    });
});
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

