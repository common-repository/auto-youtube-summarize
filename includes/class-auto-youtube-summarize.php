<?php
/**
 * coinlabo antena press main class
 */
class AYS_GetMovie {

  public function __construct(){

  }
  /**
   * プラグインにセットされたオプションを読み込むための関数
   * @return array [setting]
   */
  private function get_movie_option_setting(){
    $settings['keywords'] = $this->delimiterToArray(get_option(AYS_PREFIX . 'keywords'),',');
    shuffle($settings['keywords']);
    $settings['order'] = get_option(AYS_PREFIX . 'order');
    $settings['auth_key'] = get_option(AYS_PREFIX . 'authkey');
    $settings['publishedAfter'] = get_option(AYS_PREFIX . 'publishedAfter');
    $settings['maxResults'] = get_option(AYS_PREFIX . 'maxResults');
    $settings['commentName'] = get_option(AYS_PREFIX . 'commentName');
    $settings['commentCount'] = get_option(AYS_PREFIX . 'commentCount');
    $settings['postCommentCount'] = get_option(AYS_PREFIX . 'postCommentCount');
    $settings['descriptionIndex'] = get_option(AYS_PREFIX . 'descriptionIndex');
    $settings['postFirstContent'] = get_option(AYS_PREFIX . 'postFirstContent');
    $settings['postMovieDescription'] = get_option(AYS_PREFIX . 'postMovieDescription');
    $settings['postTemplateText'] = $this->delimiterToArray(get_option(AYS_PREFIX . 'postTemplateText'),"\n");
    shuffle($settings['postTemplateText']);
    $settings['afterTemplateText'] = $this->delimiterToArray(get_option(AYS_PREFIX . 'afterTemplateText'),"\n");
    shuffle($settings['afterTemplateText']);
    $settings['commentTemplateText'] = $this->delimiterToArray(get_option(AYS_PREFIX . 'commentTemplateText'),"\n");
    shuffle($settings['commentTemplateText']);
    $settings['commentTemplateName'] = $this->delimiterToArray(get_option(AYS_PREFIX . 'commentTemplateName'),"\n");
    shuffle($settings['commentTemplateName']);
    return $settings;
  }
  private function delimiterToArray($str,$delimiter = ','){
    $array = explode($delimiter ,$str);
    $array = array_filter($array, "strlen");
    $array = array_filter($array, array($this, 'removeBreakLine'));
    $array = array_values($array);
    return $array;
  }
  public function removeBreakLine($str){
    return !strpos("\n",$str);
  }

  /**
   * cron実行用の関数
   * @return [type] [description]
   */
  public function run(){
    return $this->post_youtube();
  }

  private function rendering_comment($post_id,$video_data) {
    $settings = $this->get_movie_option_setting();
    $content = [];
    for ($i = 0 ; $i < $settings['postCommentCount'] ; $i++){
      if(isset($settings['commentTemplateText'][$i]) && isset($settings['commentTemplateName'][$i]))
        $content[] =  [
         'comment_author' => $settings['commentTemplateName'][$i],
         'comment_content' => $settings['commentTemplateText'][$i]
       ];
     }

     for($i = 0;$i < count($content) ; $i++ ){
      foreach($video_data as $key => $value){
        $content[$i]['comment_content'] = preg_replace('/\%'.$key.'\%/',$value,$content[$i]['comment_content']);
      }
      $mail = $this->makeRandStr(12)."@".$site;
      $commentdata = array(
        'comment_post_ID' => $post_id, 
        'comment_author' => $content[$i]['comment_author'], 
        'comment_author_email' => $mail,
        'comment_content' => $content[$i]['comment_content'], 
        'comment_parent' => 0, 
      );
      try{
        $comment_id = wp_new_comment( $commentdata );
        wp_set_comment_status( $comment_id, "approve" );
      }catch(Exception $e){
        echo $e->getMessage();
      }

    }
    return true;
  }

