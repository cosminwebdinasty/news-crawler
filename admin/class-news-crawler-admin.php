<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://webdinasty.ro/
 * @since      1.0.0
 *
 * @package    News_Crawler
 * @subpackage News_Crawler/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    News_Crawler
 * @subpackage News_Crawler/admin
 * @author     Webdinasty <office@webdinasty.ro>
 */
class News_Crawler_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in News_Crawler_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The News_Crawler_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/news-crawler-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in News_Crawler_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The News_Crawler_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/news-crawler-admin.js', array( 'jquery' ), $this->version, false );

	}


	public function custom_menu_item(){
		add_menu_page('News Crawler', 'News Crawler', 'manage_options', 'news-crawler', array($this, 'dashboard_callback'), 'dashicons-download');
		add_submenu_page( 'news-crawler', 'Dowloads', 'Dowloads', 'manage_options', 'downloads-page', array($this, 'downloads_callback'));
		add_submenu_page( 'news-crawler', 'Settings', 'Settings', 'manage_options', 'settings-page', array($this, 'settings_callback'));
	}

	public function dashboard_callback(){
		echo "<h1>News Crawler</h1><hr>";
	?>

		<p>Select the category you want to download news from</p>

		<form action="" method="post">
			<select name="category">
				<option value="http://feeds.bbci.co.uk/news/world/rss.xml">World</option>
				<option value="http://feeds.bbci.co.uk/news/business/rss.xml">Business</option>
				<option value="http://feeds.bbci.co.uk/news/politics/rss.xml">Politics</option>
				<option value="http://feeds.bbci.co.uk/news/health/rss.xml">Health</option>
				<option value="http://feeds.bbci.co.uk/news/education/rss.xml">Social</option>
				<option value="http://feeds.bbci.co.uk/news/technology/rss.xml">Technology</option>
				<option value="http://feeds.bbci.co.uk/news/entertainment_and_arts/rss.xml">Entertainment & Arts</option>
			</select>
			<input type="submit" value="Download" name="submit" class="btn-primary">
		</form>

		<?php 
		
		if(isset($_POST['category'])){
			$category = $_POST['category'];

			switch($category){

				case 'http://feeds.bbci.co.uk/news/world/rss.xml':
					$category_name = 'world';
					break;

				case 'http://feeds.bbci.co.uk/news/business/rss.xml':
					$category_name = 'business';
					break;	

				case 'http://feeds.bbci.co.uk/news/politics/rss.xml':
					$category_name = 'politics';
					break;	

				case 'http://feeds.bbci.co.uk/news/health/rss.xml':
					$category_name = 'health';
					break;	

				case 'http://feeds.bbci.co.uk/news/education/rss.xml':
					$category_name = 'social';
					break;	

				case 'http://feeds.bbci.co.uk/news/technology/rss.xml':
					$category_name = 'technology';
					break;	

				case 'http://feeds.bbci.co.uk/news/entertainment_and_arts/rss.xml':
					$category_name = 'entertainment';
					break;	
			}
			
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $category);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$xml = curl_exec($ch);
			curl_close($ch);
			
			$return_data = new SimpleXmlElement($xml, LIBXML_NOCDATA);
				
			/* $first_tag1 = '<header class="ssrcss-1eqcsb1-HeadingWrapper e1nh2i2l5">';
			$last_tag1 = '<section data-component="tag-list" class="ssrcss-2z3pjz-SectionWrapper e1nh2i2l2">'; */

			$first_tag = get_option('first_tag');
		    $last_tag = get_option('last_tag');

			$first_tag = (string) $first_tag;
			$last_tag = (string) $last_tag;

			$items = count($return_data->channel->item);
			for($i=0; $i<$items; $i++)
			{
				$desc = $return_data->channel->item[$i]->link;
				$title = $return_data->channel->item[$i]->title;
			
			  	$scraped_website = $this->curl($desc); 
			  	$scraped_data =  $this->scrape_between($scraped_website, $first_tag, $last_tag); 
			  
				@$DOM = new DOMDocument;
				@$DOM->loadHTML($scraped_data);
				$imgs = $DOM->getElementsByTagName('img');
			  	foreach($imgs as $item){
					$img_url = $item->getAttribute("src");

					$file_name = $title . basename($img_url);  
					$upload = wp_upload_dir();
					$uploads_dir = $upload['basedir'];
					$uploads_dir = $uploads_dir . '/curl'; 
	
					if(!file_exists($uploads_dir.'/'.$category_name.'/'.$title.'/')){
						$old = umask(0); 
						@mkdir($uploads_dir.'/'.$category_name.'/'.$title.'/', 0777, true);
						umask($old); 
						continue;
					}
					$path = $uploads_dir.'/'.$category_name.'/'.$title. '/';
			
					$this->download_image($img_url, $path.$file_name);
					continue;
			  }
			
			  	$file_name = $title;
			  	$data = $scraped_data;
				$upload = wp_upload_dir();
				$uploads_dir = $upload['basedir'];
				$uploads_dir = $uploads_dir . '/curl';

				if(!file_exists($uploads_dir.'/'.$category_name.'/'.$title.'/')){
					$old = umask(0); 
					@mkdir($uploads_dir.'/'.$category_name.'/'.$title.'/', 0777, true);
					umask($old); 
					continue;
				}

				$file_path = $uploads_dir.'/'.$category_name.'/'.$title.'/';
				file_put_contents($file_path.'/'.$file_name.'.html', $data);
				continue;
			}
		}

		if(isset($_POST['submit'])){
			echo "<br> <b style='color: #2271b1;'><span class='dashicons dashicons-saved'></span> Done<br>  The news from the <span style='color: #2271b1;'>'" . ucfirst($category_name) . "'</span> category have been downloaded.</b>";
			echo "<br>Go to 'Downloads' to upload the posts based on the downloads from this category.";
		}
	}

	// cURL function
	public function curl($url) {
	$options = Array(
		CURLOPT_RETURNTRANSFER => TRUE,  
		CURLOPT_FOLLOWLOCATION => TRUE, 
		CURLOPT_AUTOREFERER => TRUE,
		CURLOPT_CONNECTTIMEOUT => 120,   
		CURLOPT_TIMEOUT => 120,  
		CURLOPT_MAXREDIRS => 10, 
		CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  
		CURLOPT_URL => $url, 

	);
		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$data = curl_exec($ch); 
		curl_close($ch);  
		return $data;   
	}

	//Scraping function
	public function scrape_between($data, $start, $end){
		$data = stristr($data, $start);
		$data = substr($data, strlen($start));  
		$stop = stripos($data, $end);   
		$data = substr($data, 0, $stop);   
		return $data;   
	}

	//Download images
    public function download_image($image_url, $image_file){
        $fp = fopen ($image_file, 'w+'); 
        $ch = curl_init($image_url);
        curl_setopt($ch, CURLOPT_FILE, $fp);        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);      
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        curl_exec($ch);
        curl_close($ch);                              
        fclose($fp);                                  
    }

