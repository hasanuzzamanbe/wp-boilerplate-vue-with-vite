<?php

function pluginlowercase_getAvatar($email, $size)
{
    $hash = md5(strtolower(trim($email)));

    /**
     * Gravatar URL by Email
     *
     * @return html $gravatar img attributes of the gravatar image
     */
    return apply_filters('wpwvt_get_avatar',
        "https://www.gravatar.com/avatar/{$hash}?s={$size}&d=mm&r=g",
        $email
    );
}