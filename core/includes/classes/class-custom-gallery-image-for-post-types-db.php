<?php

if ( !class_exists( 'ProjectileCRM_Plugin' ) ) {
    
    class Custom_Gallery_Image_For_Post_Types_Db
    {
        
        private static $instance;
        public $table_required_fields;
        public $table_selected_fields;
        public $table_api_key;
        
        public function __construct() {
            global $wpdb;
            //$this->table_required_fields = $wpdb->prefix.'projectile_crm_required_fields';
            //$this->table_selected_fields = $wpdb->prefix.'projectile_crm_selected_fields';
            $this->table_settings = $wpdb->prefix.'cgpt_settings';
        }
        
        public static function get_instance() {
            if ( empty( self::$instance ) ) {
            self::$instance = new self;
            }

            return self::$instance;
        }
        
        public function getThePostTypeSeleted() {
            global $wpdb;
            $sql_query = 'SELECT * FROM `'.$this->table_settings.'`';
            return $wpdb->get_results( $sql_query , OBJECT );
        }
        
        public function saveThePostTypeSeleted( $postTypeValue ) {
            global $wpdb;
            $wpdb->query('INSERT INTO `'.$this->table_settings.'` (`id`, `post_type`) 
                        VALUES 
                             (NULL, "'.$postTypeValue.'" );'); 
        }
        
        public function updateThePostTypeSeleted( $data, $where ) {
            global $wpdb;
            $wpdb->update( $this->table_settings, $data, $where );  
        }
        
        public function createTables() {
            global $wpdb;
            
            $sql = 'CREATE TABLE IF NOT EXISTS `'.$this->table_settings.'` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `post_type` varchar(100) NOT NULL,
                      UNIQUE KEY id (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

            $wpdb->query($sql);

        }
        
        function dropTables() {
            global $wpdb;
            
            $sql = 'DROP TABLE IF EXISTS `'.$this->table_settings.'`';
            $wpdb->query($sql);
        }
        
        
        
    }
}