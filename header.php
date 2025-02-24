<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('•', true, 'right'); ?> <?php bloginfo('name'); ?></title>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="bg-white p-4 flex justify-end items-end border-b-[1px] border-primary">
    
    <div class="flex items-center gap-4">
        <button><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/search-icon.svg" alt="Search" class="w-3 h-3"></button>
        <button><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/user-icon.svg" alt="User" class="w-3 h-3"></button>
        <button><img src="<?php echo get_template_directory_uri(); ?>/assets/icons/menu-icon.svg" alt="Menu" class="w-3 h-3 "></button>
    </div>
</header>
