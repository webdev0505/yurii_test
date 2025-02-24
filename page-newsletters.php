<?php
/* Template Name: Newsletters */
get_header();
?>

<main class="">
<h1 class="text-29 font-semibold text-red-700 bg-header-bg flex justify-center items-center w-full h-32 "><?php echo get_the_title(); ?></h1>
    
    <div class="flex flex-col gap-6 p-4">
    <?php
$categories = array('contributor-alerts', 'regular-newsletters', 'topic-alerts'); // Your categories

foreach ($categories as $category_slug) {
    $category = get_category_by_slug($category_slug);
    $posts = get_posts(array(
        'category' => $category->term_id,
        'posts_per_page' => -1 // Get all posts in the category
    ));
    if ($category && !empty($posts)) {?>
        <div class="overflow-hidden flex-col flex">
            <div class="border-b-[1px] border-primary mb-5 pb-4">
                <h2 class="text-23 capitalize"><? echo esc_html($category->name)?></h2>
            </div>
            <div class="flex flex-col">
                <?php
                $background_image = get_post_meta($posts[0]->ID, '_post_image', true);
                if ($background_image) {
                    echo '<img src=" ' . esc_url($background_image) . '" alt="' . esc_attr($category->name) . ' Image">';
                }
                foreach ($posts as $post) {
                    echo '<div class="p-6 bg-gray-100 flex flex-col gap-6">';
                        echo '<div class="flex justify-between">';
                            echo '<div><h3 class="text-primary text-10 tracking-wider">' . esc_html($post->post_title) . '</h3></div>';
                            echo '<div class="subscribe" data-id='. $post->ID .'><img src="images/icon_image.svg" alt="Subscribe"></div>';
                        echo '</div>';
                        setup_postdata($post);
                        $post_content = wp_strip_all_tags($post->post_content);
                        $post_excerpt = mb_strimwidth($post_content, 0, 100, '...');
                        echo '<div><a href="' . get_permalink($post->ID) . '">';
                        echo '<h2 class="text-main-heading text-26">' . esc_html($post->post_title) . '</h2>';
                        echo '</a></div>';
                        echo '<div><p class=" text-16 text-tertiary mt-1">' . esc_html($post_excerpt) . '</p></div>';
                        echo '<div><p class=" text-14 text-primary mt-1 uppercase underline font-bold tracking-wider">See a preview</p></div>';
                    echo "</div>";
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
    
<?php
    }
}
?>
</div>
    <div class="fixed bottom-0 p-4 pt-5 pb-5 bg-header-bg">
        <h3 class="text-main-heading font-normal text-20">Please select the newsletters you would like to recieve
        </h3>
        <p class="text-tertiary text-16 font-light mt-1">Then enter your email and click "submit" to start receiving newsletters.
        </p>
        <form class="flex">
            <input type="email" placeholder="EMAIL@ADDRESS.COM" class="flex-1 px-4 py-2 border rounded-l-md focus:outline-none">
            <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-r-md font-semibold">SUBMIT</button>
        </form>
    
        <div class="flex items-start mt-4">
            <input type="checkbox" id="subscribe-all" class="w-5 h-5 border-2 border-blue-900 rounded-md cursor-pointer">
            <label for="subscribe-all" class="ml-2 text-gray-800 text-16">
                Sign up for all of <i class="italic font-serif">The Publication’s</i> newsletters.
            </label>
        </div>
    </div>

</main>

<?php get_footer(); ?>