  public function post_youtube() {
    $settings = $this->get_movie_option_setting();
    $baseurl = 'https://www.youtube.com/watch?v=';

    if(count($settings['keywords']) > 0 && $settings['auth_key'] != ""){
      foreach ($settings['keywords'] as $q) {

        /**
         * OLDER VERSION
         */
        // $client = new Google_Client();
        // $client->setApplicationName('YouTube');
        // $client->setDeveloperKey($settings['auth_key']);
        // $youtube = new Google_Service_YouTube($client);

        // $dt = new DateTime();
        // $dt3 = $dt->modify('-'. $settings['publishedAfter'] .' day');
        // $publishedAfter = $dt3->format(DATE_RFC3339);

        // if($settings['order'] == ""){
        //   $settings['order'] = 'relevance';
        // }

        // $searchResponse = $youtube->search->listSearch('id,snippet', array(
        //   'q' => $q,
        //   'maxResults' => $settings['maxResults'],
        //   'order' => $settings['order'],
        //   'type' => 'video',
        //   'publishedAfter' => $publishedAfter
        // ));
        // 
        $list_query_array = array(
          'part' => 'id,snippet',
          'q' => $q,
          'maxResults' => $settings['maxResults'],
          'order' => $settings['order'],
          'type' => 'video',
          'publishedAfter' => $publishedAfter,
          'key' => $settings['auth_key']
        );
        $list_api_url = "https://www.googleapis.com/youtube/v3/search?".http_build_query($list_query_array);
        $list_api_get = wp_remote_get( $list_api_url );
        if ( ! is_wp_error( $list_api_get ) && $list_api_get['response']['code'] === 200 ) {
          $searchResponse = json_decode($list_api_get['body'],true);
          foreach($searchResponse['items'] as $index => $item){
            try{

              $videoId = $item['id']['videoId'];
              $video_query_array = array(
                'part' => 'snippet,statistics',
                'id' => $videoId,
                'key' => $settings['auth_key']
              );
              $video_api_url = "https://www.googleapis.com/youtube/v3/videos?" . http_build_query($video_query_array);
              $video_api_get = wp_remote_get( $video_api_url );
              if ( ! is_wp_error( $video_api_get ) && $video_api_get['response']['code'] === 200 ) {
                $listResponse = json_decode($video_api_get['body'],true);
                $video = $listResponse["items"][0];
                $video_data = [
                  'id' => $item['id']['videoId'],
                  'like_count' => $video['statistics']['likeCount'],
                  'dislike_count' => $video['statistics']['dislikeCount'],
                  'comment_count' => $video['statistics']['commentCount'],
                  'title' => $item['snippet']['title'],
                  'description' => $video['snippet']['description'],
                  'publish_at' => date('m/d H:i',  strtotime($video['snippet']['publishedAt'])),
                  'search_keyword' => $q,
                  'viewCount' => $video['statistics']['viewCount'],
                  'channel_title' => $video['snippet']['channelTitle'],
                ];
                if(!$this->get_post_by_title($video_data['title'])){


                  $content = $this->rendering_content($video_data);

                  $post = array(
                    'post_content'   => $content,
                    'post_title'     => $video_data['title'], 
                    'post_status'    => 'publish', 
                    'post_type'      => 'post',
                  ); 

                  remove_filter('content_save_pre', 'wp_filter_post_kses');

                  $post_id = wp_insert_post( $post );
                  $this->Generate_Featured_Image($post_id,$video_data['id']);
                  $this->rendering_comment( $post_id,$video_data );
                }
              }
            }catch(Exception $e){
             echo $e->getMessage();
           }
         }}
       }
     }else{
       return false;
     }

     return true;
   }


   private function Generate_Featured_Image( $post_id ,$movie_code ){
    $image_url = 'https://img.youtube.com/vi/'.$movie_code.'/hqdefault.jpg';
    $upload_dir = wp_upload_dir();

  // instead of file_get_contents
    $image_data = wp_remote_get( $image_url );

  /**
   * ERROR HANDLING
   */
  if ( ! is_wp_error( $image_data ) && $image_data['response']['code'] === 200 ) {
    $filename = $movie_code."_".basename($image_url);
    $path = $upload_dir['basedir'] . "/auto-youtube";
    if(wp_mkdir_p($path)){
      $file = $path . '/' . $filename;
    }

    // $upload_overrides = array( 'test_form' => false );
    // $movefile = wp_handle_upload( $image_data['body'], $upload_overrides );
    // var_dump($movefile);


    // not correct this
    // instead of file_put_contents($file, $image_data);
    if(WP_Filesystem()){
      global $wp_filesystem;
      $new_file_text = "{$movie_code}\n";
      $wp_filesystem->put_contents($file, $image_data['body']);
    }

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = [
      'post_mime_type' => $wp_filetype['type'],
      'post_title' => sanitize_file_name($filename),
      'post_content' => '',
      'post_status' => 'inherit'
    ];
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file);
    $res1 = wp_update_attachment_metadata( $attach_id, $attach_data );
    $res2 = set_post_thumbnail($post_id,$attach_id);
  }else{
    // ERROR
  }
}
private function render_html_template($array) {
  $settings = $this->get_movie_option_setting();
  $week = [
    $this->_('Sun','日'),
    $this->_('Mon','月'),
    $this->_('Tue','火'),
    $this->_('Wed','水'),
    $this->_('Thu','木'),
    $this->_('Fri','金'),
    $this->_('Sat','土')
  ];
  $html = "";
  foreach ($array as $index => $value) {
    $html .= '<div class="net" style="margin-top:16px;">'.($index+1);
    $html .= ':<font color="green"><b>'.$settings['commentName'].'</b></font><span style="padding-left:1rem">' .date('Y.m.d').'('.$week[date('w')].')</span></div>';
    $html .= PHP_EOL .PHP_EOL.'<div class="net"><p>'.$value.'</p></div>' .PHP_EOL . PHP_EOL;
  }
  return $html;

}


