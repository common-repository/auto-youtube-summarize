<style>
    small{display: block;}
    textarea{min-width:60%;}
</style>
<div class="wrap">

    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="#"><?php $this->e('Setting', '設定') ?></a>
    </li>
    <?php if(!$this->is_pro()): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $this->setting_url('addon'); ?>"><?php $this->e('Add-ons', 'アドオン') ?></a>
        </li>
    <?php endif; ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $this->setting_url('install'); ?>"><?php $this->e('Installation', 'インストール方法') ?></a>
    </li>
</ul>
<h2 class="mt-4 mb-4"><?php $this->e('Auto Youtube Summarize','動画まとめサイト構築プラグイン') ?></h2>
<form method="post" action="options.php">
    <?php 
    settings_fields( 'ays_option_group' ); 
    do_settings_sections( 'ays_option_group' );
    ?>
    <p>
        <?php $this->e('We recommend that you turn off comment notification.'); ?>
    </p>
    <div class="row mt-4">
        <div class="col-md-2">
            <?php $this->e('Google Auth Key','Google auth キー') ?>
        </div>

        <div class="col-md-8">
            <input class="form-control" type="text" name="ays_authkey" value="<?php echo esc_attr( get_option('ays_authkey') ); ?>" />
            <p>
                <a href="https://console.cloud.google.com/?hl=ja" target="_blank">Go To Google Cloud Console</a>
            </p>
            <small>
                <a href="<?php echo $this->setting_url('guide'); ?>">
                    <?php $this->e('If you don\'t know how to get Google Auth key,please look at installation guide.','Google Auth キーの取得方法が分からない方は、こちらのインストール方法をみてください。') ?> 
                </a>
            </small>
        </div>
    </div>

    <div class="row mt-4">

        <div class="col-md-2">
            <?php $this->e('Search Keyword','検索キーワード'); ?>
        </div>

        <div class="col-md-8">
            <textarea class="form-control" name="ays_keywords" cols="30" row mt-4s="5" rows="10"><?php echo esc_attr( get_option('ays_keywords') ); ?></textarea> 
            <small>
                <?php $this->e('※ When you want to use multiple keywords, specify them with comma (,) separated.','※複数のキーワードを使いたい場合は、カンマ(,)区切りで指定します。'); ?>
            </small>
        </div>
    </div>



    <div class="row mt-4">

        <div class="col-md-2">
            <?php $this->e('Search Option','検索時のオプション'); ?>
        </div>
        <div class="col-md-8">

            <?php 
            $order = get_option('ays_order');
            $order_list = [
                ['key' => 'relevance','value' => $this->_('Related Keyword','キーワードとの関連度順')],
                ['key' => 'date','value' => $this->_('Latest','最新順')],
                ['key' => 'viewCount','value' => $this->_('Popular','人気順')],
                ['key' => 'rating','value' => $this->_('Rating','評価順')],
            ];

            if($this->is_pro()){
                foreach($order_list as $index => $value){
                    if($order == $value['key']){

                        ?>
                        <div class="mnt-radio">
                            <input class="form-control" type="radio" name="ays_order" value="<?php echo $value['key']; ?>" checked="checked"><?php echo $value['value']; ?>
                        </div>
                        <?php 
                    }else{
                        ?>
                        <div class="mnt-radio">
                            <input class="form-control" type="radio" name="ays_order" value="<?php echo $value['key']; ?>"><?php echo $value['value']; ?>
                        </div>
                        <?php
                    }
                }
            }else{
               foreach($order_list as $index => $value){
                $checked = $index == 0 ? 'checked' : '';
                ?>
                <div class="mnt-radio">
                    <input class="form-control" type="radio" name="ays_order" value="<?php echo $value['key']; ?>" <?php echo $checked; ?> readonly><?php echo $value['value']; ?>
                </div>
                <?php 
            }
        }
        ?>
        <?php $this->need_pro_addons(); ?>
    </div>
</div>



<div class="row mt-4">
    <div class="col-md-2">
        <?php $this->e('Extract only videos that were posted up to the ◯ day before','◯日前までに投稿された動画のみで抽出する'); ?>
    </div>
    <div class="col-md-8">
        <input class="form-control" type="number" name="ays_publishedAfter" value="<?php echo esc_attr( get_option('ays_publishedAfter') ); ?>" />
        <small>
            <?php $this->e('※ If you set the item here to a large value, the same video will be extracted and the frequency of updating the site will decrease. (We will not post articles with the same title.)','※ここの項目を大きい値にすると、同じ動画が抽出されてしまいサイトの更新頻度が下がります。（同じタイトルの記事は投稿しません。）'); ?>

        </small>
    </div>
</div>



<div class="row mt-4">

    <div class="col-md-2">

        <?php $this->e('Maximum number of videos to extract at one time','一度に抽出する動画の最大数'); ?>

    </div>
    <div class="col-md-8">
        <input class="form-control" type="number" name="ays_maxResults" value="<?php echo esc_attr( get_option('ays_maxResults') ); ?>" />
        <small>
            <?php $this->e('※ It is the number of video extraction for one search keyword. (Default: 5)','※1つの検索キーワードに対しての動画抽出数です。（デフォルト：5）') ?>

        </small>
    </div>
