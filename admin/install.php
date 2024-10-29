<div class="wrap">
	<ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo $this->setting_url(''); ?>"><?php $this->e('Setting', '設定') ?></a>
    </li>
    <?php if(!$this->is_pro()): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo $this->setting_url('addon'); ?>"><?php $this->e('Add-ons', 'アドオン') ?></a>
    </li>
    <?php endif; ?>
    <li class="nav-item">
        <a class="nav-link active" href="#"><?php $this->e('Installation', 'インストール方法') ?></a>
    </li>
</ul>

	<h2 class="mt-4 mb-4">
		<?php $this->e('This is Installation guide','インストール方法の説明') ?>
	</h2>
	<h4>
		<?php $this->e('1.Get API key'); ?>
	</h4>
	<p>
		<?php $this->e('If you not have Google Account,Create Google Account'); ?>
	</p>
	<p><a href="https://accounts.google.com/signup/v2/webcreateaccount"><?php $this->e('Create Google Account') ?></a></p>

	<p>
		<?php $this->e('Next,access to <a href="https://console.cloud.google.com/">Google Cloud Console</a>') ?>
	</p>
	<p>
		<?php $this->e('Select "APIs and Services"-> "Dashboard" from the top left menu.') ?>
	</p>
	<p><?php $this->e('Click "Enable API and Service" on the screen.') ?></p>	
	<p>
		<img style="max-width:50%" src="<?php echo AYS_PLUGIN_URL . '/includes/image/install_guide_1.png' ?>">
	</p>
	<p><?php $this->e('I will move to the screen to search for the API, so I will search for "Youtube Data" here, search for "Youtube Data Api v3" and activate it..') ?></p>
	<p>
		<img style="max-width:50%" src="<?php echo AYS_PLUGIN_URL . '/includes/image/install_guide_2.png' ?>">
	</p>
	<p>
		<?php $this->e('When activation is complete, from menu "API and service" → "Authentication information" → "Create authentication information" → "API key" and press to issue the API key.') ?>
	</p>
	<p>
		<img style="max-width:50%" src="<?php echo AYS_PLUGIN_URL . '/includes/image/install_guide_3.png' ?>">
	</p>
	<h4>
		<?php $this->e('2.Setting'); ?>
	</h4>
	<p>
		<?php $this->e('Please enter the API key from "Auto Youtube Summarize"-> "Google auth key" for the previously acquired APi key.') ?>
	</p>
	<p>
		<img style="max-width:50%" src="<?php echo AYS_PLUGIN_URL . '/includes/image/install_guide_4.png' ?>">
	</p>
	<p>
		<?php $this->e('Configuration is complete.'); ?>
	</p>
	<p>
		<img style="max-width:50%" src="<?php echo AYS_PLUGIN_URL . '/includes/image/install_guide_5.png' ?>">
	</p>
	<p>
		<?php $this->e('Make various settings on the corresponding page. A comment is added on each item.'); ?>
	</p>
	<ul>
		<li>
			<?php $this->e("<strong> Google auth key </strong>: Enter Google's API key.") ?>
		</li>
		<li>
			<?php $this->e("<strong> Search Keyword </strong>: Enter a keyword.") ?>
		</li>
		<li>
			<?php $this->e("<strong> Search options </strong>: Determine the video collection priority.") ?>
		</li>
		<li>
			<?php $this->e("<strong> Exclude only videos posted up to the day before </strong>: Filter by the date of video posting. (If you do not narrow down, the same video will be extracted more easily.)") ?>
		</li>
		<li>
			<?php $this->e("<strong> Maximum number of videos to extract at once </strong>: The number of videos to collect for keywords at one time. Defaults are recommended unless there is a specific reason.") ?>
		</li>
		<li>
			<?php $this->e("<strong> Post Comment name </strong>: Determines the name of the comment, such as the summary site in the article.") ?>
			
		</li>
		<li>
			<?php $this->e('<strong> Show comments per page </strong>: Determine the number of comments in the article.') ?>
		</li>
		<li>
			<?php $this->e('<strong> Number to display summary column </strong>: Decide the order of the comments for the video summary column.') ?>
		</li>
		<li>
			<?php $this->e('<strong> First article comment (within post) </strong>: Determines the content that will appear first in the article. (HTML and replacement characters can be used.)') ?>
		</li>
		<li>
			<?php $this->e('<strong> Second video comment (within post)</strong>: Determines the second comment for the article. (HTML and replacement characters can be used.)') ?>
		</li>
		<li>
			<?php $this->e('<strong> Comments (within a post) </strong>: Specify other comments. (HTML and replacement characters can be used.)') ?>
		</li>
		<li>
			<?php $this->e('<strong> Comment after posting video summary (within post)</strong>: Specify the comment after the summary column. (HTML and replacement characters can be used.)') ?>
		</li>
		<li>
			<?php $this->e('<strong> Show individual comments </strong>: Specify the number of comments to post.') ?>
		</li>
		<li>
			<?php $this->e('<strong> Comment content </strong>: Specify the content of the comment.') ?>
		</li>
		<li>
			<?php $this->e('<strong> Comment Name </strong>: Specify a name for the comment.') ?>
		</li>
	</ul>
	<h4>
		3.Tips
	</h4>
	<p><?php $this->e("This plugin is using 'wp_cron'.So, run auto posts when user access comes.") ?></p>
	<p><?php $this->e("'wp_cron' set twice daily") ?></p>
</div>