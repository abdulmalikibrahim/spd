<!DOCTYPE html>
<html lang="en">
      <head> <?php $this->load->view("layout/header"); ?> </head>
      <body id="page-top">
            <!-- Page Wrapper -->
            <div id="wrapper">
                  <!-- Sidebar --> 
			<?php $this->load->view("layout/sidebar"); ?>
                  <!-- End of Sidebar -->
                  <!-- Content Wrapper -->
                  <div id="content-wrapper" class="d-flex flex-column">
                        <!-- Main Content -->
                        <div id="content">
                              <!-- Topbar --> 
					<?php $this->load->view("layout/navbar"); ?>
                              <!-- End of Topbar -->
                              <!-- Begin Page Content -->
                              <div class="container-fluid">
                                    <!-- Page Heading -->
                                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                          <h1 class="h3 mb-0 text-gray-800"> <?= $title; ?> </h1>
                                    </div> 
						<?php $this->load->view("content/page/".$content) ?>
                              </div>
                              <!-- /.container-fluid -->
                        </div>
                        <!-- End of Main Content -->
                        <!-- Footer --> 
				<?php $this->load->view("layout/footer"); ?>
                        <!-- End of Footer -->
                  </div>
                  <!-- End of Content Wrapper -->
            </div>
            <!-- End of Page Wrapper -->
            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                  <i class="fas fa-angle-up"></i>
            </a> 
		<?php $this->load->view("layout/footer_js"); ?>
      </body>
</html> <?php
if(!empty($content_js)){
	$this->load->view("content/js/".$content_js); 
} 
?>
