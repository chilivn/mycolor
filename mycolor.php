<?php
/**
 * Plugin Name: Đổi màu chủ đạo My Color
 * Plugin URI: http://chili.vn
 * Description: Đây là addons thay đổi màu chủ đạo cho trang web của bạn
 * Version: 1.0.0
 * Author: MAT BAO CORPORATION
 * Author URI: http://chili.vn
 * License: GPL2
 */
 
/*
 Add css custom color  to header
*/
define( 'MB_PLUGIN', __FILE__ );
define( 'MB_PLUGIN_DIR', untrailingslashit( dirname( MB_PLUGIN ) ) );
require(ABSPATH . WPINC . "/pluggable.php");
require_once MB_PLUGIN_DIR . '/functions.php';

class Mycolor {

	public function __construct(){

			global $current_user;

      get_currentuserinfo();


			if (current_user_can( 'manage_options' ))
			{
				add_action( 'wp_head', array($this,'add_mycolor_int') );
			  add_action( 'wp_footer', array($this,'add_mycolor_file') );
				add_action( 'wp_footer', array($this,'dashboard_addons'));
				add_action( 'wp_footer', array($this,'dashboard_addons'));
				add_action( 'mycolor_control', array($this,'show_button_retore_color'));
				

			}

	}
	
	/*
	===
	
	Int mycolor 

	===
	*/
	function add_mycolor_int()
	{
		?>
		<script type="text/javascript">
				jQuery(document).ready(function($){
					jQuery("body").attr({
						"ng-app": 'myApp',
						"ng-controller": 'myCtrl'
					});
				});
		</script>
		<?php
	}
	
	/*
	 Add angular js to header
	*/
	function add_mycolor_file() {
	  ?>
	  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>
	  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.23/angular-sanitize.min.js"></script>
	  <link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'css/colorpicker.min.css', __FILE__ ); ?>">
	  <link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'css/colorstyle.css', __FILE__ ); ?>">
	  <script src="<?php echo plugins_url( 'js/bootstrap-colorpicker-module.min.js', __FILE__ ); ?>"></script>
	  <?php
	}
	
	/*
	 Add layout an css code to footer
	*/

	function dashboard_addons()
	{
		//require_once Mycolor_INCLUDES."/dashbords.php";
		?>
        	
	   <div class="changeboxcolor">

			<div class="wrap-mycolor-control">
			
				<span class="my-colorcontrol-close"></span>
	            
				<h2 class="addons-heading">Đổi màu chủ đạo</h2>
				<div class="msg" ng-bind-html="message"></div>	
				<form ng-submit="submitForm()" method="post">
		
					<div class="form-group">
						<label>Màu chủ đạo</label>
						<input colorpicker="hex" name="mainColor" style="background-color:{{myColor.color_main_current}}"  type="text" ng-model="myColor.color_main_current"/>
					</div>
					<div class="form-group">
						<label>Màu chủ đạo thứ 2</label>
						<input colorpicker="hex" name="childColor" style="background-color:{{myColor.color_child_current}}"  type="text" ng-model="myColor.color_child_current"/>
					</div>
					
					<div class="form-footer">
						
						<input type="submit"  value="Đổi màu" class="btn-mycolor save-mycolor">
						
						<?php do_action("mycolor_control") ?>	
						
					</div>

				</form>
				<div class="addons_info">Phiên bản thử nghiệm v1.0</div>
				</div>
			</div>
		</div>
	
	<script>
		
	var app = angular.module('myApp',['colorpicker.module','ngSanitize']);

 
	
	app.controller('myCtrl', function($scope,$http) {
	   
		var myColor;

		$http.get('<?php echo plugins_url( 'getmycolor.php', __FILE__ ); ?>').success(function(data, status, headers, config) {
			// this callback will be called asynchronously
			// when the response is available
	
				$scope.myColor =data;
			  console.log(data);
				
		});

		
		 /* Retore color default */
		 $scope.RetoreColor=function(){

		 	 if (confirm("Bạn có chắc chắn muốn KHÔI PHỤC màu sắc chủ đạo của website ?")) {

				 	$scope.message='<span class="process">Đang khôi phục ....</span>';

					$http.post("<?php echo plugins_url( 'retore.php', __FILE__ ); ?>")
							.success(function(data)
							{

								console.log(data);
								
								$scope.message='<span class="success">Khôi phục thành công .... đang khởi động lại ...</span>';

								location.reload();
								
							})
							.error(function(data)
							{
								console.log('error');
							});

				 }; /*end then*/

		}

		/* Change color */
		$scope.message = undefined;	
		$scope.submitForm = function() {
			
			//console.log("posting data color ...."+$scope.myColor.color_main_current+" Child color => "+ $scope.myColor.color_child_current);
			
			if (confirm("Bạn có chắc chắn muốn thay đổi màu sắc chủ đạo của website ?")) {

						$scope.message='<span class="process">Đang đổi màu ....</span>';

					  $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
					  
					   data = {
					  'action' : "change-color",
						'mainColor' : $scope.myColor.color_main_current,
						'childColor' : $scope.myColor.color_child_current,
						};
						
						
					    $http.post("<?php echo plugins_url( 'savecolor.php', __FILE__ ); ?>", data)
							.success(function(data, status, headers, config)
							{

								console.log(data);

								$scope.message='<span class="success">Đổi màu thành công .... Đang tải lại trang ... </span>';
								
								location.reload();
								
							})
							.error(function(data, status, headers, config)
							{
								console.log('error');
							});
					 
				 };	

		 };/* end change color function */
		
	});
	
	
	function removehex(string)
	{
		var newString= string.replace(/#/g,"");
		
		return newString;
	}
	
	jQuery(document).ready(function(e) {
		
		jQuery('.my-colorcontrol-close').click(function(e) {
			
			jQuery('.changeboxcolor').toggleClass('toggle');
			
		});
		
	});
	
	</script>
	
	
	<!-- /angular js file -->
        <?php
	}
		
	protected function constant(){
		define('Mycolor_INCLUDES'	, 	plugin_dir_path( __FILE__ ));
	}



	public function show_button_retore_color()
	{

			if ($this->check_valid_backup_color()) {

				//var_dump($this->check_valid_backup_color());

				echo '<input type="button"  value="Khôi phục màu gốc" ng-click="RetoreColor();" class="btn-mycolor retore-mycolor">';
			}
			else
			{
						if(!$this->create_backup_color())
						{
							echo "<span class='msg'>Lỗi ! Không thể tạo backup</span>";	
						}
			}

	}

	/*
		Check backup color json in themes current
	*/
	public  function check_valid_backup_color()
	{

		$data_color=my_backup_color_data();

		$data=$data_color["file_data"];

		if(!empty($data['mainColor']))
		{
			return true;
		}
		else{
			return false;
		}

	}


	function create_backup_color()
	{

		/* only link */
		$backupfile_url = get_stylesheet_directory()."/backup-mycolor.json";

		$mycolor_data=mycolor_data();

		$data_color="";

		//echo $mycolor_data;

		if(isset($mycolor_data["mainColor"]))
		{

			$data_color='{
	    "mainColor":"'.$mycolor_data["mainColor"].'",
	    "childColor":"'.$mycolor_data["childColor"].'"}';

			    if(!empty($mycolor_data))
			   {

			   		if(file_put_contents($backupfile_url, $data_color))
						{
								return true;
						}
						else
						{
								return false;
						} 

			   }
		}


	   else
			{
						return false;
			} 
		

	}

	

}/**/

$_Mycolor = new Mycolor; // Start an instance of the plugin class 

