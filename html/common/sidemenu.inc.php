<div class="main-menu">

    <div class="item-list transparent mb-2">

        <ul>

            <li class="item-li">
                <a href="/<?php echo auth::getCurrentUserLogin(); ?>" class="custom-item-link" target="">
                    <div class="item-counter">
                        <span class="counter"></span>
                    </div>
                    <span class="item-icon">
                        <img class="_2qgu _54rt img" src="<?php echo auth::getCurrentUserPhotoUrl(); ?>" alt="" draggable="false" style="border-radius: 50%;">
                    </span>
                    <div class="item-title"><?php echo auth::getCurrentUserFullname(); ?></div>
                </a>
            </li>

        </ul>

    </div>

    <div class="item-list transparent mb-2">

        <ul>
        <!--    <li class="item-li <?php if (isset($page_id) && $page_id === 'wall') echo 'item-selected'; ?>">
                <a href="/account/wall" class="custom-item-link" target="">
                    <div class="item-counter">
                        <span class="counter"></span>
                    </div>
                    <span class="item-icon">
                        <i class="img menu-icon-news"></i>
                    </span>
                    <div class="item-title"><?php echo $LANG['page-wall']; ?></div>
                </a>
            </li>
-->
            <li class="item-li <?php if (isset($page_id) && $page_id === 'stream') echo 'item-selected'; ?>">
                <a href="/account/stream" class="custom-item-link" target="">
                    <div class="item-counter">
                        <span class="counter"></span>
                    </div>
                    <span class="item-icon"><i class="img menu-icon-stream"></i></span>
                    <div class="item-title"><?php echo $LANG['page-stream']; ?></div>
                </a>
            </li>
        </ul>
    </div>

    <div class="item-list transparent">

        <ul>

			 <li class="item-li <?php if (isset($page_id) && $page_id === 'popular') echo 'item-selected'; ?>">
                <a href="/account/popular" class="custom-item-link" target="">
                    <div class="item-counter">
                        <span class="counter"></span>
                    </div>
                    <span class="item-icon"><i class="img menu-icon-popular"></i></span>
                    <div class="item-title"><?php echo $LANG['page-popular']; ?></div>
                </a>
            </li>
		
			
            <li class="item-li <?php if (isset($page_id) && $page_id === 'my_groups') echo 'item-selected'; ?>">
                <a href="/account/groups" class="custom-item-link" target="">
                    <div class="item-counter">
                        <span class="counter"></span>
                    </div>
                    <span class="item-icon">
                        <i class="img menu-icon-groups"></i>
                    </span>
                    <div class="item-title"><?php echo $LANG['nav-communities']; ?></div>
                </a>
            </li>
	<li class="item-li <?php if (isset($page_id) && $page_id === 'search') echo 'item-selected'; ?>">
                <a href="/account/artist" class="custom-item-link" target="">
                    <div class="item-counter">
                        <span class="counter"></span>
                    </div>
                    <span class="item-icon">
                        <i class="img menu-icon-guests"></i>
                    </span>
                    <div class="item-title"><?php echo $LANG['page-artist']; ?></div>
                </a>
            </li>
			 <li class="item-li <?php if (isset($page_id) && $page_id === 'video') echo 'item-selected'; ?>">
                <a href="/account/video" class="custom-item-link" target="">
                    <div class="item-counter">
                        <span class="counter"></span>
                    </div>
                    <span class="item-icon"><i class="img menu-icon-upgrades"></i></span>
                    <div class="item-title"><?php echo $LANG['page-video']; ?></div>
                </a>
            </li>
			
			<li class="item-li <?php if (isset($page_id) && $page_id === 'event') echo 'item-selected'; ?>">
                <a href="/account/event" class="custom-item-link" target="">
                    <div class="item-counter">
                        <span class="counter"></span>
                    </div>
                    <span class="item-icon">
                        <i class="img menu-icon-news"></i>
                    </span>
                    <div class="item-title"><?php echo $LANG['page-event']; ?></div>
                </a>
            </li>
		 
			   <li class="item-li <?php if (isset($page_id) && $page_id === 'search_market') echo 'item-selected'; ?>">
                <a href="/search/market" class="custom-item-link" target="">
                    <div class="item-counter">
                        <span class="counter"></span>
                    </div>
                    <span class="item-icon">
                        <i class="img menu-icon-marketplace"></i>
                    </span>
                    <div class="item-title"><?php echo $LANG['page-market']; ?></div>
                </a>
            </li>
		

        </ul>
    </div>

</div>