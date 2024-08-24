<?php
/**
 * Plugin Name: SEO Optimization Plugin
 * Plugin URI:  https://example.com/seo-optimization-plugin
 * Description: A simple SEO plugin to add custom meta titles and descriptions to posts and pages.
 * Version:     5.3.3
 * Author:      Jay
 * Author URI:  https://example.com
 * License:     GPLv2 or later
 */

 // Add meta boxes to post and page editor
function seo_add_meta_box() {
    add_meta_box(
        'seo_meta_box',            // ID of the meta box
        __('SEO Settings', 'text_domain'),  // Title of the meta box
        'seo_meta_box_callback',   // Callback function to render the meta box
        array('post', 'page')      // Post types where the meta box should appear
    );
}
add_action('add_meta_boxes', 'seo_add_meta_box');

// Callback function to display the fields in the meta box
function seo_meta_box_callback($post) {
    // Retrieve current values for the fields
    $seo_title = get_post_meta($post->ID, '_seo_title', true);
    $seo_description = get_post_meta($post->ID, '_seo_description', true);
    $seo_keywords = get_post_meta($post->ID, '_seo_keywords', true);
    $og_title = get_post_meta($post->ID, '_og_title', true);
    $og_description = get_post_meta($post->ID, '_og_description', true);
    $canonical_url = get_post_meta($post->ID, '_canonical_url', true);
    $focus_keyword = get_post_meta($post->ID, '_focus_keyword', true); // New field for SEO Analysis

    // Output fields
    ?>
    <!-- SEO Title -->
    <p>
        <label for="seo_title"><?php _e('SEO Title:', 'text_domain'); ?></label>
        <input type="text" id="seo_title" name="seo_title" value="<?php echo esc_attr($seo_title); ?>" class="widefat">
    </p>

    <!-- SEO Description -->
    <p>
        <label for="seo_description"><?php _e('SEO Description:', 'text_domain'); ?></label>
        <textarea id="seo_description" name="seo_description" class="widefat"><?php echo esc_textarea($seo_description); ?></textarea>
    </p>

    <!-- Meta Keywords -->
    <p>
        <label for="seo_keywords"><?php _e('Meta Keywords:', 'text_domain'); ?></label>
        <input type="text" id="seo_keywords" name="seo_keywords" value="<?php echo esc_attr($seo_keywords); ?>" class="widefat">
        <small><?php _e('Enter keywords separated by commas.', 'text_domain'); ?></small>
    </p>

    <!-- Open Graph Title -->
    <p>
        <label for="og_title"><?php _e('Open Graph Title (for social sharing):', 'text_domain'); ?></label>
        <input type="text" id="og_title" name="og_title" value="<?php echo esc_attr($og_title); ?>" class="widefat">
    </p>

    <!-- Open Graph Description -->
    <p>
        <label for="og_description"><?php _e('Open Graph Description (for social sharing):', 'text_domain'); ?></label>
        <textarea id="og_description" name="og_description" class="widefat"><?php echo esc_textarea($og_description); ?></textarea>
    </p>

    <!-- Canonical URL -->
    <p>
        <label for="canonical_url"><?php _e('Canonical URL:', 'text_domain'); ?></label>
        <input type="text" id="canonical_url" name="canonical_url" value="<?php echo esc_attr($canonical_url); ?>" class="widefat">
        <small><?php _e('Leave blank to use the default URL.', 'text_domain'); ?></small>
    </p>

    <!-- Focus Keyword for SEO Analysis -->
    <p>
        <label for="focus_keyword"><?php _e('Focus Keyword:', 'text_domain'); ?></label>
        <input type="text" id="focus_keyword" name="focus_keyword" value="<?php echo esc_attr($focus_keyword); ?>" class="widefat">
    </p>
    <?php
}

