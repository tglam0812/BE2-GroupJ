<?php include 'header.php';
$user = new User();
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$userInfo = $user->getUserById($userId);
?>

<!-- Header Breadcrumb -->
<div class="breadcome-area">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="breadcome-list">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="breadcomb-wp">
                                <div class="breadcomb-icon">
                                    <i class="icon nalika-home"></i>
                                </div>
                                <div class="breadcomb-ctn">
                                    <h2>Edit User</h2>
                                    <p>Welcome to Admin Panel <span class="bread-ntd">User Edit</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Error and Success Message Handling -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" id="error-message">
        <ul>
            <?php foreach ($_SESSION['error'] as $message): ?>
                <li><?php echo $message; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" id="success-message">
        <p><?php echo $_SESSION['success']; ?></p>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<script>
    window.onload = function() {
        setTimeout(function() {
            var errorMessage = document.getElementById('error-message');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 15000);

        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 15000);
    };
</script>

<!-- User Edit Form -->
<div class="single-product-tab-area mg-b-30">
    <div class="single-pro-review-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="review-tab-pro-inner">
                        <ul id="myTab3" class="tab-review-design">
                            <li class="active"><a href="#description"><i class="icon nalika-edit" aria-hidden="true"></i> Edit User</a></li>
                        </ul>

                        <form action="updateUser.php" method="post" class="form-horizontal">
                            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                            <div id="myTabContent" class="tab-content custom-product-edit">
                                <div class="product-tab-list tab-pane fade active in" id="description">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="review-content-section">
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="icon nalika-edit" aria-hidden="true"></i></span>
                                                    <input type="text" class="form-control" placeholder="Name" name="name" value="<?php echo htmlspecialchars($userInfo['name']); ?>">
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo htmlspecialchars($userInfo['email']); ?>">
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                                    <input type="password" class="form-control" placeholder="Password" name="password">
                                                </div>
                                                <div class="input-group mg-b-pro-edt">
                                                    <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                                    <select name="role" class="form-control pro-edt-select form-control-primary">
                                                        <option value="admin" <?php echo ($userInfo['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                                        <option value="user" <?php echo ($userInfo['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="text-center custom-pro-edt-ds">
                                                <button type="submit" class="btn btn-ctl-bt waves-effect waves-light m-r-10">Update User</button>
                                                <button type="button" onclick="window.location.href='user-list.php'" class="btn btn-ctl-bt waves-effect waves-light m-r-10">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>
<!-- End footer -->