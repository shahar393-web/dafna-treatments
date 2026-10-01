<?php
/**
 * פרטי העסק — עמוד הגדרות אחד בוורדפרס, וכל ווידג'ט באתר מושך ממנו.
 *
 *  · מסך: "פרטי העסק" בתפריט הניהול.
 *  · Dynamic Tags באלמנטור (קבוצה "דפנה"): טקסט ("פרטי העסק") וקישור ("קישור מפרטי העסק").
 *  · קיצור: [dafna_biz field="phone"]  /  [dafna_biz field="whatsapp" link="1"]
 */
if (!defined('ABSPATH')) exit;

function dt_biz_fields() {
    // מפתח => [תווית, ברירת מחדל, סוג שדה, הסבר]
    return [
        'phone'         => ['טלפון (כמו שיופיע באתר)', '050-000-0000', 'text', ''],
        'whatsapp'      => ['וואטסאפ — מספר', '972500000000', 'text', 'בפורמט בינלאומי, בלי + ובלי מקפים. למשל 972501234567'],
        'whatsapp_text' => ['הודעה מוכנה בוואטסאפ', 'היי דפנה, אשמח לקבוע תור', 'text', ''],
        'email'         => ['מייל', '', 'email', 'גם הכתובת שאליה נשלחים הטפסים באתר'],
        'instagram'     => ['אינסטגרם — קישור', '', 'url', ''],
        'facebook'      => ['פייסבוק — קישור', '', 'url', ''],
        'address'       => ['כתובת הקליניקה', '', 'text', ''],
        'hours'         => ['שעות פעילות', "ראשון עד חמישי\n9:00 עד 19:00\nשישי\n9:00 עד 13:00\nשבת\nסגור", 'textarea', 'שורה לכל פריט'],
        'google_key'    => ['Google API Key', '', 'password', 'מפתח של Places API, להמלצות מגוגל. נשמר רק באתר.'],
        'google_place'  => ['Google Place ID', '', 'text', 'המזהה של העסק בגוגל (מתחיל ב-ChIJ…)'],
    ];
}

function dt_biz($key) {
    $o = get_option('dt_business', []);
    if (isset($o[$key]) && $o[$key] !== '') return $o[$key];
    return dt_biz_fields()[$key][1] ?? '';
}

/* קישור מוכן לכל סוג */
function dt_biz_url($type) {
    switch ($type) {
        case 'phone':     return 'tel:' . preg_replace('/[^0-9+]/', '', dt_biz('phone'));
        case 'whatsapp':  return 'https://wa.me/' . preg_replace('/\D/', '', dt_biz('whatsapp')) . '?text=' . rawurlencode(dt_biz('whatsapp_text'));
        case 'email':     return dt_biz('email') ? 'mailto:' . dt_biz('email') : '';
        case 'instagram': return dt_biz('instagram');
        case 'facebook':  return dt_biz('facebook');
        case 'address':   return dt_biz('address') ? 'https://maps.google.com/?q=' . rawurlencode(dt_biz('address')) : '';
    }
    return '';
}

/* ── מסך ההגדרות ───────────────────────────────────────────────────────── */
add_action('admin_menu', function () {
    add_menu_page('פרטי העסק', 'פרטי העסק', 'manage_options', 'dt-business', 'dt_biz_screen', 'dashicons-store', 4);
});

add_action('admin_init', function () {
    register_setting('dt_business', 'dt_business', ['sanitize_callback' => function ($in) {
        $out = [];
        foreach (dt_biz_fields() as $k => $f) {
            $v = isset($in[$k]) ? wp_unslash($in[$k]) : '';
            if ($f[2] === 'textarea')   $out[$k] = sanitize_textarea_field($v);
            elseif ($f[2] === 'email')  $out[$k] = sanitize_email($v);
            elseif ($f[2] === 'url')    $out[$k] = esc_url_raw($v);
            else                        $out[$k] = sanitize_text_field($v);
        }
        delete_transient('dt_reviews_' . md5($out['google_place'] ?? ''));
        return $out;
    }]);
});

