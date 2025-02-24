<?php
function enqueue_newsletters_script() {
    wp_enqueue_script('newsletters-script', get_template_directory_uri() . '/assets/js/newsletters.js', array(), filemtime(get_template_directory() . '/assets/js/newsletters.js'));
}
add_action('wp_enqueue_scripts', 'enqueue_newsletters_script');

function enqueue_tailwind_css() {
    wp_enqueue_style('tailwind-css', get_template_directory_uri() . '/assets/css/output.css', array(), filemtime(get_template_directory() . '/assets/css/output.css'));
}
add_action('wp_enqueue_scripts', 'enqueue_tailwind_css');

function add_post_image_meta_box() {
    add_meta_box(
        'post_image_meta_box',
        'Post Image',
        'post_image_meta_box_callback',
        'post',
        'side'
    );
}
add_action('add_meta_boxes', 'add_post_image_meta_box');

function post_image_meta_box_callback($post) {
    wp_nonce_field(basename(__FILE__), 'post_image_nonce');
    $post_image = get_post_meta($post->ID, '_post_image', true);
    ?>
    <div>
        <input type="hidden" id="post_image" name="post_image" value="<?php echo esc_url($post_image); ?>" />
        <img id="post_image_preview" src="<?php echo esc_url($post_image); ?>" style="max-width: 100%; height: auto; display: <?php echo $post_image ? 'block' : 'none'; ?>;" />
        <br>
        <button type="button" class="button" id="upload_post_image">Select Image</button>
        <button type="button" class="button button-secondary" id="remove_post_image" style="display: <?php echo $post_image ? 'inline-block' : 'none'; ?>;">Remove</button>
    </div>
    <script>
        jQuery(document).ready(function($){
            $('#upload_post_image').click(function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: 'Select Post Image',
                    button: { text: 'Use this image' },
                    multiple: false
                });
                frame.open();
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#post_image').val(attachment.url);
                    $('#post_image_preview').attr('src', attachment.url).show();
                    $('#remove_post_image').show();
                });
            });

            $('#remove_post_image').click(function() {
                $('#post_image').val('');
                $('#post_image_preview').hide();
                $(this).hide();
            });
        });
    </script>
    <?php
}

function save_post_image($post_id) {
    if (!isset($_POST['post_image_nonce']) || !wp_verify_nonce($_POST['post_image_nonce'], basename(__FILE__))) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['post_image']) && !empty($_POST['post_image'])) {
        update_post_meta($post_id, '_post_image', esc_url_raw($_POST['post_image']));
    } else {
        delete_post_meta($post_id, '_post_image');
    }
}
add_action('save_post', 'save_post_image');

//MailChimping
function custom_subscribe_endpoint() {
    register_rest_route('custom/v1', '/subscribe', array(
        'methods'  => 'POST',
        'callback' => 'handle_mailchimp_subscription',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'custom_subscribe_endpoint');

function handle_mailchimp_subscription(WP_REST_Request $request) {
    $email = sanitize_email($request->get_param('email'));
    $subscribe = $request->get_param('subscribe');
    $newsletter_ids = $request->get_param('ids');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return new WP_REST_Response(['status' => 'error', 'message' => 'Invalid email address'], 400);
    }

    if (empty($newsletter_ids) || !is_array($newsletter_ids)) {
        return new WP_REST_Response(['status' => 'error', 'message' => 'No newsletters selected'], 400);
    }

    $api_key = 'your-mailchimp-api-key'; 
    $list_id = 'your-mailchimp-list-id'; 
    $api_url = "https://usX.api.mailchimp.com/3.0/lists/$list_id/members/";

    $data = [
        'email_address' => $email,
        'status'        => 'subscribed',
        'merge_fields'  => [
            'NEWSLETTER_IDS' => implode(',', $newsletter_ids),
            'SUBSCRIBE_ALL' => $subscribe ? 'Yes' : 'No',
        ]
    ];

    $json_data = json_encode($data);

    $response = wp_remote_post($api_url, [
        'method'    => 'POST',
        'headers'   => [
            'Authorization' => 'Basic ' . base64_encode("user:$api_key"),
            'Content-Type'  => 'application/json'
        ],
        'body'      => $json_data
    ]);

    if (is_wp_error($response)) {
        return new WP_REST_Response(['status' => 'error', 'message' => 'MailChimp request failed'], 500);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['status']) && $body['status'] == 'subscribed') {
        return new WP_REST_Response(['status' => 'success', 'message' => 'Subscription successful'], 200);
    } else {
        return new WP_REST_Response(['status' => 'error', 'message' => $body['detail'] ?? 'Unknown error'], 400);
    }
}

?>