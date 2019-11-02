<fieldset>
    <!-- Form Name -->
    <legend>Add new admin user</legend>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label">Surname</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input  type="text" name="surname" autocomplete="off" placeholder="Surname" class="form-control" value="<?php echo ($edit) ? $admin_account['surname'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>
      <div class="form-group">
        <label class="col-md-4 control-label">First Name</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input  type="text" name="firstname" autocomplete="off" placeholder="First Name" class="form-control" value="<?php echo ($edit) ? $admin_account['firstname'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>
      <div class="form-group">
        <label class="col-md-4 control-label">Phone Number</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                <input  type="text" name="phone" autocomplete="off" placeholder="Phone Number" class="form-control" value="<?php echo ($edit) ? $admin_account['phone'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>
      <div class="form-group">
        <label class="col-md-4 control-label">Email Address</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <input  type="text" name="email" autocomplete="off" placeholder="Email Address" class="form-control" value="<?php echo ($edit) ? $admin_account['email'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>
      <div class="form-group">
        <label class="col-md-4 control-label">User name</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input  type="text" name="user_name" autocomplete="off" placeholder="user name" class="form-control" value="<?php echo ($edit) ? $admin_account['user_name'] : ''; ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-4 control-label" >Password</label>
        <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" name="passwd" autocomplete="off" placeholder="Password " class="form-control" required="" autocomplete="off">
            </div>
        </div>
    </div>
    <!-- radio checks -->
    <div class="form-group">
        <label class="col-md-4 control-label">User type</label>
        <div class="col-md-4">
            <div class="radio">
                <label>
                    <?php //echo $admin_account['admin_type'] ?>
                    <input type="radio" name="admin_type" value="super" required="" <?php echo ($edit && $admin_account['admin_type'] =='super') ? "checked": "" ; ?> /> Super admin
                </label>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="admin_type" value="admin" required="" <?php echo ($edit && $admin_account['admin_type'] =='admin') ? "checked": "" ; ?> /> Admin
                </label>
            </div>
             <div class="radio">
                <label>
                    <input type="radio" name="admin_type" value="supercashr" required="" <?php echo ($edit && $admin_account['admin_type'] =='supercashr') ? "checked": "" ; ?> /> Super Cahsier
                </label>
            </div>
             <div class="radio">
                <label>
                    <input type="radio" name="admin_type" value="cashier" required="" <?php echo ($edit && $admin_account['admin_type'] =='cashier') ? "checked": "" ; ?> /> Cash Officer
                </label>
            </div>
                <div class="radio">
                <label>
                    <input type="radio" name="admin_type" value="cust_serv" required="" <?php echo ($edit && $admin_account['admin_type'] =='cust_serv') ? "checked": "" ; ?> /> Customer Service
                </label>
            </div>
        </div>
    </div>
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label"></label>
        <div class="col-md-4">
            <button type="submit" class="btn btn-warning" >Save <span class="glyphicon glyphicon-send"></span></button>
        </div>
    </div>
</fieldset>