function dt_biz_screen() {
    $o = get_option('dt_business', []);
    echo '<div class="wrap" dir="rtl"><h1>פרטי העסק</h1>';
    echo '<p>כל מה שנכתב כאן מופיע באתר בכל המקומות שמושכים ממנו — תפריט, פוטר, כפתורי וואטסאפ, טפסים והמלצות. משנים פעם אחת כאן.</p>';
    echo '<form method="post" action="options.php">';
    settings_fields('dt_business');
    echo '<table class="form-table" role="presentation">';
    foreach (dt_biz_fields() as $k => $f) {
        $name = 'dt_business[' . $k . ']';
        $val  = $o[$k] ?? '';
        echo '<tr><th scope="row"><label for="dtb_' . esc_attr($k) . '">' . esc_html($f[0]) . '</label></th><td>';
        if ($f[2] === 'textarea') {
            echo '<textarea id="dtb_' . esc_attr($k) . '" name="' . esc_attr($name) . '" rows="6" class="large-text">' . esc_textarea($val) . '</textarea>';
        } else {
            $type = $f[2] === 'password' ? 'password' : ($f[2] === 'email' ? 'email' : ($f[2] === 'url' ? 'url' : 'text'));
            echo '<input id="dtb_' . esc_attr($k) . '" type="' . $type . '" name="' . esc_attr($name) . '" value="' . esc_attr($val) . '" class="regular-text" autocomplete="off" dir="auto">';
        }
        if ($f[1] !== '' && $f[2] !== 'password') echo '<p class="description">ברירת מחדל כשריק: ' . esc_html(str_replace("\n", ' · ', $f[1])) . '</p>';
        if ($f[3]) echo '<p class="description">' . esc_html($f[3]) . '</p>';
        echo '</td></tr>';
    }
    echo '</table>';
    submit_button('שמירה');
    echo '</form></div>';
}

/* ── קיצור ─────────────────────────────────────────────────────────────── */
add_shortcode('dafna_biz', function ($a) {
    $a = shortcode_atts(['field' => 'phone', 'link' => ''], $a);
    $text = $a['field'] === 'hours' ? nl2br(esc_html(dt_biz('hours'))) : esc_html(dt_biz($a['field']));
    if ($a['link'] && ($u = dt_biz_url($a['field']))) return '<a href="' . esc_url($u) . '">' . $text . '</a>';
    return $text;
});

/* ── Dynamic Tags ──────────────────────────────────────────────────────── */
add_action('elementor/dynamic_tags/register', function ($tags) {
    if (!class_exists('\Elementor\Core\DynamicTags\Tag')) return;
    $tags->register_group('dafna', ['title' => 'דפנה']);

    if (!class_exists('DT_Biz_Text_Tag')) {
        class DT_Biz_Text_Tag extends \Elementor\Core\DynamicTags\Tag {
            public function get_name()       { return 'dt-biz-text'; }
            public function get_title()      { return 'פרטי העסק'; }
            public function get_group()      { return 'dafna'; }
            public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY]; }
            protected function register_controls() {
                $opts = [];
                foreach (dt_biz_fields() as $k => $f) if (!in_array($k, ['google_key', 'google_place', 'whatsapp_text'], true)) $opts[$k] = $f[0];
                $this->add_control('field', ['label' => 'שדה', 'type' => \Elementor\Controls_Manager::SELECT, 'options' => $opts, 'default' => 'phone']);
            }
            public function render() {
                $k = $this->get_settings('field') ?: 'phone';
                echo $k === 'hours' ? nl2br(esc_html(dt_biz('hours'))) : esc_html(dt_biz($k));
            }
        }
        class DT_Biz_Url_Tag extends \Elementor\Core\DynamicTags\Data_Tag {
            public function get_name()       { return 'dt-biz-url'; }
            public function get_title()      { return 'קישור מפרטי העסק'; }
            public function get_group()      { return 'dafna'; }
            public function get_categories() { return [\Elementor\Modules\DynamicTags\Module::URL_CATEGORY]; }
            protected function register_controls() {
                $this->add_control('type', ['label' => 'סוג', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'whatsapp', 'options' => [
                    'whatsapp' => 'וואטסאפ (עם ההודעה המוכנה)', 'phone' => 'חיוג', 'email' => 'מייל',
                    'instagram' => 'אינסטגרם', 'facebook' => 'פייסבוק', 'address' => 'ניווט לכתובת',
                ]]);
            }
            public function get_value(array $options = []) { return dt_biz_url($this->get_settings('type') ?: 'whatsapp'); }
        }
    }
    $tags->register(new DT_Biz_Text_Tag());
    $tags->register(new DT_Biz_Url_Tag());
});