</div>




<div class="row mt-4">
    <h3><?php $this->e('Post','投稿関連') ?></h3>
</div>



<div class="row mt-4">

    <div class="col-md-2">
        <?php $this->e('Post User Name','投稿ユーザー名') ?> 
    </div>
    <div class="col-md-8">
        <input class="form-control" type="text" name="ays_commentName" value="<?php echo esc_attr( get_option('ays_commentName') ); ?>" />
    </div>
</div>



<div class="row mt-4">

    <div class="col-md-2">

        <?php $this->e('Post Comment Count','コメントを◯個表示する') ?>


    </div>
    <div class="col-md-8">
        <input class="form-control" type="number" name="ays_commentCount" value="<?php echo esc_attr( get_option('ays_commentCount') ); ?>" />
        <small>
           <?php $this->e('※ The comments at the beginning and the comments on the video are included in the number.','※冒頭のコメント、動画掲載のコメントも数に含まれます。') ?>
       </small>
   </div>
</div>


<div class="row mt-4">

    <div class="col-md-2">
        <?php $this->e('Description Index','概要欄を表示させる番号') ?>
    </div>
    <div class="col-md-8">
        <input class="form-control" type="number" name="ays_descriptionIndex" value="<?php echo esc_attr( get_option('ays_descriptionIndex') ); ?>" />
    </div>
</div>


<div class="row mt-4">

    <div class="col-md-2">
        <?php $this->e('First Comment in Post','記事最初のコメント（投稿内）'); ?>
    </div>
    <div class="col-md-8">
        <textarea class="form-control" name="ays_postFirstContent" cols="30" row mt-4s="7" rows="10"><?php echo get_option('ays_postFirstContent'); ?></textarea> 
        <small>
            <?php $this->e('※HTML tag enable','※HTMLが利用できます。') ?>
        </small>
    </div>
</div>


<div class="row mt-4">

    <div class="col-md-2">
     <?php $this->e('Second Comment in Post','2番目の動画掲載時につくコメント（投稿内）'); ?>
 </div>
 <div class="col-md-8">
    <textarea class="form-control" name="ays_postMovieDescription" cols="30" row mt-4s="7" rows="10"><?php echo get_option('ays_postMovieDescription'); ?></textarea> 
    <small>
        <?php $this->e('※HTML tag enable','※HTMLが利用できます。') ?>
    </small>
</div>
</div>


<div class="row mt-4">

    <div class="col-md-2">

        <?php $this->e('Comment in Post','コメント（投稿内）') ?>

    </div>
    <div class="col-md-8">
        <textarea class="form-control" name="ays_postTemplateText" cols="30" row mt-4s="7" rows="10"><?php echo get_option('ays_postTemplateText'); ?></textarea> 
        <small>
            <?php $this->e('※ HTML · The following substitution characters can be used. (Use br if you want to use line breaks in sentences)','※HTML・下記の置換文字が利用できます。（文章内で改行を使いたい場合はbrを利用してください）') ?>
        </small>
        <small>
            <?php $this->e('※ You can enter multiple comments by line feed.') ?>
        </small>
        <div>
           <p style="font-size:12px;color:grey;margin:6px 0;">%id% =&gt; <?php $this->e('Movie ID','動画ID') ?></p>
           <p style="font-size:12px;color:grey;margin:6px 0;">%like_count% =&gt; <?php $this->e('Movie High rating','動画高評価') ?></p>
           <p style="font-size:12px;color:grey;margin:6px 0;">%dislike_count% =&gt; <?php $this->e('Movie Low rating','動画低評価') ?></p>
           <p style="font-size:12px;color:grey;margin:6px 0;">%comment_count% =&gt; <?php $this->e('Comment Count','コメント数') ?></p>
           <p style="font-size:12px;color:grey;margin:6px 0;">%title% =&gt; <?php $this->e('Movie Title','動画タイトル') ?></p>
           <p style="font-size:12px;color:grey;margin:6px 0;">%description% =&gt; <?php $this->e('Movie Description','動画説明文') ?></p>
           <p style="font-size:12px;color:grey;margin:6px 0;">%publish_at% =&gt; <?php $this->e('Upload Date','投稿日時') ?></p>
           <p style="font-size:12px;color:grey;margin:6px 0;">%search_keyword% =&gt; <?php $this->e('Search Keyword','検索ワード') ?></p>
           <p style="font-size:12px;color:grey;margin:6px 0;">%viewCount% =&gt; <?php $this->e('View Count','閲覧数') ?></p>
           <p style="font-size:12px;color:grey;margin:6px 0;">%channel_title% =&gt; <?php $this->e('Channel Title','チャンネル名') ?></p>
       </div>
   </div>
</div>


