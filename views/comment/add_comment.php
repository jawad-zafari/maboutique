<?php

$productInfo   = $data['productInfo'] ?? [];
$commentInfo   = $data['commentInfo'] ?? [];
$params        = $data['params'] ?? [];
$commentParams = $data['commentParams'] ?? [];
$csrfToken     = $data['csrf_token'] ?? '';
$productId     = (int)($productInfo['id'] ?? 0);
?>

<div class="comment-container">
    <form method="post" action="<?= URL ?>AddComment/saveComment/<?= $productId ?>" class="comment-form" id="formComment">
        
        <!-- SÉCURITÉ : Jeton CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $this->e($csrfToken) ?>">

       