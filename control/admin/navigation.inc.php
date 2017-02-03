        <nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="dashboard.php"><img src="images/logo.png" alt=""/></a>
            </div>
            <!-- /.navbar-header -->
            <ul class="nav navbar-nav navbar-right">
			    <li class="dropdown">
	        		<a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><img src="images/1.png"><span class="badge">9</span></a>
	        		<ul class="dropdown-menu">					
						<li class="m_2"><a href="logout.php"><i class="fa fa-lock"></i> Logout</a></li>	
	        		</ul>
	      		</li>
			</ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="dashboard.php"><i class="fa fa-dashboard fa-fw nav_icon"></i>Dashboard</a>
                        </li>
                        <?php
                          if ($admin_role == "Owner") {
                        ?>							  
						<li>
                            <a href="#"><i class="fa fa-laptop nav_icon"></i>Admin Users<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="manage-admin-users.php">Manage Users</a>
                                </li>
								<li>
                                    <a href="add-new-admin-user.php">Add New User</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<?php }
						?>
						<li>
                            <a href="#"><i class="fa fa-indent nav_icon"></i>Ambassadors<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="pending-users.php">Pending Users</a>
                                </li>
                                <li>
                                    <a href="approve-users.php">Approve Users</a>
                                </li>
								<li>
                                    <a href="manage-users.php">Manage Users</a>
                                </li>
								<li>
                                    <a href="add-bonus.php">Add Bonus Points</a>
                                </li>
                                <li>
                                    <a href="view-user-details.php">View User Details</a>
                                </li>
								<?php
                                   if ($admin_role == "Owner") {
                                ?>
								<li>
                                    <a href="download-user-table.php">Download User Table</a>
                                </li>
								<?php }
						        ?>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-envelope nav_icon"></i>Tasks<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="add-new-task.php">Add New Task</a>
                                </li>
                                <li>
                                    <a href="manage-valid-tasks.php">Manage Valid Tasks</a>
                                </li>
								<li>
                                    <a href="bulk-upload-tasks.php">Bulk Upload Tasks</a>
                                </li>
								<li>
                                    <a href="view-task-details.php">View Task Details</a>
                                </li>
								<li>
                                    <a href="export-tasks.php">Export Tasks</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-flask nav_icon"></i>Task Assign Management<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="task-assign.php">Bulk Upload Assign</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-check-square-o nav_icon"></i>Redeem<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="requested-rewards.php">Requested Rewards</a>
                                </li>
                                <li>
                                    <a href="make-payments.php">Make Payments</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-table nav_icon"></i>Task Status<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="manual-submitted-task.php">Manual Submitted Tasks</a>
                                </li>
								<li>
                                    <a href="approve-manual-tasks.php">Approve Manual Tasks</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                      
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
		