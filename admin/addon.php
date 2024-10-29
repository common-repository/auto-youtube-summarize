<div class="wrap plugin-wrap">

    <div class="plugin-main-area">
        <ul class="nav nav-tabs">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $this->setting_url(''); ?>"><?php $this->e('Setting', '設定') ?></a>
        </li>
        <?php if(!$this->is_pro()): ?>
        <li class="nav-item">
            <a class="nav-link active" href="#"><?php $this->e('Add-ons', 'アドオン') ?></a>
        </li>
    <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo $this->setting_url('install'); ?>"><?php $this->e('Installation', 'インストール方法') ?></a>
        </li>
    </ul>
    <h2 class="mt-4 mb-4"><?php $this->e('Auto Youtube Summarize', 'Auto Youtube Sammarize') ?></h2>
    <p>
        <?php $this->e( 'Auto Youtube Summarize'); $this->e('Add-Ons' ); ?>
    </p>
    <div class="plugin_contents">

        <div class="plugin_content">
            <form action="" method="post" id="form">
                <?php wp_nonce_field( 'auto-youtube-summarize' );?>
                <div class="mt-4">
                    <h3><?php $this->e('Addon\'s Feature','アドオン') ?></h3>
                    <p>
                        <?php $this->e( 'Add-Ons will enable you to do this.', 'アドオンを購入すると以下のことが行えるようになります。' ) ?>
                    </p>
                    <ul class="list-group">
                        <li class="list-group-item"><?php $this->e('Change the narrowing conditions on get movies','動画抽出の絞り込み条件を変えられます。') ?></li>
                        <li class="list-group-item"><?php $this->e('Customize post content.','動画抽出の絞り込み条件を変えられます。') ?></li>
                        <li class="list-group-item"><?php $this->e('Remove ads on every bottom of content','記事下部の広告表記を消すことができます。') ?></li>
                    </ul>  
                </div>
                <div class="mt-4">
                    <h3><?php $this->e('How to get Addon','アドオンの購入方法') ?></h3>
                    <p class="lead">- <a href="https://gum.co/BPyDE" target="_blank">Gumroad</a>
                        <small>$50</small>
                    </p>
                    <p class="lead">- <a href="https://ruana-wp.stores.jp/items/5cf3740fc843ce547a5458fe" target="_blank">STORES.JP (Japan)</a>
                        <small>¥5,000</small></p>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>