public function downloads_callback(){
	?>

	<h1>Downloads</h1><hr>
	<p>Upload the posts from the categories you downloaded news from.</p>

	<form action="" method="post">
		<select name="category">
			<option value="http://feeds.bbci.co.uk/news/world/rss.xml">World</option>
			<option value="http://feeds.bbci.co.uk/news/business/rss.xml">Business</option>
			<option value="http://feeds.bbci.co.uk/news/politics/rss.xml">Politics</option>
			<option value="http://feeds.bbci.co.uk/news/health/rss.xml">Health</option>
			<option value="http://feeds.bbci.co.uk/news/education/rss.xml">Social</option>
			<option value="http://feeds.bbci.co.uk/news/technology/rss.xml">Technology</option>
			<option value="http://feeds.bbci.co.uk/news/entertainment_and_arts/rss.xml">Entertainment & Arts</option>
		</select>
		<input type="submit" value="Upload Posts" name="submit" class="btn-primary">
	</form>

<?php



if(isset($_POST['category'])){
	$category = $_POST['category'];

			switch($category){

				case 'http://feeds.bbci.co.uk/news/world/rss.xml':
					$category_name = 'world';
					break;

				case 'http://feeds.bbci.co.uk/news/business/rss.xml':
					$category_name = 'business';
					break;	

				case 'http://feeds.bbci.co.uk/news/politics/rss.xml':
					$category_name = 'politics';
					break;	

				case 'http://feeds.bbci.co.uk/news/health/rss.xml':
					$category_name = 'health';
					break;	

				case 'http://feeds.bbci.co.uk/news/education/rss.xml':
					$category_name = 'social';
					break;	

				case 'http://feeds.bbci.co.uk/news/technology/rss.xml':
					$category_name = 'technology';
					break;	

				case 'http://feeds.bbci.co.uk/news/entertainment_and_arts/rss.xml':
					$category_name = 'entertainment';
					break;	
			}

	$uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'curl/'.$category_name;

	$dir = new DirectoryIterator($uploads_dir);

	foreach ($dir as $fileinfo) {
		if ($fileinfo->isDir() && !$fileinfo->isDot()) {
			echo "<br><b><span class='dashicons dashicons-saved'></span>" .$fileinfo->getFilename().'</b> - uploaded<br>';
			echo "<hr>";
	
		$img_folder = trailingslashit( wp_upload_dir()['basedir'] ) . 'curl/'.$category_name . '/' . $fileinfo->getFilename();
		$featured_img = glob($img_folder . "/*.jpg");
		
			$dir2 = opendir($uploads_dir .'/' .$fileinfo->getFilename());

			while ($file = readdir($dir2)) {
				if ($file == '.' || $file == '..') {
					continue;
				}

				$html = ($uploads_dir. '/' . $fileinfo->getFilename() . '/' . $file);
				$htmlData = file_get_contents($html); 
				
				htmlspecialchars($htmlData);

				$post_id = wp_insert_post(array (
					'post_title' => $fileinfo->getFilename(),
					'post_content' =>$htmlData,
					'post_status' => 'publish',
				));

				wp_set_object_terms( $post_id, $category_name, 'category');

				require_once(ABSPATH . 'wp-admin/includes/media.php');
				require_once(ABSPATH . 'wp-admin/includes/file.php');
				require_once(ABSPATH . 'wp-admin/includes/image.php');

				$image_url = $featured_img[0];
				$upload_dir = wp_upload_dir();
				$image_data = @file_get_contents( $image_url );
				$filename = basename( $image_url );

				$old = umask(0);
				if ( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
				}
				else {
				$file = $upload_dir['basedir'] . '/' . $filename;
				}
				umask($old); 

				@file_put_contents( $file, $image_data );

				$wp_filetype = wp_check_filetype( $filename, null );

				$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => sanitize_file_name( $filename ),
				'post_content' => '',
				'post_status' => 'inherit'
				);

				$attach_id = wp_insert_attachment( $attachment, $file,  $post_id);
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				set_post_thumbnail( $post_id, $attach_id);

			//	echo $file;
				break;
			}
			closedir($dir2);
		
			
			}
		} 
	}
}


	public function settings_callback(){

		$first_tag = htmlentities(get_option('first_tag'));
		$last_tag = htmlentities(get_option('last_tag'));

		?>
		<h1>Settings</h1><hr>
		<p>Specify the tags for the interval you want to scrape data from.</p>
		<form action="" method="post">
			<label><b>Starting Tag</b></label><br>
			<input type="text" name="first_tag" style="min-width: 100%;" value="<?php echo $first_tag ?>">
			<br><br>
			<label><b>Ending Tag</b></label><br>
			<input type="text" name="last_tag" style="min-width: 100%;" value="<?php echo $last_tag ?>">
			<br><br>
			<input type="submit" value="Save Settings" name="submit_settings" class="btn-primary">
		</form>

	<?php
		if(isset($_POST['submit_settings'])){

			$first_tag = stripslashes($_POST['first_tag']);
			$last_tag =  stripslashes($_POST['last_tag']);
			update_option('first_tag', $first_tag);
			update_option('last_tag', $last_tag);

			echo "Saved changes";
			echo "<meta http-equiv='refresh' content='0'>";
		}
	}
}


