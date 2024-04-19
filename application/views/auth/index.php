<div class="row page-content">
    <div class="col-lg-12 text-right">
        <a class="btn btn-danger btn-xs" href="<?php print site_url() ?>auth/logout"><i class="fa fa-power-off"></i> Log Out</a>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="card hovercard">                    
                    <div class="cardheader"> 
                        <div class="avatar">
                            <img alt="<?php print $this->session->userdata('user_name'); ?>" src="<?php print HTTP_IMAGES_PATH; ?>user-default.jpg">
                        </div>
                    </div>
                    <div class="card-body info">
                        <div class="title">
                            <?php print $userInfo['user_name']; ?>
                        </div>
                        <!-- <div class="desc"> <a target="_blank" rel="noopener">"><?php PRINT APPLICATION_NAME; ?></a></div>     -->
                        <div class="desc"><?php print $userInfo['email']; ?></div>                  
                    </div>
                </div>
            </div>
        </div>
    </div>   
</div>