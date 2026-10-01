<?php
/**
 * שאלות ותשובות — דפנה מוסיפה ומשנה מוורדפרס.
 *
 *  · סוג תוכן "שאלות ותשובות": הכותרת = השאלה, התוכן = התשובה, "סדר" = המיקום ברשימה.
 *  · תיבה "איפה השאלה מופיעה": דף הבית · על עצמי · דף הטיפולים · טיפולים מסוימים.
 *  · ווידג'ט "Dafna · שאלות ותשובות": בוחרים מקום, והוא מציג רק את השאלות שלו (+ FAQ Schema לגוגל).
 */
if (!defined('ABSPATH')) exit;

define('DT_FAQ', 'dt_faq');

function dt_faq_places() {
    return ['home' => 'דף הבית', 'about' => 'על עצמי', 'treatments' => 'דף הטיפולים'];
}

add_action('init', function () {
    register_post_type(DT_FAQ, [
        'labels' => ['name' => 'שאלות ותשובות', 'singular_name' => 'שאלה', 'add_new' => 'הוספת שאלה', 'add_new_item' => 'שאלה חדשה',
                     'edit_item' => 'עריכת שאלה', 'menu_name' => 'שאלות ותשובות', 'all_items' => 'כל השאלות'],
        'public' => false, 'show_ui' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-format-chat', 'menu_position' => 6,
        'supports' => ['title', 'editor', 'page-attributes'],
    ]);
});

/* שם השדה "כותרת" בטופס → "השאלה" */
add_filter('enter_title_here', function ($t, $post) { return $post->post_type === DT_FAQ ? 'השאלה' : $t; }, 10, 2);

add_action('add_meta_boxes', function () {
    add_meta_box('dt_faq_where', 'איפה השאלה מופיעה', function ($post) {
        wp_nonce_field('dt_faq_save', 'dt_faq_nonce');
        $sel = (array) get_post_meta($post->ID, '_dt_faq_places', true);
        echo '<p><b>עמודים</b></p>';
        foreach (dt_faq_places() as $k => $label) {
            echo '<label style="display:block;margin:4px 0"><input type="checkbox" name="dt_faq_places[]" value="' . esc_attr($k) . '"' . checked(in_array($k, $sel, true), true, false) . '> ' . esc_html($label) . '</label>';
        }
        $tr = get_posts(['post_type' => DT_CPT, 'numberposts' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC']);
        if ($tr) {
            echo '<p style="margin-top:12px"><b>טיפולים</b> — השאלה תופיע בעמוד של הטיפול</p>';
            foreach ($tr as $p) {
                $v = 'treatment:' . $p->ID;
                echo '<label style="display:block;margin:4px 0"><input type="checkbox" name="dt_faq_places[]" value="' . esc_attr($v) . '"' . checked(in_array($v, $sel, true), true, false) . '> ' . esc_html(get_the_title($p)) . '</label>';
            }
        }
        echo '<p class="description">הסדר נקבע בשדה "סדר" (מספר קטן = למעלה).</p>';
    }, DT_FAQ, 'side', 'high');
});

add_action('save_post_' . DT_FAQ, function ($id) {
    if (!isset($_POST['dt_faq_nonce']) || !wp_verify_nonce($_POST['dt_faq_nonce'], 'dt_faq_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $id)) return;
    $in = array_map('sanitize_text_field', (array) ($_POST['dt_faq_places'] ?? []));
    update_post_meta($id, '_dt_faq_places', $in);
});

/* השאלות של מקום מסוים, לפי הסדר */
function dt_faq_items($place) {
    $q = get_posts(['post_type' => DT_FAQ, 'numberposts' => -1, 'orderby' => 'menu_order date', 'order' => 'ASC',
                    'meta_query' => [['key' => '_dt_faq_places', 'value' => '"' . $place . '"', 'compare' => 'LIKE']]]);
    $out = [];
    foreach ($q as $p) $out[] = ['q' => get_the_title($p), 'a' => apply_filters('the_content', $p->post_content)];
    return $out;
}

/* עמודה ברשימת השאלות: איפה כל שאלה מופיעה */
add_filter('manage_' . DT_FAQ . '_posts_columns', function ($c) { $c['dt_where'] = 'מופיעה ב'; return $c; });
add_action('manage_' . DT_FAQ . '_posts_custom_column', function ($c, $id) {
    if ($c !== 'dt_where') return;
    $names = [];
    foreach ((array) get_post_meta($id, '_dt_faq_places', true) as $v) {
        if (isset(dt_faq_places()[$v])) $names[] = dt_faq_places()[$v];
        elseif (strpos($v, 'treatment:') === 0) $names[] = get_the_title((int) substr($v, 10));
    }
    echo esc_html(implode(' · ', $names));
}, 10, 2);
