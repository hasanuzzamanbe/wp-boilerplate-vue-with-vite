<div>
    <h3>Honorable Authors</h3>
</div>
<ul style="display: flex; min-width:100%; justify-content: center; flex-wrap: wrap;">
    <?php
        foreach ($authorList as $author) {
            ob_start();
            ?>
            <li class="wpm_post_authors">
                <a href='<?php echo $author['url']; ?>'> <img width='100px' src='<?php echo $author['avatar']; ?>' /></a>
                <p><?php echo $author['name']; ?></p>
            </li>
            <?php
            echo ob_get_clean();
        }
    ?>
</ul>
<br/>
<br/>
<h3>Publish post on WP Plugin  Vue Tailwind</h3>
<p>You can publish your posts on WP Plugin  Vue Tailwind! Seems interesting, sign up and post something of your own.
    Please feel free to contact an admin if your profile does not have Author access.</p>
<p>Ask admin for author access by submitting this form bellow!</i></p><i>
