<?php
/**
 * Plugin Name: Dafna Treatments
 * Description: שכבת נתונים לטיפולים — סוג תוכן "טיפול", טופס עריכה בעברית מחולק לסקשנים, טקסונומיית "תחום", Dynamic Tags וווידג'טים ל-Elementor. התצוגה נבנית ב-Theme Builder בלבד.
 * Version: 2.4.0
 * Author: Studio (for Dafna)
 * Text Domain: dafna-treatments
 * Plugin URI: https://github.com/shahar393-web/dafna-treatments
 * Update URI: https://github.com/shahar393-web/dafna-treatments
 *
 * ───────────────────────────────────────────────────────────────────────
 * להוסיף שדה חדש = שורה אחת ב-dt_fields(). מיד אחריה הוא מופיע:
 *   · בטופס העריכה, בתוך הסקשן שלו
 *   · ב-Dynamic Tags של Elementor
 *   · ברשימת הווידג'טים (אם הוא repeater)
 * אין קובץ נוסף לגעת בו, ואין CSS לכתוב.
 * ───────────────────────────────────────────────────────────────────────
 */

if (!defined('ABSPATH')) exit;

define('DT_CPT',  'treatment');
define('DT_TAX',  'treatment_concern');
define('DT_META', '_dt_');
define('DT_VER',  '2.4.0');
// הקבוצה בדף הבית = הטאב שבו הטיפול מופיע
define('DT_GROUP_TAX', 'treatment_group');
// העדכונים מגיעים מה-releases של המאגר הזה (סעיף 9)
define('DT_REPO_URL', 'https://github.com/shahar393-web/dafna-treatments/');

/* ═══════════════════════════════════════════════════════════════════════
   1 · הסקשנים בטופס. הסדר כאן הוא הסדר על המסך.
   ═══════════════════════════════════════════════════════════════════════ */
function dt_groups() {
    return [
        'intro'    => '1 · פתיחה — מה שמופיע ליד התמונה',
        'tabs'     => '2 · שלושת הטאבים — אבחון · הטיפול · למי מתאים',
        'benefits' => '3 · היתרונות — חמש שורות עם ✓ (ליד התמונה, מתחת לתקציר)',
        'faq'      => '4 · שאלות ותשובות על הטיפול',
        'tags'     => '5 · תחומים (לסינון בלבד)',
    ];
}

/* ═══════════════════════════════════════════════════════════════════════
   2 · השדות. רק מה שהתבנית מציגה. שדות ישנים (שלבים, עובדות, ציטוט,
       הזמנה, "תוכן ותמונה") נשארים במסד הנתונים אך לא מוצגים בטופס.
   ═══════════════════════════════════════════════════════════════════════ */
function dt_fields() {
    return [
        'eyebrow'   => ['type'=>'text',     'group'=>'intro', 'label'=>'עינית (השורה הקטנה מעל שם הטיפול)', 'hint'=>'למשל: ACNE · DR. SCHRAMMEK'],
        'subtitle'  => ['type'=>'text',     'group'=>'intro', 'label'=>'משפט משנה (שורה אחת, מתחת לשם הטיפול)'],
        'summary'   => ['type'=>'textarea', 'group'=>'intro', 'label'=>'תקציר (2–3 משפטים)', 'rows'=>4],

        'tab_diag'  => ['type'=>'textarea', 'group'=>'tabs', 'label'=>'אבחון — מה קורה לפני הטיפול', 'rows'=>5],
        'tab_proc'  => ['type'=>'textarea', 'group'=>'tabs', 'label'=>'הטיפול — מה עושים בפועל', 'rows'=>7],
        'tab_for'   => ['type'=>'textarea', 'group'=>'tabs', 'label'=>'למי מתאים', 'rows'=>5],

        'benefits'  => ['type'=>'repeater', 'group'=>'benefits', 'label'=>'השורות', 'hint'=>'כל שורה = ✓ אחד. קצר, עד 6 מילים.',
                        'sub'=>['text'=>'יתרון'], 'widget'=>'יתרונות (וי-ים)', 'icon'=>'eicon-bullet-list'],

        'faq'       => ['type'=>'repeater', 'group'=>'faq', 'label'=>'השאלות',
                        'sub'=>['q'=>'שאלה','a'=>'תשובה'], 'widget'=>'שאלות ותשובות', 'icon'=>'eicon-help-o'],

        'tags'      => ['type'=>'repeater', 'group'=>'tags', 'label'=>'תחומים', 'hint'=>'מסונכרן אוטומטית לטקסונומיית "תחום"',
                        'sub'=>['text'=>'תחום'], 'widget'=>'תחומים', 'icon'=>'eicon-tags'],
    ];
}