// Save the custom SEO meta data
function seo_save_meta_box_data($post_id) {
    // Check and save SEO title
    if (isset($_POST['seo_title'])) {
        update_post_meta($post_id, '_seo_title', sanitize_text_field($_POST['seo_title']));
    }

    // Check and save SEO description
    if (isset($_POST['seo_description'])) {
        update_post_meta($post_id, '_seo_description', sanitize_textarea_field($_POST['seo_description']));
    }

    // Check and save Meta Keywords
    if (isset($_POST['seo_keywords'])) {
        update_post_meta($post_id, '_seo_keywords', sanitize_text_field($_POST['seo_keywords']));
    }

    // Check and save Open Graph title
    if (isset($_POST['og_title'])) {
        update_post_meta($post_id, '_og_title', sanitize_text_field($_POST['og_title']));
    }

    // Check and save Open Graph description
    if (isset($_POST['og_description'])) {
        update_post_meta($post_id, '_og_description', sanitize_textarea_field($_POST['og_description']));
    }

    // Check and save Canonical URL
    if (isset($_POST['canonical_url'])) {
        update_post_meta($post_id, '_canonical_url', esc_url_raw($_POST['canonical_url']));
    }

    // Save Focus Keyword for SEO Analysis
    if (isset($_POST['focus_keyword'])) {
        update_post_meta($post_id, '_focus_keyword', sanitize_text_field($_POST['focus_keyword']));
    }
}
add_action('save_post', 'seo_save_meta_box_data');

// Output custom SEO meta tags in the head section
function seo_output_meta_tags() {
    if (is_singular()) {
        global $post;

        // Retrieve SEO meta data
        $seo_title = get_post_meta($post->ID, '_seo_title', true);
        $seo_description = get_post_meta($post->ID, '_seo_description', true);
        $seo_keywords = get_post_meta($post->ID, '_seo_keywords', true);
        $og_title = get_post_meta($post->ID, '_og_title', true);
        $og_description = get_post_meta($post->ID, '_og_description', true);
        $canonical_url = get_post_meta($post->ID, '_canonical_url', true);

        // Output custom meta title
        if (!empty($seo_title)) {
            echo '<title>' . esc_html($seo_title) . '</title>' . "\n";
        }

        // Output custom meta description
        if (!empty($seo_description)) {
            echo '<meta name="description" content="' . esc_attr($seo_description) . '">' . "\n";
        }

        // Output custom meta keywords
        if (!empty($seo_keywords)) {
            echo '<meta name="keywords" content="' . esc_attr($seo_keywords) . '">' . "\n";
        }

        // Output Open Graph title for social sharing
        if (!empty($og_title)) {
            echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
        }

        // Output Open Graph description for social sharing
        if (!empty($og_description)) {
            echo '<meta property="og:description" content="' . esc_attr($og_description) . '">' . "\n";
        }

        // Output canonical URL
        if (!empty($canonical_url)) {
            echo '<link rel="canonical" href="' . esc_url($canonical_url) . '">' . "\n";
        } else {
            // Default canonical URL if not set
            echo '<link rel="canonical" href="' . esc_url(get_permalink($post->ID)) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'seo_output_meta_tags');

// Perform Basic SEO Analysis
function seo_basic_analysis($post) {
    $focus_keyword = get_post_meta($post->ID, '_focus_keyword', true);
    $content = $post->post_content;
    $seo_title = get_post_meta($post->ID, '_seo_title', true);
    $seo_description = get_post_meta($post->ID, '_seo_description', true);

    // Calculate Keyword Density
    $word_count = str_word_count(strip_tags($content));
    $keyword_count = substr_count(strtolower($content), strtolower($focus_keyword));
    $keyword_density = ($word_count > 0) ? ($keyword_count / $word_count) * 100 : 0;

    echo '<div class="seo-analysis">';
    echo '<h3>' . __('Basic SEO Analysis', 'text_domain') . '</h3>';

    // Check for focus keyword
    if (!empty($focus_keyword)) {
        echo '<p><strong>' . __('Focus Keyword:', 'text_domain') . '</strong> ' . esc_html($focus_keyword) . '</p>';
        echo '<p>' . __('Keyword Density:', 'text_domain') . ' ' . number_format($keyword_density, 2) . '%</p>';
    } else {
        echo '<p style="color: red;">' . __('No Focus Keyword Set.', 'text_domain') . '</p>';
    }

    // Content Length Analysis
    echo '<p>' . __('Content Length:', 'text_domain') . ' ' . $word_count . ' ' . __('words', 'text_domain') . '</p>';

    // Title and Description Check
    if (!empty($seo_title)) {
        echo '<p style="color: green;">' . __('SEO Title Set.', 'text_domain') . '</p>';
    } else {
        echo '<p style="color: red;">' . __('SEO Title Missing.', 'text_domain') . '</p>';
    }

    if (!empty($seo_description)) {
        echo '<p style="color: green;">' . __('SEO Description Set.', 'text_domain') . '</p>';
    } else {
        echo '<p style="color: red;">' . __('SEO Description Missing.', 'text_domain') . '</p>';
    }

    echo '</div>';
}
add_action('edit_form_after_editor', 'seo_basic_analysis');
