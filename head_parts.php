<?php

function head_parts($page_title,$file_title)
{
    return <<<EOM

    <meta charset="UTF-8">
    <title>$page_title</title>
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/favorite.css" rel="stylesheet">
    <link href="css/$file_title.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p&display=swap" rel="stylesheet">

    EOM;
}