function dt_get($post_id, $key) {
    $v = get_post_meta($post_id, DT_META . $key, true);
    $f = dt_fields()[$key] ?? null;
    if ($f && $f['type'] === 'repeater') return is_array($v) ? $v : [];
    return $v;
}

/* ═══════════════════════════════════════════════════════════════════════
   3 · סוג התוכן, הטקסונומיה, ורישום המטא ל-REST ול-Dynamic Tags
   ═══════════════════════════════════════════════════════════════════════ */
add_action('init', function () {
    register_post_type(DT_CPT, [
        'labels' => [
            'name'=>'טיפולים','singular_name'=>'טיפול','add_new'=>'הוסף טיפול','add_new_item'=>'הוספת טיפול חדש',
            'edit_item'=>'עריכת טיפול','new_item'=>'טיפול חדש','view_item'=>'צפייה בטיפול','search_items'=>'חיפוש טיפולים',
            'not_found'=>'לא נמצאו טיפולים','menu_name'=>'טיפולים',
        ],
        'public'=>true,'has_archive'=>true,'show_in_rest'=>true,
        'menu_icon'=>'dashicons-heart','menu_position'=>5,
        'rewrite'=>['slug'=>'treatment'],
        'supports'=>['title','thumbnail','page-attributes'],
    ]);

    // קבוצה בדף הבית — הטאב שבו הטיפול מופיע. היררכית = תיבות סימון בטופס, כמו קטגוריות.
    register_taxonomy(DT_GROUP_TAX, DT_CPT, [
        'labels'=>['name'=>'קבוצות בדף הבית','singular_name'=>'קבוצה','menu_name'=>'קבוצות בדף הבית',
                   'add_new_item'=>'הוספת קבוצה','all_items'=>'כל הקבוצות'],
        'hierarchical'=>true,'public'=>true,'show_in_rest'=>true,'show_admin_column'=>true,
        'rewrite'=>['slug'=>'treatment-group'],
    ]);

    register_taxonomy(DT_TAX, DT_CPT, [
        'labels'=>['name'=>'תחומים','singular_name'=>'תחום','menu_name'=>'תחומים'],
        'hierarchical'=>false,'public'=>true,'show_in_rest'=>true,'show_admin_column'=>true,
        'rewrite'=>['slug'=>'concern'],
    ]);

    /* כל שדה סקלרי נרשם כמטא — כך Elementor מציע אותו ב-Dynamic Tags
       בלי שנצטרך לכתוב מחלקת תג לכל אחד. */
    foreach (dt_fields() as $key => $f) {
        if ($f['type'] === 'repeater') continue;
        register_post_meta(DT_CPT, DT_META . $key, [
            'type'=>'string','single'=>true,'show_in_rest'=>true,
            'auth_callback'=>function(){ return current_user_can('edit_posts'); },
        ]);
    }
});

/* ═══════════════════════════════════════════════════════════════════════
   4 · טופס העריכה — תיבה אחת לכל סקשן, נבנית מהסכימה
   ═══════════════════════════════════════════════════════════════════════ */
add_action('add_meta_boxes', function () {
    foreach (dt_groups() as $g => $title) {
        add_meta_box('dt_g_'.$g, $title, function ($post) use ($g) {
            dt_render_group($post, $g);
        }, DT_CPT, 'normal', 'high');
    }
});