<div class="row mt-4">

    <div class="col-md-2">

        <?php $this->e('Comments after posting the video summary (in the post)') ?>

    </div>
    <div class="col-md-8">
        <?php if($this->is_pro()): ?>
            <textarea class="form-control" name="ays_afterTemplateText" cols="30" row mt-4s="7" rows="10"><?php echo get_option('ays_afterTemplateText'); ?></textarea> 
            <?php else: ?>
                <textarea class="form-control" name="ays_afterTemplateText" cols="30" row mt-4s="7" rows="10" readonly><?php echo get_option('ays_afterTemplateText'); ?></textarea> 
                <?php $this->need_pro_addons(); ?>
            <?php endif ?>
            <small>
                <?php $this->e('※ HTML · The following substitution characters can be used. (Use br if you want to use line breaks in sentences)','※HTML・下記の置換文字が利用できます。（文章内で改行を使いたい場合はbrを利用してください）') ?>
            </small>
            <small>
                <?php $this->e('※ You can enter multiple comments by line feed.') ?>
            </small>
            <div>
                <p style="font-size:12px;color:grey;margin:6px 0;">%id% =&gt; <?php $this->e('Movie ID','動画ID') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%like_count% =&gt; <?php $this->e('Movie High rating','動画高評価') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%dislike_count% =&gt; <?php $this->e('Movie Low rating','動画低評価') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%comment_count% =&gt; <?php $this->e('Comment Count','コメント数') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%title% =&gt; <?php $this->e('Movie Title','動画タイトル') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%description% =&gt; <?php $this->e('Movie Description','動画説明文') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%publish_at% =&gt; <?php $this->e('Upload Date','投稿日時') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%search_keyword% =&gt; <?php $this->e('Search Keyword','検索ワード') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%viewCount% =&gt; <?php $this->e('View Count','閲覧数') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%channel_title% =&gt; <?php $this->e('Channel Title','チャンネル名') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%description_index% =&gt; <?php $this->e('Description Index','概要欄の投稿番号') ?></p>
            </div>
        </div>
    </div>



    <div class="row mt-4">
        <h3><?php $this->e('Post Comments') ?></h3>
    </div>


    <div class="row mt-4">

        <div class="col-md-2">
            <?php $this->e('Post Comment Count','コメントを◯個表示する') ?>
        </div>
        <div class="col-md-8">
            <input class="form-control" type="number" name="ays_postCommentCount" value="<?php echo esc_attr( get_option('ays_postCommentCount') ); ?>" />
        </div>
    </div>


    <div class="row mt-4">

        <div class="col-md-2">
            <?php $this->e('Comment Content','コメント内容') ?>
        </div>
        <div class="col-md-8">
            <textarea class="form-control" name="ays_commentTemplateText" cols="30" rows="10" row mt-4s="7"><?php echo get_option('ays_commentTemplateText'); ?></textarea> 
            <small>
                <?php $this->e('※ HTML · The following substitution characters can be used. (Use br if you want to use line breaks in sentences)','※HTML・下記の置換文字が利用できます。（文章内で改行を使いたい場合はbrを利用してください）') ?>
            </small>
            <small>
                <?php $this->e('※ You can enter multiple comments by line feed.') ?>
            </small>
            <div>
                <p style="font-size:12px;color:grey;margin:6px 0;">%id% =&gt; <?php $this->e('Movie ID','動画ID') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%like_count% =&gt; <?php $this->e('Movie High rating','動画高評価') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%dislike_count% =&gt; <?php $this->e('Movie Low rating','動画低評価') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%comment_count% =&gt; <?php $this->e('Comment Count','コメント数') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%title% =&gt; <?php $this->e('Movie Title','動画タイトル') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%description% =&gt; <?php $this->e('Movie Description','動画説明文') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%publish_at% =&gt; <?php $this->e('Upload Date','投稿日時') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%search_keyword% =&gt; <?php $this->e('Search Keyword','検索ワード') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%viewCount% =&gt; <?php $this->e('View Count','閲覧数') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%channel_title% =&gt; <?php $this->e('Channel Title','チャンネル名') ?></p>
                <p style="font-size:12px;color:grey;margin:6px 0;">%description_index% =&gt; <?php $this->e('Description Index','概要欄の投稿番号') ?></p>
            </div>
        </div>
    </div>


    <div class="row mt-4">

        <div class="col-md-2">
            <?php $this->e('Post Comment User Name','コメントユーザー名') ?>
        </div>
        <div class="col-md-8">
            <textarea class="form-control" name="ays_commentTemplateName" cols="30" row mt-4s="7" rows="10"><?php echo get_option('ays_commentTemplateName'); ?></textarea>
        </div>
    </div>

</table>
<?php submit_button(); ?>
</form>
<p><a href="<?php echo admin_url(); ?>options-general.php?page=exec_ays_get_movie"><?php echo $this->e('Force Video Extraction Now','動画抽出を今すぐ強制実行') ?></a></p>
</div>
