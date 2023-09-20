

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <span class="brand-text font-weight-light">CookingFood ADMIN</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
        <?php
									$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
									$select_profile->execute([$admin_id]);
									$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
									
								?>
                <p style = "color: white;"> <?php echo  $fetch_profile['name'];?></p>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="http://localhost/teach_and_learn-to_cook/admin/admin.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Tài khoản
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Quản trị</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/accounts/add_admin.php" class="nav-link">
                  <p>Thêm quản trị</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/accounts/list_admin.php" class="nav-link">
                  <p>Danh sách quản trị</p>
                </a>
              </li>
            </ul>
            </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Khách hàng</p>
                  <i class="right fas fa-angle-left"></i>
                </a>
                <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/accounts/add_customer.php" class="nav-link">
                  <p>Thêm khách hàng</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/accounts/list_customer.php" class="nav-link">
                  <p>Danh sách khách hàng</p>
                </a>
              </li>
            </ul>
            </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Danh mục
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/category/add_category.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm danh mục</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/category/list_category.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách danh mục</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Khóa học
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/course/add_course.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm khóa học</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/course/list_course.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách khóa học</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Công thức
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/recipe/add_recipe.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Thêm công thức</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/recipe/list_recipe.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách công thức</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Liên hệ
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="http://localhost/teach_and_learn-to_cook/admin/contact/list_contact.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Danh sách liên hệ</p>
                </a>
              </li>
            </ul>
          </li>
        
        </ul>
      </nav>