function dt_render_group($post, $group) {
    wp_nonce_field('dt_save', 'dt_nonce');
    echo '<div class="dt-form">';
    foreach (dt_fields() as $key => $f) {
        if (($f['group'] ?? '') !== $group) continue;
        $name = DT_META . $key;
        $val  = dt_get($post->ID, $key);
        $cls  = !empty($f['is_title']) ? ' dt-f--title' : '';
        echo '<div class="dt-f'.$cls.'"><label for="'.esc_attr($name).'">'.esc_html($f['label']).'</label>';
        if (!empty($f['hint'])) echo '<span class="dt-hint">'.esc_html($f['hint']).'</span>';

        if ($f['type'] === 'textarea') {
            printf('<textarea id="%1$s" name="%1$s" rows="%2$d">%3$s</textarea>',
                esc_attr($name), (int)($f['rows'] ?? 4), esc_textarea($val));

        } elseif ($f['type'] === 'image') {
            $src = $val ? wp_get_attachment_image_url((int)$val, 'medium') : '';
            echo '<div class="dt-img" data-name="'.esc_attr($name).'">';
            echo '<img src="'.esc_url($src).'" alt=""'.($src ? '' : ' hidden').'>';
            echo '<input type="hidden" name="'.esc_attr($name).'" value="'.esc_attr($val).'">';
            echo '<button type="button" class="button dt-img-pick">בחירת תמונה</button> ';
            echo '<button type="button" class="button-link dt-img-clear">הסרה</button>';
            echo '</div>';

        } elseif ($f['type'] === 'repeater') {
            $rows = is_array($val) ? $val : [];
            echo '<div class="dt-rep" data-key="'.esc_attr($key).'">';
            echo '<div class="dt-rows">';
            if ($rows) { foreach ($rows as $i => $row) echo dt_row_html($key, $f['sub'], $i, $row); }
            else       { echo dt_row_html($key, $f['sub'], 0, []); }
            echo '</div><button type="button" class="button dt-add">הוספת שורה</button>';
            echo '<template>'.dt_row_html($key, $f['sub'], '__i__', []).'</template>';
            echo '</div>';

        } else {
            printf('<input type="text" id="%1$s" name="%1$s" value="%2$s">',
                esc_attr($name), esc_attr($val));
        }
        echo '</div>';
    }
    echo '</div>';
}

function dt_row_html($key, $sub, $i, $row) {
    $h = '<div class="dt-row">';
    foreach ($sub as $sk => $slabel) {
        $n = DT_META.$key.'['.$i.']['.$sk.']';
        $v = $row[$sk] ?? '';
        $long = in_array($sk, ['a','text','value'], true) && $sk !== 'value';
        $h .= '<label class="dt-sub"><span>'.esc_html($slabel).'</span>';
        $h .= $long
            ? '<textarea name="'.esc_attr($n).'" rows="2">'.esc_textarea($v).'</textarea>'
            : '<input type="text" name="'.esc_attr($n).'" value="'.esc_attr($v).'">';
        $h .= '</label>';
    }
    return $h.'<button type="button" class="button-link dt-del" aria-label="מחיקת שורה">✕</button></div>';
}

