<section>
    <div class="container-fluid settings">
        <div class="row">
            <div class="col-lg-2 col-lg-offset-1">
                <div class="row">
                    <div class="col-lg-12">  
                        <?php
                        md_include_component_file( MIDRUB_BASE_ADMIN_COMPONENTS_SETTINGS . 'views/menu.php' );
                        ?>
                    </div>
                </div>                        
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-12 settings-area">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="material-icons-outlined">
                                    settings
                                </i>
                                <?php echo $this->lang->line('general_settings'); ?>
                            </div>
                            <div class="panel-body">
                                <?php settings_load_options('general'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
md_include_component_file( MIDRUB_BASE_ADMIN_COMPONENTS_SETTINGS . 'views/footer.php' );
?>