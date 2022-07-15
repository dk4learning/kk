


<?php
/*-------------------------------------------------
	BlankPress - Default Blog Template
	Template Name: BlogPage Template
 --------------------------------------------------*/
 
get_header(); ?>

	<!-- Calling the page breadcums -->
    <section class="container dsp-container">
        <div class="page_navi_bg">
  <!--Start Container-->
  <div class="container_24">
    <div class="grid_24">
      <!--Start Page navi-->
      <div class="page_navi">
        <?php the_breadcrumb(); ?>
      </div>
      <!--End Page navi-->
    </div>
  </div>
  <!--End Container-->
</div>
    </section>

    <div class="dsp-content-wrapper dsp-content-wrapper-form">
    	<div class="dsp-md-8">
        	
        </div>
        <div class="dsp-md-4">
        	<?php get_sidebar(); ?>
        </div>
    </div>

    

<?php get_footer();