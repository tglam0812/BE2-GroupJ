<?php
include 'header.php';

$user = new User();
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page > 0 ? $page : 1;

$limit = 5;
$offset = ($page - 1) * $limit;

if ($searchTerm) {
    $getAllUsers = $user->searchUsers($searchTerm, $limit, $offset);
    $totalUsers = $user->getTotalSearchUsers($searchTerm);
} else {
    $getAllUsers = $user->getUsersByPage($limit, $offset);
    $totalUsers = $user->getTotalUsers();
}

$totalPages = ceil($totalUsers / $limit);
?>
<!-- Breadcome Area -->
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
                                    <h2>Users</h2>
                                    <p>Welcome to Nalika <span class="bread-ntd">Admin Template</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                            <div class="header-top-menu tabl-d-n">
                                <div class="breadcome-heading">
                                    <form role="search" method="get" class="form-inline">
                                        <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search Users..." class="form-control">
                                        <button type="submit"><i class="fa fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Users Status Area -->
<div class="product-status mg-b-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap">
                    <h4>Users List</h4>
                    <div class="add-product">
                        <a href="user-add.php">Add User</a>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" id="error-message">
                            <?php
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                var errorMessage = document.getElementById('error-message');
                                if (errorMessage) {
                                    errorMessage.style.display = 'none';
                                }
                            }, 5000);
                        };
                    </script>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                        <?php foreach ($getAllUsers as $value): ?>
                            <tr>
                                <td><?php echo $value['id'] ?></td>
                                <td><?php echo $value['name'] ?></td>
                                <td><?php echo $value['email'] ?></td>
                                <td><?php echo $value['role'] ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($value['created_at'])) ?></td>
                                <td>
                                    <button data-toggle="tooltip" title="Edit" class="pd-setting-ed" onclick="window.location.href='user-edit.php?id=<?php echo $value['id']; ?>'">
                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                    </button>
                                    <form action="delete.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $value['id']; ?>">
                                        <button type="submit" data-toggle="tooltip" title="Delete" class="pd-setting-ed" onclick="return confirm('Bạn có muốn xóa user có tên <?php echo $value['name'] ?>?');">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="custom-pagination">
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?search=<?php echo urlencode($searchTerm); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                                    </li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?search=<?php echo urlencode($searchTerm); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?search=<?php echo urlencode($searchTerm); ?>&page=<?php echo $page + 1; ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>
<!-- End footer -->