add_action('save_post_'.DT_CPT, function ($post_id) {
    if (!isset($_POST['dt_nonce']) || !wp_verify_nonce($_POST['dt_nonce'], 'dt_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    foreach (dt_fields() as $key => $f) {
        $name = DT_META . $key;
        if (!isset($_POST[$name])) continue;
        $raw = wp_unslash($_POST[$name]);

        if ($f['type'] === 'repeater') {
            $clean = [];
            foreach ((array)$raw as $row) {
                $r = [];
                foreach ($f['sub'] as $sk => $_) $r[$sk] = sanitize_textarea_field($row[$sk] ?? '');
                if (implode('', $r) !== '') $clean[] = $r;   // שורה ריקה לא נשמרת
            }
            update_post_meta($post_id, $name, $clean);
        } elseif ($f['type'] === 'image') {
            update_post_meta($post_id, $name, (int)$raw);
        } elseif ($f['type'] === 'textarea') {
            update_post_meta($post_id, $name, sanitize_textarea_field($raw));
        } else {
            update_post_meta($post_id, $name, sanitize_text_field($raw));
        }
    }
    dt_sync_tags_to_terms($post_id);
}, 10, 1);

/* "תחומים" שנכתבו בטופס נשמרים גם כמונחי טקסונומיה, כדי שאפשר יהיה
   לסנן ולקבץ טיפולים בלי להקליד אותם פעמיים. */
function dt_sync_tags_to_terms($post_id) {
    $names = array_filter(array_map(function ($r) { return trim($r['text'] ?? ''); }, dt_get($post_id, 'tags')));
    wp_set_object_terms($post_id, array_values(array_unique($names)), DT_TAX, false);
}

/* ═══════════════════════════════════════════════════════════════════════
   5 · עיצוב הטופס + בורר התמונה
   ═══════════════════════════════════════════════════════════════════════ */
add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['post.php','post-new.php'], true)) return;
    if (get_post_type() !== DT_CPT) return;
    wp_enqueue_media();
    wp_add_inline_style('common', '
      .dt-form{display:grid;gap:18px}
      .dt-f label{display:block;font-weight:600;margin-bottom:4px}
      .dt-f--title label{color:#2271b1}
      .dt-hint{display:block;color:#666;font-size:12px;margin-bottom:5px}
      .dt-f input[type=text],.dt-f textarea{width:100%}
      .dt-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr)) 28px;
              gap:10px;align-items:end;padding:10px;border:1px solid #dcdcde;border-radius:6px;margin-bottom:8px;background:#fff}
      .dt-sub span{display:block;font-size:12px;color:#555;margin-bottom:3px}
      .dt-sub input,.dt-sub textarea{width:100%}
      .dt-del{color:#b32d2e;text-decoration:none;font-size:15px}
      .dt-rep{background:#f6f7f7;padding:12px;border-radius:8px}
      .dt-img img{max-width:200px;height:auto;display:block;margin-bottom:8px;border-radius:6px}
    ');
    wp_add_inline_script('jquery-core', '
      jQuery(function($){
        $(document).on("click",".dt-add",function(){
          var rep=$(this).closest(".dt-rep"), n=rep.find(".dt-row").length;
          rep.find(".dt-rows").append(rep.find("template").html().replace(/__i__/g,n));
        });
        $(document).on("click",".dt-del",function(){
          var rows=$(this).closest(".dt-rows");
          if(rows.find(".dt-row").length>1) $(this).closest(".dt-row").remove();
          else rows.find("input,textarea").val("");
        });
        $(document).on("click",".dt-img-pick",function(){
          var box=$(this).closest(".dt-img");
          var f=wp.media({title:"בחירת תמונה",multiple:false});
          f.on("select",function(){
            var a=f.state().get("selection").first().toJSON();
            box.find("input").val(a.id);
            box.find("img").attr("src",a.sizes&&a.sizes.medium?a.sizes.medium.url:a.url).prop("hidden",false);
          });
          f.open();
        });
        $(document).on("click",".dt-img-clear",function(){
          var box=$(this).closest(".dt-img");
          box.find("input").val(""); box.find("img").prop("hidden",true);
        });
      });
    ');
});

/* ═══════════════════════════════════════════════════════════════════════
   6 · רינדור — פונקציה אחת לכל סוג שורה, נגזרת מהסכימה
   ═══════════════════════════════════════════════════════════════════════ */
function dt_render_field($id, $key) {
    $f = dt_fields()[$key] ?? null;
    if (!$f || $f['type'] !== 'repeater') return '';
    $rows = dt_get($id, $key);
    if (!$rows) return '';
    $sub = array_keys($f['sub']);
    $out = '<ul class="dt-list dt-list--'.esc_attr($key).'">';
    foreach ($rows as $n => $r) {
        $out .= '<li class="dt-item">';
        if (count($sub) === 1) {
            $out .= '<span class="dt-a">'.esc_html($r[$sub[0]] ?? '').'</span>';
        } elseif ($key === 'faq') {
            $out .= '<details><summary>'.esc_html($r['q'] ?? '').'</summary>'
                 .  '<div class="dt-b">'.nl2br(esc_html($r['a'] ?? '')).'</div></details>';
        } elseif ($key === 'steps') {
            $out .= '<span class="dt-n">'.str_pad($n+1, 2, '0', STR_PAD_LEFT).'</span>'
                 .  '<span class="dt-a">'.esc_html($r['title'] ?? '').'</span>'
                 .  '<span class="dt-b">'.esc_html($r['text'] ?? '').'</span>';
        } else {
            $out .= '<span class="dt-a">'.esc_html($r[$sub[0]] ?? '').'</span>'
                 .  '<span class="dt-b">'.esc_html($r[$sub[1]] ?? '').'</span>';
        }
        $out .= '</li>';
    }
    return $out.'</ul>';
}

/* ═══════════════════════════════════════════════════════════════════════
   7 · ווידג'ט אחד. חמשת השמות הישנים נשארים ככינויים שלו, כדי שתבניות
       קיימות לא יישברו, וכל שדה repeater חדש מקבל ווידג'ט משלו מעצמו.
   ═══════════════════════════════════════════════════════════════════════ */
add_action('elementor/elements/categories_registered', function ($mgr) {
    $mgr->add_category('dafna', ['title'=>'Dafna','icon'=>'fa fa-heart']);
});

add_action('elementor/widgets/register', function ($wm) {
    if (!class_exists('\Elementor\Widget_Base')) return;
    require_once __DIR__ . '/widget.php';
    foreach (dt_fields() as $key => $f) {
        if ($f['type'] !== 'repeater' || empty($f['widget'])) continue;
        $wm->register(new DT_Field_Widget($key));
    }
});

/* ═══════════════════════════════════════════════════════════════════════
   8 · טעינת ה-CSS של הרשימות רק כשווידג'ט שלנו באמת על העמוד
   ═══════════════════════════════════════════════════════════════════════ */
add_action('wp_enqueue_scripts', function () {
    wp_register_style('dt-front', false, [], DT_VER);
    wp_enqueue_style('dt-front');
    wp_add_inline_style('dt-front', '
      .dt-list{list-style:none;margin:0;padding:0}
      .dt-item{display:block}
      .dt-list--benefits .dt-item{position:relative;padding-inline-start:22px}
      .dt-list--benefits .dt-item::before{content:"";position:absolute;inset-inline-start:0;top:.6em;
        width:8px;height:8px;border-radius:50%;background:currentColor}
      .dt-list--steps .dt-item{display:grid;grid-template-columns:auto 1fr;gap:4px 14px}
      .dt-list--steps .dt-n{grid-row:span 2;font-variant-numeric:tabular-nums;opacity:.45}
      .dt-list--facts .dt-item{display:flex;justify-content:space-between;gap:16px}
      .dt-list--faq summary{cursor:pointer;list-style:none}
      .dt-list--faq summary::-webkit-details-marker{display:none}
      .dt-a{display:block;font-weight:600}
      .dt-b{display:block;opacity:.75}
    ');
});

/* ═══════════════════════════════════════════════════════════════════════
   9 · ייבוא — נקודת REST אחת לכתיבת כל השדות של כל הטיפולים בבת אחת
       (למשתמש מחובר עם הרשאת עריכה בלבד). גוף הבקשה:
       { "items": [ { "id": 11544, "meta": { "summary": "...", "faq": [...] }, "featured_media": 123 } ] }
   ═══════════════════════════════════════════════════════════════════════ */
add_action('rest_api_init', function () {
    register_rest_route('dafna-treatments/v1', '/import', [
        'methods'  => 'POST',
        'permission_callback' => function () { return current_user_can('edit_posts'); },
        'callback' => function (\WP_REST_Request $req) {
            $items = (array)$req->get_param('items');
            $fields = dt_fields(); $done = [];
            foreach ($items as $it) {
                $id = (int)($it['id'] ?? 0);
                if (!$id || get_post_type($id) !== DT_CPT) continue;
                foreach ((array)($it['meta'] ?? []) as $key => $val) {
                    if (!isset($fields[$key])) continue;
                    $f = $fields[$key];
                    if ($f['type'] === 'repeater') {
                        $clean = [];
                        foreach ((array)$val as $row) {
                            $r = [];
                            foreach ($f['sub'] as $sk => $_) $r[$sk] = sanitize_textarea_field($row[$sk] ?? '');
                            if (implode('', $r) !== '') $clean[] = $r;
                        }
                        update_post_meta($id, DT_META.$key, $clean);
                    } elseif ($f['type'] === 'image') {
                        update_post_meta($id, DT_META.$key, (int)$val);
                    } elseif ($f['type'] === 'textarea') {
                        update_post_meta($id, DT_META.$key, sanitize_textarea_field($val));
                    } else {
                        update_post_meta($id, DT_META.$key, sanitize_text_field($val));
                    }
                }
                if (!empty($it['featured_media'])) set_post_thumbnail($id, (int)$it['featured_media']);
                dt_sync_tags_to_terms($id);
                $done[] = $id;
            }
            return ['ok' => true, 'updated' => $done];
        },
    ]);
});

/* ═══════════════════════════════════════════════════════════════════════
   10 · "טיפולים נוספים" — ווידג'ט שמונה טיפולים אחרים, בלי שדה
   ═══════════════════════════════════════════════════════════════════════ */
add_action('elementor/widgets/register', function ($wm) {
    if (!class_exists('\Elementor\Widget_Base') || class_exists('DT_Related_Widget')) return;
    class DT_Related_Widget extends \Elementor\Widget_Base {
        public function get_name()       { return 'dt_related'; }
        public function get_title()      { return 'Dafna · טיפולים נוספים'; }
        public function get_icon()       { return 'eicon-post-list'; }
        public function get_categories() { return ['dafna']; }
        protected function register_controls() {
            $this->start_controls_section('c', ['label' => 'תוכן']);
            $this->add_control('count', ['label' => 'כמה', 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 4, 'min' => 1, 'max' => 10]);
            $this->end_controls_section();
        }
        protected function render() {
            $cur = get_the_ID(); $n = (int)($this->get_settings_for_display()['count'] ?: 4);
            $q = get_posts(['post_type' => DT_CPT, 'numberposts' => $n, 'exclude' => [$cur], 'orderby' => 'menu_order title', 'order' => 'ASC']);
            if (!$q) return;
            echo '<ul class="dt-list dt-list--related">';
            foreach ($q as $p) {
                $eb = get_post_meta($p->ID, DT_META.'eyebrow', true);
                echo '<li class="dt-item"><a href="'.esc_url(get_permalink($p)).'">'
                   . ($eb ? '<span class="dt-b">'.esc_html($eb).'</span>' : '')
                   . '<span class="dt-a">'.esc_html(get_the_title($p)).'</span></a></li>';
            }
            echo '</ul>';
        }
    }
    $wm->register(new DT_Related_Widget());
}, 20);

/* ═══════════════════════════════════════════════════════════════════════
   10ב · 2.4.0 — פרטי העסק, שאלות ותשובות, והווידג'טים של דף הבית
   (טאבים · לפני ואחרי · מותגים · תעודות · המלצות · שאלות)
   ═══════════════════════════════════════════════════════════════════════ */
require_once __DIR__ . '/inc/business.php';
require_once __DIR__ . '/inc/faq.php';
require_once __DIR__ . '/inc/assets.php';
add_action('elementor/widgets/register', function ($wm) {
    if (!class_exists('\Elementor\Widget_Base')) return;
    require_once __DIR__ . '/inc/widgets.php';
    dt_register_home_widgets($wm);
}, 25);

/* ═══════════════════════════════════════════════════════════════════════
   9 · עדכונים מ-GitHub — "קיימת גרסה חדשה" במסך התוספים, כמו כל תוסף
   ספריית Plugin Update Checker v5.7 (MIT, © Yahnis Elsts) קוראת את ה-release
   האחרון של github.com/shahar393-web/dafna-treatments ומתקינה את הקובץ
   dafna-treatments.zip שמצורף אליו. שום דבר לא מתעדכן לבד — לוחצים "עדכן".
   אם הספרייה חסרה או GitHub לא עונה, האתר ממשיך לעבוד כרגיל.
   ═══════════════════════════════════════════════════════════════════════ */
add_action('plugins_loaded', function () {
    $lib = __DIR__ . '/vendor/plugin-update-checker/plugin-update-checker.php';
    if (!file_exists($lib)) return;
    require_once $lib;
    if (!class_exists('\YahnisElsts\PluginUpdateChecker\v5\PucFactory')) return;
    try {
        $checker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(DT_REPO_URL, __FILE__, 'dafna-treatments');
        $checker->setBranch('main');
        $checker->getVcsApi()->enableReleaseAssets('/dafna-treatments\.zip($|[?&#])/i');
    } catch (\Throwable $e) {
        if (defined('WP_DEBUG') && WP_DEBUG) error_log('Dafna Treatments updater: ' . $e->getMessage());
    }
});

/* ═══════════════════════════════════════════════════════════════════════
   10 · תיקון: גיליון הסגנון של הלייטבוקס
   אלמנטור טוען את קובץ הלייטבוקס שלו רק כשהוא מזהה שהעמוד צריך אותו,
   והזיהוי מפספס תמונות שיושבות בתוך קרוסלה מקוננת — הדיאלוג נפתח ריק.
   כאן בודקים בעצמנו אם בעמוד יש תמונה עם לייטבוקס, ואם כן טוענים את
   שני הקבצים של אלמנטור עצמו. אין כאן CSS משלנו.
   ═══════════════════════════════════════════════════════════════════════ */
add_action('wp_enqueue_scripts', function () {
    if (!did_action('elementor/loaded') || !is_singular()) return;
    $data = get_post_meta(get_queried_object_id(), '_elementor_data', true);
    if (!is_string($data) || (strpos($data, '"open_lightbox":"yes"') === false && strpos($data, '"widgetType":"dt_before_after"') === false)) return;
    foreach (['dialog', 'lightbox'] as $handle) {
        if (wp_style_is($handle, 'registered')) { wp_enqueue_style($handle); continue; }
        if (defined('ELEMENTOR_ASSETS_URL')) {
            wp_enqueue_style('dt-' . $handle, ELEMENTOR_ASSETS_URL . 'css/conditionals/' . $handle . '.min.css',
                             [], defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : null);
        }
    }
}, 20);

/* ═══════════════════════════════════════════════════════════════════════
   11 · הקבוצות של דף הבית — נזרעות פעם אחת, בהפעלה או בעדכון
   ═══════════════════════════════════════════════════════════════════════ */
function dt_seed_groups() {
    foreach (['טיפולי פנים' => 'facial', 'טיפולים מתקדמים' => 'advanced', 'ספא ואירועים' => 'spa'] as $name => $slug) {
        if (!term_exists($slug, DT_GROUP_TAX)) wp_insert_term($name, DT_GROUP_TAX, ['slug' => $slug]);
    }
}
register_activation_hook(__FILE__, function () { dt_seed_groups(); flush_rewrite_rules(); });
add_action('admin_init', function () {
    if (get_option('dt_groups_seeded') !== DT_VER) { dt_seed_groups(); update_option('dt_groups_seeded', DT_VER); }
});

