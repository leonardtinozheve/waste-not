<?php
require "utilities.php";

if(!isset($_SESSION['unique_email'])){
  header("Location: login.php");
}

$email = $_SESSION['unique_email'];

$member_info = get_member_details($email);
$member_id = $member_info['member_id'];
$full_name = $member_info['name'];


if(isset($_POST['logout'])){
  logout();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  
  <style>
    .product-card {
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      transition: transform 0.2s ease;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-image {
      background-color: #f8f9fa;
      height: 150px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: #6c757d;
    }

    .product-info {
      padding: 10px;
    }

    .product-name {
      font-size: 1.1rem;
      font-weight: bold;
      margin: 0;
    }

    .product-price {
      font-size: 0.9rem;
      color: #6c757d;
    }

    .add-to-cart {
      position: absolute;
      top: 10px;
      left: 10px;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-light bg-light px-4">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1"><?php echo $full_name;?></span>
      <div class="d-flex">
        <button class="btn btn-outline-primary mx-1" data-bs-toggle="modal" data-bs-target="#addItemModal">Add Item</button>
        <button class="btn btn-outline-primary mx-1">My Items</button>
        <button class="btn btn-outline-primary mx-1">Requests</button>
        <a href="#" class="btn btn-outline-secondary mx-1" data-bs-toggle="modal" data-bs-target="#cartModal">
          <i class="fas fa-shopping-cart"></i> Cart
        </a>
        <div class="dropdown">
            <button class="btn btn-outline-secondary mx-1 dropdown-toggle" type="button" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-cog"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
              <li><a class="dropdown-item" href="profile.php" target="_blank">Profile</a></li>
              <li><form method="post"><button type="submit" class="dropdown-item" name="logout">Logout</f></form></li>
            </ul>
          </div>
      </div>
    </div>
  </nav>

  <!-- Search and Filter -->
  <div class="container my-3">
    <div class="row">
      <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Search by name">
      </div>
      <div class="col-md-4">
        <select class="form-select">
          <option selected>Dropdown</option>
          <option value="1">Option 1</option>
          <option value="2">Option 2</option>
          <option value="3">Option 3</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Product Grid -->
  <div class="container">
    <div class="row g-4">
      <!-- Product Card -->
      <div class="col-md-3">
        <div class="product-card position-relative">
          <a href="product.html" class="text-decoration-none text-dark">
            <div class="product-image">
              <i class="fas fa-box"></i> <!-- Placeholder for product image -->
            </div>
            <div class="product-info">
              <p class="product-name">Product Name</p>
   
            </div>
          </a>
          <button class="btn btn-sm btn-primary add-to-cart">
            <i class="fas fa-shopping-cart"></i>
          </button>
        </div>
      </div>
      <!-- Repeat Product Cards -->
      <div class="col-md-3">
        <div class="product-card position-relative">
          <a href="product.html" class="text-decoration-none text-dark">
            <div class="product-image">
              <i class="fas fa-box"></i> <!-- Placeholder for product image -->
            </div>
            <div class="product-info">
              <p class="product-name">Product Name</p>

            </div>
          </a>
          <button class="btn btn-sm btn-primary add-to-cart">
            <i class="fas fa-shopping-cart"></i>
          </button>
        </div>
      </div>
      
    </div>
  </div>

  <!-- Add Item Modal -->
  <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- Modal Body -->
        <div class="modal-body">
          <form method="post">
            <div class="mb-3">
              <label for="itemName" class="form-label">Item Name</label>
              <input type="text" class="form-control" id="itemName" name="name" placeholder="Enter Item Name">
            </div>
            <div class="mb-3">
              <label for="itemQuantity" class="form-label">Quantity</label>
              <input type="number" class="form-control" id="itemQuantity" name="quantity" placeholder="Enter Item Quantity">
            </div>

            <div class="mb-3">
                <label for="itemCategory" class="form-label">Category</label>
                <select class="form-select" id="itemCategory" name="category">
                  <option selected disabled>Choose a category</option>
                  <option value="electronics">Electronics</option>
                  <option value="furniture">Furniture</option>
                  <option value="clothing">Clothing</option>
                  <option value="toys">Toys</option>
                  <option value="books">Books</option>
                  <option value="sports">Sports Equipment</option>
                  <option value="beauty">Beauty Products</option>
                </select>
            </div>
            <button type="submit" name="addItem">Submit</button>
          </form>
        </div>

        
        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Cart Modal -->
  <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cartModalLabel">Cart</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Cart content goes here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Checkout</button>
        </div>
      </div>
    </div>
  </div>

      <!-- Repeat the above block for more products -->
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>