private function rendering_content($video_data) { 
  $settings = $this->get_movie_option_setting();
  $ret_content = [];
  $index = 1;

  if($settings['commentCount'] - count($ret_content) > 0 ){
    $ret_content[] = $settings['postFirstContent'];
  }

  $movie_html = '<div id="movie"><iframe width="728" height="410" src="https://www.youtube.com/embed/%id%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen=""></iframe></div>';
  $movie_desc_text = $settings['postMovieDescription'];

  if($settings['commentCount'] - count($ret_content) > 0 ){
    $ret_content[] = "<p>{$movie_desc_text}</p>{$movie_html}";
  }

  $normal_comment_count = $settings['descriptionIndex'] - count($ret_content) - 1;
  ays_debug_mylog($normal_comment_count);
  ays_debug_mylog($settings['postTemplateText']);
  ays_debug_mylog($settings['descriptionIndex']);
  for ($i = 0 ; $i < $normal_comment_count ; $i++){
    ays_debug_mylog($settings['postTemplateText'][$i]);
    if($settings['postTemplateText'][$i] != ""){
      $ret_content[] = $settings['postTemplateText'][$i];
    }
  }

  $ret_content[] = $this->_('This is description','概要') . "<blockquote>%description%</blockquote>";


  $after_comment_count = $settings['commentCount'] - count($ret_content);
  for ($i = 0 ; $i < $after_comment_count ; $i++){
    if(isset($settings['afterTemplateText'][$i])){
      $ret_content[] = $settings['afterTemplateText'][$i];
    }
  }

  foreach ($ret_content as $index => $content) {
    foreach($video_data as $key => $value){
      $ret_content[$index] = preg_replace('/\%'.$key.'\%/',$value,$ret_content[$index]);
    }
    $ret_content[$index] = preg_replace('/\%description_index\%/',$settings['descriptionIndex'],$ret_content[$index]);
  }

  /**
   * 最後にHTML化してreturn
   */
  return $this->render_html_template($ret_content);
}
  /**
   * 投稿が唯一か検索する関数
   * @param  [string] $page_title [post title]
   * @return [boolean]   [true or false]
   */
  private function get_post_by_title($page_title) {
    $q3 = new WP_Query( array( 's'=>'"'.$page_title . '"') ); 
    return $q3->have_posts();
  }
  /**
   * trim関数
   * @param  [string] $str [before sanitize]
   * @return [string]      [after sanitize]
   */
  private function __trim($str)
  {
    $str = trim($str);
    $str = preg_replace('/[^ぁ-んァ-ンーa-zA-Z一-龠\-\r、。]+/u','' ,$str);
    $str = preg_replace('/[\n\r\t]/', '', $str);
    $str = preg_replace('/\s(?=\s)/', '', $str);
    return $str;
  }
  private function makeRandStr($length) {
    $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
    $r_str = null;
    for ($i = 0; $i < $length; $i++) {
      $r_str .= $str[rand(0, count($str) - 1)];
    }
    return $r_str;
  }
  /**
   * 翻訳用
   */
  public function e( $text, $ja = null ) {
    _e( $text, $this->textdomain );
  }
  public function _( $text, $ja = null ) {
    return __( $text, $this->textdomain );
  }

}