<?php
/**
 * הווידג'טים של 2.4.0 — כולם בקטגוריה "Dafna" בעורך.
 *
 *   Dafna · טאבים של טיפולים   — הטאבים = קבוצות, השורות = הטיפולים (נערך בוורדפרס, מעוצב באלמנטור)
 *   Dafna · לפני ואחרי          — קרוסלה רציפה, תמונות לאורך, מהירות / לופ / לייטבוקס
 *   Dafna · מותגים              — פס לוגואים אינסופי, כל לוגו מקשר
 *   Dafna · פס תעודות           — הכשרות עם אייקון ביניהן, רץ בלופ
 *   Dafna · המלצות              — מגוגל (מפתח ב"פרטי העסק") או ידני
 *   Dafna · שאלות ותשובות       — מסוג התוכן "שאלות ותשובות", לפי מקום
 */
if (!defined('ABSPATH')) exit;

use Elementor\Controls_Manager as CM;
use Elementor\Group_Control_Typography as GT;
use Elementor\Group_Control_Border as GB;
use Elementor\Repeater;

abstract class DT_W_Base extends \Elementor\Widget_Base {
    public function get_categories()   { return ['dafna']; }
    public function get_style_depends(): array  { return ['dt-widgets']; }
    public function get_script_depends(): array { return ['dt-widgets']; }

    /* פקדים משותפים לפס רץ */
    protected function marquee_controls($speed = 40) {
        $this->add_control('speed', ['label' => 'זמן סיבוב מלא (שניות)', 'type' => CM::NUMBER, 'min' => 5, 'max' => 400, 'default' => $speed,
            'description' => 'מספר גדול = תנועה איטית יותר', 'selectors' => ['{{WRAPPER}} .dfm' => '--dfm-dur:{{VALUE}}s']]);
        $this->add_control('dir', ['label' => 'כיוון התנועה', 'type' => CM::SELECT, 'default' => 'left', 'options' => ['left' => 'שמאלה', 'right' => 'ימינה']]);
        $this->add_control('pause', ['label' => 'עצירה במעבר עכבר', 'type' => CM::SWITCHER, 'default' => 'yes']);
        $this->add_control('fade', ['label' => 'דהייה בצדדים', 'type' => CM::SWITCHER, 'default' => 'yes']);
        $this->add_responsive_control('gap', ['label' => 'רווח בין פריטים', 'type' => CM::SLIDER, 'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 200]], 'default' => ['unit' => 'px', 'size' => 32],
            'selectors' => ['{{WRAPPER}} .dfm' => '--dfm-gap:{{SIZE}}{{UNIT}}']]);
    }

    /* שני עותקים של אותם פריטים = לופ בלי קפיצה */
    protected function marquee($items, $s, $extra = '') {
        if (!$items) return;
        $cls = 'dfm ' . $extra . (($s['pause'] ?? '') === 'yes' ? ' is-pause' : '') . (($s['fade'] ?? '') === 'yes' ? ' is-fade' : '');
        echo '<div class="' . esc_attr(trim($cls)) . '" data-dir="' . esc_attr($s['dir'] ?? 'left') . '"><div class="dfm-track">';
        echo '<div class="dfm-set">' . $items . '</div>';
        echo '<div class="dfm-set" aria-hidden="true">' . str_replace(['data-elementor-lightbox-slideshow="', ' href='], ['data-elementor-lightbox-slideshow="b-', ' tabindex="-1" href='], $items) . '</div>';
        echo '</div></div>';
    }
}

/* ═══════════════════════════ 1 · טאבים של טיפולים ═══════════════════════════ */
class DT_Treatment_Tabs_Widget extends DT_W_Base {
    public function get_name()  { return 'dt_treatment_tabs'; }
    public function get_title() { return 'Dafna · טאבים של טיפולים'; }
    public function get_icon()  { return 'eicon-tabs'; }

    protected function register_controls() {
        $terms = get_terms(['taxonomy' => DT_GROUP_TAX, 'hide_empty' => false]);
        $opts = [];
        if (!is_wp_error($terms)) foreach ($terms as $t) $opts[$t->slug] = $t->name;

        $this->start_controls_section('c', ['label' => 'תוכן']);
        $this->add_control('note', ['type' => CM::RAW_HTML, 'content_classes' => 'elementor-descriptor',
            'raw' => 'הטיפולים והקבוצות נערכים בוורדפרס: <b>טיפולים</b> ← עריכת טיפול ← "קבוצות בדף הבית". הסדר בכל טאב = שדה "סדר" של הטיפול.']);
        $this->add_control('groups', ['label' => 'אילו טאבים ובאיזה סדר', 'type' => CM::SELECT2, 'multiple' => true, 'options' => $opts,
            'default' => array_values(array_intersect(['facial', 'advanced', 'spa'], array_keys($opts)))]);
        $this->add_control('max', ['label' => 'כמה טיפולים בכל טאב', 'type' => CM::NUMBER, 'min' => 1, 'max' => 20, 'default' => 4]);
        $this->add_control('words', ['label' => 'אורך התיאור (מילים)', 'type' => CM::NUMBER, 'min' => 0, 'max' => 60, 'default' => 14]);
        $this->end_controls_section();

        $this->start_controls_section('s_tabs', ['label' => 'כותרות הטאבים', 'tab' => CM::TAB_STYLE]);
        $this->add_responsive_control('tabs_align', ['label' => 'יישור', 'type' => CM::CHOOSE, 'default' => 'flex-start',
            'options' => ['flex-start' => ['title' => 'ימין', 'icon' => 'eicon-text-align-right'], 'center' => ['title' => 'מרכז', 'icon' => 'eicon-text-align-center'], 'flex-end' => ['title' => 'שמאל', 'icon' => 'eicon-text-align-left']],
            'selectors' => ['{{WRAPPER}} .dtt-tabs' => 'justify-content:{{VALUE}}']]);
        $this->add_responsive_control('tabs_gap', ['label' => 'רווח בין טאבים', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 48], 'tablet_default' => ['unit' => 'px', 'size' => 40], 'mobile_default' => ['unit' => 'px', 'size' => 24],
            'selectors' => ['{{WRAPPER}} .dtt-tabs' => 'gap:{{SIZE}}{{UNIT}}']]);
        $this->add_responsive_control('tabs_space', ['label' => 'רווח מתחת לטאבים', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 48], 'mobile_default' => ['unit' => 'px', 'size' => 24],
            'selectors' => ['{{WRAPPER}} .dtt-tabs' => 'margin-bottom:{{SIZE}}{{UNIT}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'tab_t', 'selector' => '{{WRAPPER}} .dtt-tab']);
        $this->add_control('tab_c', ['label' => 'צבע', 'type' => CM::COLOR, 'default' => '#6E675D', 'selectors' => ['{{WRAPPER}} .dtt-tab' => 'color:{{VALUE}}']]);
        $this->add_control('tab_ca', ['label' => 'צבע טאב פעיל', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}} .dtt-tab.is-active' => 'color:{{VALUE}};border-bottom-color:{{VALUE}}']]);
        $this->add_control('tab_wa', ['label' => 'משקל טאב פעיל', 'type' => CM::SELECT, 'default' => '700', 'options' => ['400' => 'רגיל', '600' => 'חצי מודגש', '700' => 'מודגש'],
            'selectors' => ['{{WRAPPER}} .dtt-tab.is-active' => 'font-weight:{{VALUE}}']]);
        $this->add_control('tab_pb', ['label' => 'רווח טקסט ↔ קו', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 12], 'selectors' => ['{{WRAPPER}} .dtt-tab' => 'padding-bottom:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();

        $this->start_controls_section('s_rows', ['label' => 'שורות הטיפולים', 'tab' => CM::TAB_STYLE]);
        $this->add_responsive_control('row_pad', ['label' => 'ריפוד שורה', 'type' => CM::DIMENSIONS, 'size_units' => ['px'],
            'default' => ['top' => '12', 'right' => '0', 'bottom' => '20', 'left' => '0', 'unit' => 'px', 'isLinked' => false],
            'selectors' => ['{{WRAPPER}} .dtt-row' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']]);
        $this->add_responsive_control('row_gap', ['label' => 'רווח טקסט ↔ עיגול', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 32], 'selectors' => ['{{WRAPPER}} .dtt-row' => 'gap:{{SIZE}}{{UNIT}}']]);
        $this->add_control('row_line', ['label' => 'צבע קו בין שורות', 'type' => CM::COLOR, 'default' => '#DFDAD1', 'selectors' => ['{{WRAPPER}} .dtt-row:not(:last-child)' => 'border-bottom:1px solid {{VALUE}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'title_t', 'label' => 'שם הטיפול', 'selector' => '{{WRAPPER}} .dtt-title']);
        $this->add_control('title_c', ['label' => 'צבע השם', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}} .dtt-title' => 'color:{{VALUE}}']]);
        $this->add_control('title_sp', ['label' => 'רווח שם ↔ תיאור', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 6], 'selectors' => ['{{WRAPPER}} .dtt-title' => 'margin-bottom:{{SIZE}}{{UNIT}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'desc_t', 'label' => 'תיאור', 'selector' => '{{WRAPPER}} .dtt-desc']);
        $this->add_control('desc_c', ['label' => 'צבע התיאור', 'type' => CM::COLOR, 'default' => '#6E675D', 'selectors' => ['{{WRAPPER}} .dtt-desc' => 'color:{{VALUE}}']]);
        $this->add_responsive_control('ic_size', ['label' => 'גודל העיגול', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 46], 'mobile_default' => ['unit' => 'px', 'size' => 40],
            'selectors' => ['{{WRAPPER}} .dtt-ic' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}}']]);
        $this->add_control('ic_c', ['label' => 'צבע העיגול והחץ', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}} .dtt-ic' => 'color:{{VALUE}};border-color:{{VALUE}}']]);
        $this->add_control('ic_bw', ['label' => 'עובי קו העיגול', 'type' => CM::NUMBER, 'default' => 1, 'min' => 0, 'max' => 4, 'selectors' => ['{{WRAPPER}} .dtt-ic' => 'border-width:{{VALUE}}px']]);
        $this->add_control('ic_hbg', ['label' => 'מילוי העיגול במעבר עכבר', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}} .dtt-row:hover .dtt-ic' => 'background-color:{{VALUE}}']]);
        $this->add_control('ic_hc', ['label' => 'צבע החץ במעבר עכבר', 'type' => CM::COLOR, 'default' => '#F2F1EC', 'selectors' => ['{{WRAPPER}} .dtt-row:hover .dtt-ic' => 'color:{{VALUE}}']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $groups = array_filter((array) ($s['groups'] ?? []));
        if (!$groups) return;
        $id = $this->get_id();
        $tabs = $panels = '';
        $arrow = '<svg viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7M8 7h9v9"/></svg>';
        foreach (array_values($groups) as $i => $slug) {
            $term = get_term_by('slug', $slug, DT_GROUP_TAX);
            if (!$term) continue;
            $on = $i === 0;
            $tabs .= '<button type="button" class="dtt-tab' . ($on ? ' is-active' : '') . '" role="tab" aria-selected="' . ($on ? 'true' : 'false') . '" data-i="' . $i . '">' . esc_html($term->name) . '</button>';
            $posts = get_posts(['post_type' => DT_CPT, 'numberposts' => (int) ($s['max'] ?: 4), 'orderby' => 'menu_order title', 'order' => 'ASC',
                                'tax_query' => [['taxonomy' => DT_GROUP_TAX, 'field' => 'slug', 'terms' => $slug]]]);
            $rows = '';
            foreach ($posts as $p) {
                $desc = (int) $s['words'] ? wp_trim_words(dt_get($p->ID, 'summary'), (int) $s['words'], '…') : '';
                $rows .= '<a class="dtt-row" href="' . esc_url(get_permalink($p)) . '"><span class="dtt-txt"><span class="dtt-title">' . esc_html(get_the_title($p)) . '</span>'
                       . ($desc ? '<span class="dtt-desc">' . esc_html($desc) . '</span>' : '') . '</span><span class="dtt-ic">' . $arrow . '</span></a>';
            }
            $panels .= '<div class="dtt-panel" role="tabpanel" data-i="' . $i . '"' . ($on ? '' : ' hidden') . '>' . $rows . '</div>';
        }
        echo '<div class="dtt" id="dtt-' . esc_attr($id) . '"><div class="dtt-tabs" role="tablist">' . $tabs . '</div>' . $panels . '</div>';
    }
}

/* ═══════════════════════════ 2 · לפני ואחרי ═══════════════════════════ */
class DT_Before_After_Widget extends DT_W_Base {
    public function get_name()  { return 'dt_before_after'; }
    public function get_title() { return 'Dafna · לפני ואחרי'; }
    public function get_icon()  { return 'eicon-image-before-after'; }

    protected function register_controls() {
        $this->start_controls_section('c', ['label' => 'תמונות']);
        $r = new Repeater();
        $r->add_control('before', ['label' => 'לפני', 'type' => CM::MEDIA]);
        $r->add_control('after', ['label' => 'אחרי', 'type' => CM::MEDIA]);
        $r->add_control('title', ['label' => 'שם הטיפול', 'type' => CM::TEXT, 'default' => 'שם הטיפול', 'label_block' => true]);
        $rot = ['auto' => 'אוטומטי (תמונה לרוחב → לאורך)', 'none' => 'בלי סיבוב', 'cw' => 'לסובב ימינה', 'ccw' => 'לסובב שמאלה'];
        $r->add_control('rot_b', ['label' => 'סיבוב "לפני"', 'type' => CM::SELECT, 'default' => 'auto', 'options' => $rot]);
        $r->add_control('rot_a', ['label' => 'סיבוב "אחרי"', 'type' => CM::SELECT, 'default' => 'auto', 'options' => $rot]);
        $this->add_control('items', ['label' => 'זוגות', 'type' => CM::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ title }}}', 'default' => [['title' => 'שם הטיפול']]]);
        $this->add_control('lightbox', ['label' => 'לחיצה פותחת תמונה גדולה (Lightbox)', 'type' => CM::SWITCHER, 'default' => 'yes']);
        $this->add_control('labels', ['label' => 'תוויות "לפני / אחרי"', 'type' => CM::SWITCHER, 'default' => 'yes']);
        $this->add_control('lbl_b', ['label' => 'טקסט "לפני"', 'type' => CM::TEXT, 'default' => 'לפני', 'condition' => ['labels' => 'yes']]);
        $this->add_control('lbl_a', ['label' => 'טקסט "אחרי"', 'type' => CM::TEXT, 'default' => 'אחרי', 'condition' => ['labels' => 'yes']]);
        $this->end_controls_section();

        $this->start_controls_section('c2', ['label' => 'תנועה ומידות']);
        $this->add_control('loop', ['label' => 'תנועה רציפה בלופ', 'type' => CM::SWITCHER, 'default' => 'yes']);
        $this->marquee_controls(80);
        $this->add_responsive_control('card_w', ['label' => 'רוחב כרטיס', 'type' => CM::SLIDER, 'size_units' => ['px'], 'range' => ['px' => ['min' => 200, 'max' => 900]],
            'default' => ['unit' => 'px', 'size' => 560], 'tablet_default' => ['unit' => 'px', 'size' => 420], 'mobile_default' => ['unit' => 'px', 'size' => 300],
            'selectors' => ['{{WRAPPER}} .dtba-card' => '--dtba-w:{{SIZE}}{{UNIT}}']]);
        $this->add_control('ratio', ['label' => 'יחס כל תמונה', 'type' => CM::SELECT, 'default' => '3/4',
            'options' => ['3/4' => '3:4 (לאורך)', '4/5' => '4:5', '2/3' => '2:3 (גבוה)', '1/1' => 'ריבוע'], 'selectors' => ['{{WRAPPER}} .dtba-frame' => '--dtba-ratio:{{VALUE}}']]);
        $this->add_responsive_control('pair_gap', ['label' => 'רווח בין לפני לאחרי', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 8], 'selectors' => ['{{WRAPPER}} .dtba-pair' => '--dtba-gap:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();

        $this->start_controls_section('s_card', ['label' => 'כרטיס', 'tab' => CM::TAB_STYLE]);
        $this->add_control('card_bg', ['label' => 'רקע', 'type' => CM::COLOR, 'default' => 'rgba(239,236,228,.05)', 'selectors' => ['{{WRAPPER}} .dtba-card' => 'background-color:{{VALUE}}']]);
        $this->add_group_control(GB::get_type(), ['name' => 'card_b', 'selector' => '{{WRAPPER}} .dtba-card',
            'fields_options' => ['border' => ['default' => 'solid'], 'width' => ['default' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'isLinked' => true]], 'color' => ['default' => 'rgba(239,236,228,.12)']]]);
        $this->add_responsive_control('card_r', ['label' => 'פינות', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 18], 'selectors' => ['{{WRAPPER}} .dtba-card' => 'border-radius:{{SIZE}}{{UNIT}}']]);
        $this->add_responsive_control('card_p', ['label' => 'ריפוד', 'type' => CM::DIMENSIONS, 'size_units' => ['px'], 'default' => ['top' => '12', 'right' => '12', 'bottom' => '16', 'left' => '12', 'unit' => 'px', 'isLinked' => false],
            'selectors' => ['{{WRAPPER}} .dtba-card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']]);
        $this->add_responsive_control('img_r', ['label' => 'פינות התמונות', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 12], 'selectors' => ['{{WRAPPER}} .dtba-frame' => 'border-radius:{{SIZE}}{{UNIT}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'cap_t', 'label' => 'שם הטיפול', 'selector' => '{{WRAPPER}} .dtba-cap']);
        $this->add_control('cap_c', ['label' => 'צבע שם הטיפול', 'type' => CM::COLOR, 'default' => 'rgba(239,236,228,.55)', 'selectors' => ['{{WRAPPER}} .dtba-cap' => 'color:{{VALUE}}']]);
        $this->add_control('cap_al', ['label' => 'יישור השם', 'type' => CM::CHOOSE, 'default' => 'center',
            'options' => ['right' => ['title' => 'ימין', 'icon' => 'eicon-text-align-right'], 'center' => ['title' => 'מרכז', 'icon' => 'eicon-text-align-center'], 'left' => ['title' => 'שמאל', 'icon' => 'eicon-text-align-left']],
            'selectors' => ['{{WRAPPER}} .dtba-cap' => 'text-align:{{VALUE}}']]);
        $this->add_control('cap_sp', ['label' => 'רווח מעל השם', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 16], 'selectors' => ['{{WRAPPER}} .dtba-cap' => 'margin-top:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();

        $this->start_controls_section('s_lbl', ['label' => 'תוויות לפני / אחרי', 'tab' => CM::TAB_STYLE, 'condition' => ['labels' => 'yes']]);
        $this->add_group_control(GT::get_type(), ['name' => 'lbl_t', 'selector' => '{{WRAPPER}} .dtba-lbl']);
        $this->add_control('lbl_c', ['label' => 'צבע טקסט', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}} .dtba-lbl' => 'color:{{VALUE}}']]);
        $this->add_control('lbl_bg', ['label' => 'רקע', 'type' => CM::COLOR, 'default' => 'rgba(242,241,236,.9)', 'selectors' => ['{{WRAPPER}} .dtba-lbl' => 'background-color:{{VALUE}}']]);
        $this->add_control('lbl_p', ['label' => 'ריפוד', 'type' => CM::DIMENSIONS, 'size_units' => ['px'], 'default' => ['top' => '2', 'right' => '10', 'bottom' => '2', 'left' => '10', 'unit' => 'px', 'isLinked' => false],
            'selectors' => ['{{WRAPPER}} .dtba-lbl' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};border-radius:100px']]);
        $this->end_controls_section();
    }

    protected function img($m, $rot, $label, $title, $s, $group) {
        $id = (int) ($m['id'] ?? 0); $url = $m['url'] ?? '';
        if (!$id && !$url) return '';
        $full = $id ? wp_get_attachment_image_url($id, 'full') : $url;
        $img  = $id ? wp_get_attachment_image($id, 'large', false, ['alt' => esc_attr($title . ' · ' . $label), 'loading' => 'lazy']) : '<img src="' . esc_url($url) . '" alt="' . esc_attr($title) . '" loading="lazy">';
        $cls  = $rot === 'cw' ? ' is-cw' : ($rot === 'ccw' ? ' is-ccw' : '');
        $lb   = ($s['lightbox'] ?? '') === 'yes';
        $tag  = $lb ? 'a' : 'span';
        $att  = $lb ? ' href="' . esc_url($full) . '" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="' . esc_attr($group) . '" data-elementor-lightbox-title="' . esc_attr($title . ' · ' . $label) . '"' : '';
        $lbl  = ($s['labels'] ?? '') === 'yes' ? '<span class="dtba-lbl">' . esc_html($label) . '</span>' : '';
        return '<' . $tag . ' class="dtba-img"' . $att . '><span class="dtba-frame' . $cls . '" data-rot="' . esc_attr($rot) . '">' . $img . '</span>' . $lbl . '</' . $tag . '>';
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $group = 'ba-' . $this->get_id();
        $html = '';
        foreach ((array) ($s['items'] ?? []) as $it) {
            $t = $it['title'] ?? '';
            $html .= '<figure class="dtba-card"><div class="dtba-pair">'
                   . $this->img($it['before'] ?? [], $it['rot_b'] ?? 'auto', $s['lbl_b'] ?? 'לפני', $t, $s, $group)
                   . $this->img($it['after'] ?? [], $it['rot_a'] ?? 'auto', $s['lbl_a'] ?? 'אחרי', $t, $s, $group)
                   . '</div>' . ($t !== '' ? '<figcaption class="dtba-cap">' . esc_html($t) . '</figcaption>' : '') . '</figure>';
        }
        if (($s['loop'] ?? '') === 'yes') { $this->marquee($html, $s, 'dtba'); return; }
        echo '<div class="dtba dfm-set" style="flex-wrap:wrap;justify-content:center;padding:0">' . $html . '</div>';
    }
}

/* ═══════════════════════════ 3 · מותגים ═══════════════════════════ */
class DT_Brands_Widget extends DT_W_Base {
    public function get_name()  { return 'dt_brands'; }
    public function get_title() { return 'Dafna · מותגים'; }
    public function get_icon()  { return 'eicon-logo'; }

    protected function register_controls() {
        $this->start_controls_section('c', ['label' => 'לוגואים']);
        $r = new Repeater();
        $r->add_control('logo', ['label' => 'לוגו', 'type' => CM::MEDIA]);
        $r->add_control('name', ['label' => 'שם המותג', 'type' => CM::TEXT, 'default' => 'מותג']);
        $r->add_control('link', ['label' => 'קישור', 'type' => CM::URL, 'default' => ['url' => '/shop/'], 'description' => 'ברירת מחדל: החנות']);
        $this->add_control('items', ['label' => 'מותגים', 'type' => CM::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ name }}}']);
        $this->marquee_controls(30);
        $this->add_responsive_control('logo_h', ['label' => 'גובה לוגו', 'type' => CM::SLIDER, 'size_units' => ['px'], 'range' => ['px' => ['min' => 16, 'max' => 120]],
            'default' => ['unit' => 'px', 'size' => 40], 'mobile_default' => ['unit' => 'px', 'size' => 30], 'selectors' => ['{{WRAPPER}} .dtb-logo' => '--dtb-h:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();

        $this->start_controls_section('s', ['label' => 'עיצוב', 'tab' => CM::TAB_STYLE]);
        $this->add_control('op', ['label' => 'שקיפות לוגו', 'type' => CM::SLIDER, 'range' => ['px' => ['min' => .1, 'max' => 1, 'step' => .05]], 'default' => ['size' => .85], 'selectors' => ['{{WRAPPER}} .dtb-logo' => 'opacity:{{SIZE}}']]);
        $this->add_control('op_h', ['label' => 'שקיפות במעבר עכבר', 'type' => CM::SLIDER, 'range' => ['px' => ['min' => .1, 'max' => 1, 'step' => .05]], 'default' => ['size' => 1], 'selectors' => ['{{WRAPPER}} .dtb-logo:hover' => 'opacity:{{SIZE}}']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $html = '';
        foreach ((array) ($s['items'] ?? []) as $it) {
            $id = (int) ($it['logo']['id'] ?? 0); $src = $it['logo']['url'] ?? '';
            if (!$id && !$src) continue;
            $img = $id ? wp_get_attachment_image($id, 'medium', false, ['alt' => esc_attr($it['name'] ?? ''), 'loading' => 'lazy']) : '<img src="' . esc_url($src) . '" alt="' . esc_attr($it['name'] ?? '') . '">';
            $url = $it['link']['url'] ?? '';
            $html .= $url ? '<a class="dtb-logo" href="' . esc_url($url) . '"' . (!empty($it['link']['is_external']) ? ' target="_blank" rel="noopener"' : '') . ' aria-label="' . esc_attr($it['name'] ?? '') . '">' . $img . '</a>'
                          : '<span class="dtb-logo">' . $img . '</span>';
        }
        $this->marquee($html, $s, 'dtb');
    }
}

/* ═══════════════════════════ 4 · פס תעודות ═══════════════════════════ */
class DT_Certs_Widget extends DT_W_Base {
    public function get_name()  { return 'dt_certs'; }
    public function get_title() { return 'Dafna · פס תעודות'; }
    public function get_icon()  { return 'eicon-check-circle-o'; }

    protected function register_controls() {
        $this->start_controls_section('c', ['label' => 'הכשרות']);
        $r = new Repeater();
        $r->add_control('text', ['label' => 'הכשרה', 'type' => CM::TEXT, 'label_block' => true, 'default' => 'הכשרה']);
        $this->add_control('items', ['label' => 'רשימה', 'type' => CM::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ text }}}']);
        $this->add_control('icon', ['label' => 'אייקון בין פריטים', 'type' => CM::ICONS, 'default' => ['value' => 'fas fa-circle', 'library' => 'fa-solid']]);
        $this->marquee_controls(60);
        $this->end_controls_section();

        $this->start_controls_section('s', ['label' => 'עיצוב', 'tab' => CM::TAB_STYLE]);
        $this->add_group_control(GT::get_type(), ['name' => 't', 'selector' => '{{WRAPPER}} .dtc-item']);
        $this->add_control('c', ['label' => 'צבע טקסט', 'type' => CM::COLOR, 'default' => '#6E675D', 'selectors' => ['{{WRAPPER}} .dtc-item' => 'color:{{VALUE}}']]);
        $this->add_control('ic', ['label' => 'צבע האייקון', 'type' => CM::COLOR, 'default' => 'rgba(110,103,93,.5)', 'selectors' => ['{{WRAPPER}} .dtc-sep' => 'color:{{VALUE}}']]);
        $this->add_control('is', ['label' => 'גודל האייקון', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 5], 'selectors' => ['{{WRAPPER}} .dtc-sep' => 'font-size:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ob_start(); \Elementor\Icons_Manager::render_icon($s['icon'] ?? [], ['aria-hidden' => 'true']); $ic = ob_get_clean();
        $html = '';
        foreach ((array) ($s['items'] ?? []) as $it) {
            if (trim($it['text'] ?? '') === '') continue;
            $html .= '<span class="dtc-item"><span class="dtc-t">' . esc_html($it['text']) . '</span><span class="dtc-sep">' . $ic . '</span></span>';
        }
        $this->marquee($html, $s, 'dtc');
    }
}

/* ═══════════════════════════ 5 · המלצות (גוגל / ידני) ═══════════════════════════ */
function dt_google_reviews() {
    $key = dt_biz('google_key'); $place = dt_biz('google_place');
    if (!$key || !$place) return [];
    $tk = 'dt_reviews_' . md5($place);
    $c = get_transient($tk);
    if ($c !== false) return $c;
    $res = wp_remote_get('https://places.googleapis.com/v1/places/' . rawurlencode($place) . '?languageCode=iw', ['timeout' => 10, 'headers' => [
        'X-Goog-Api-Key' => $key, 'X-Goog-FieldMask' => 'reviews,rating,userRatingCount,googleMapsUri']]);
    $out = [];
    if (!is_wp_error($res) && wp_remote_retrieve_response_code($res) === 200) {
        $d = json_decode(wp_remote_retrieve_body($res), true);
        foreach ((array) ($d['reviews'] ?? []) as $r) {
            $txt = $r['text']['text'] ?? ($r['originalText']['text'] ?? '');
            if ($txt === '') continue;
            $out[] = ['q' => $txt, 'name' => $r['authorAttribution']['displayName'] ?? '', 'stars' => (int) ($r['rating'] ?? 5), 'when' => $r['relativePublishTimeDescription'] ?? ''];
        }
    }
    set_transient($tk, $out, $out ? 12 * HOUR_IN_SECONDS : 30 * MINUTE_IN_SECONDS);
    return $out;
}

class DT_Reviews_Widget extends DT_W_Base {
    public function get_name()  { return 'dt_reviews'; }
    public function get_title() { return 'Dafna · המלצות'; }
    public function get_icon()  { return 'eicon-testimonial'; }

    protected function register_controls() {
        $this->start_controls_section('c', ['label' => 'תוכן']);
        $this->add_control('src', ['label' => 'מקור', 'type' => CM::SELECT, 'default' => 'google', 'options' => ['google' => 'גוגל (מ"פרטי העסק")', 'manual' => 'ידני']]);
        $this->add_control('note', ['type' => CM::RAW_HTML, 'content_classes' => 'elementor-descriptor', 'condition' => ['src' => 'google'],
            'raw' => 'המפתח והמזהה של העסק נכתבים ב<b>פרטי העסק</b>. כל עוד אין — מוצגות ההמלצות הידניות שלמטה. ההמלצות מתעדכנות פעם ב-12 שעות.']);
        $this->add_control('min_stars', ['label' => 'רק המלצות עם לפחות', 'type' => CM::SELECT, 'default' => '4', 'options' => ['1' => 'כוכב 1', '3' => '3 כוכבים', '4' => '4 כוכבים', '5' => '5 כוכבים'], 'condition' => ['src' => 'google']]);
        $this->add_control('count', ['label' => 'כמה להציג', 'type' => CM::NUMBER, 'min' => 1, 'max' => 10, 'default' => 2]);
        $r = new Repeater();
        $r->add_control('q', ['label' => 'ציטוט', 'type' => CM::TEXTAREA, 'rows' => 4]);
        $r->add_control('name', ['label' => 'שם', 'type' => CM::TEXT]);
        $this->add_control('items', ['label' => 'המלצות ידניות', 'type' => CM::REPEATER, 'fields' => $r->get_controls(), 'title_field' => '{{{ name }}}',
            'default' => [['q' => 'הסקשן ממתין להמלצות אמיתיות של לקוחות.', 'name' => 'דרוש תוכן'], ['q' => 'עד שיגיעו — אין כאן ציטוטים.', 'name' => 'דרוש תוכן']]]);
        $this->add_control('stars', ['label' => 'כוכבים', 'type' => CM::SWITCHER, 'default' => '']);
        $this->add_responsive_control('cols', ['label' => 'עמודות', 'type' => CM::NUMBER, 'min' => 1, 'max' => 4, 'default' => 2, 'tablet_default' => 1, 'mobile_default' => 1,
            'selectors' => ['{{WRAPPER}} .dtr' => '--dtr-cols:{{VALUE}}']]);
        $this->add_responsive_control('gap', ['label' => 'רווח בין כרטיסים', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 32], 'mobile_default' => ['unit' => 'px', 'size' => 16],
            'selectors' => ['{{WRAPPER}} .dtr' => 'gap:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();

        $this->start_controls_section('s', ['label' => 'כרטיס', 'tab' => CM::TAB_STYLE]);
        $this->add_control('bg', ['label' => 'רקע', 'type' => CM::COLOR, 'default' => 'rgba(239,236,228,.06)', 'selectors' => ['{{WRAPPER}} .dtr-card' => 'background-color:{{VALUE}}']]);
        $this->add_group_control(GB::get_type(), ['name' => 'b', 'selector' => '{{WRAPPER}} .dtr-card',
            'fields_options' => ['border' => ['default' => 'dashed'], 'width' => ['default' => ['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1, 'isLinked' => true]], 'color' => ['default' => 'rgba(239,236,228,.18)']]]);
        $this->add_responsive_control('r', ['label' => 'פינות', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 18], 'selectors' => ['{{WRAPPER}} .dtr-card' => 'border-radius:{{SIZE}}{{UNIT}}']]);
        $this->add_responsive_control('p', ['label' => 'ריפוד', 'type' => CM::DIMENSIONS, 'size_units' => ['px'], 'default' => ['top' => '40', 'right' => '32', 'bottom' => '40', 'left' => '32', 'unit' => 'px', 'isLinked' => false],
            'selectors' => ['{{WRAPPER}} .dtr-card' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'qt', 'label' => 'ציטוט', 'selector' => '{{WRAPPER}} .dtr-q']);
        $this->add_control('qc', ['label' => 'צבע ציטוט', 'type' => CM::COLOR, 'default' => 'rgba(239,236,228,.67)', 'selectors' => ['{{WRAPPER}} .dtr-q' => 'color:{{VALUE}}']]);
        $this->add_control('line', ['label' => 'קו מעל השם', 'type' => CM::COLOR, 'default' => 'rgba(239,236,228,.12)', 'selectors' => ['{{WRAPPER}} .dtr-n' => 'border-top:1px solid {{VALUE}}']]);
        $this->add_responsive_control('nsp', ['label' => 'רווח ציטוט ↔ שם', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 24], 'selectors' => ['{{WRAPPER}} .dtr-n' => 'margin-top:{{SIZE}}{{UNIT}};padding-top:{{SIZE}}{{UNIT}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'nt', 'label' => 'שם', 'selector' => '{{WRAPPER}} .dtr-n']);
        $this->add_control('nc', ['label' => 'צבע שם', 'type' => CM::COLOR, 'default' => 'rgba(239,236,228,.72)', 'selectors' => ['{{WRAPPER}} .dtr-n' => 'color:{{VALUE}}']]);
        $this->add_control('sc', ['label' => 'צבע כוכבים', 'type' => CM::COLOR, 'default' => '#DFDAD1', 'selectors' => ['{{WRAPPER}} .dtr-stars' => 'color:{{VALUE}}'], 'condition' => ['stars' => 'yes']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $items = [];
        if (($s['src'] ?? 'google') === 'google') {
            foreach (dt_google_reviews() as $r) if ($r['stars'] >= (int) ($s['min_stars'] ?: 4)) $items[] = $r;
        }
        if (!$items) foreach ((array) ($s['items'] ?? []) as $r) $items[] = ['q' => $r['q'] ?? '', 'name' => $r['name'] ?? '', 'stars' => 5];
        $items = array_slice($items, 0, max(1, (int) $s['count']));
        echo '<div class="dtr">';
        foreach ($items as $r) {
            echo '<figure class="dtr-card">' . (($s['stars'] ?? '') === 'yes' ? '<span class="dtr-stars" aria-label="' . (int) $r['stars'] . ' כוכבים">' . str_repeat('★', (int) $r['stars']) . '</span>' : '')
               . '<blockquote class="dtr-q">' . esc_html($r['q']) . '</blockquote><figcaption class="dtr-n">' . esc_html($r['name']) . '</figcaption></figure>';
        }
        echo '</div>';
    }
}

/* ═══════════════════════════ 6 · שאלות ותשובות ═══════════════════════════ */
class DT_Faq_Widget extends DT_W_Base {
    public function get_name()  { return 'dt_faq_list'; }
    public function get_title() { return 'Dafna · שאלות ותשובות'; }
    public function get_icon()  { return 'eicon-accordion'; }

    protected function register_controls() {
        $this->start_controls_section('c', ['label' => 'תוכן']);
        $this->add_control('place', ['label' => 'אילו שאלות', 'type' => CM::SELECT, 'default' => 'home',
            'options' => dt_faq_places() + ['current' => 'הטיפול שבעמוד (לתבנית טיפול)']]);
        $this->add_control('note', ['type' => CM::RAW_HTML, 'content_classes' => 'elementor-descriptor',
            'raw' => 'השאלות נערכות בוורדפרס: <b>שאלות ותשובות</b> ← כל שאלה ← "איפה השאלה מופיעה".']);
        $this->add_control('schema', ['label' => 'סימון FAQ לגוגל', 'type' => CM::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();

        $this->start_controls_section('s', ['label' => 'עיצוב', 'tab' => CM::TAB_STYLE]);
        $this->add_control('line', ['label' => 'צבע קו', 'type' => CM::COLOR, 'default' => '#DFDAD1', 'selectors' => ['{{WRAPPER}} .dtq' => 'border-top:1px solid {{VALUE}}', '{{WRAPPER}} .dtq-item' => 'border-bottom:1px solid {{VALUE}}']]);
        $this->add_responsive_control('pad', ['label' => 'ריפוד שאלה', 'type' => CM::DIMENSIONS, 'size_units' => ['px'], 'default' => ['top' => '20', 'right' => '0', 'bottom' => '20', 'left' => '0', 'unit' => 'px', 'isLinked' => false],
            'selectors' => ['{{WRAPPER}} .dtq-item summary' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'qt', 'label' => 'שאלה', 'selector' => '{{WRAPPER}} .dtq-q']);
        $this->add_control('qc', ['label' => 'צבע שאלה', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}} .dtq-q' => 'color:{{VALUE}}']]);
        $this->add_control('icc', ['label' => 'צבע +', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}} .dtq-ic' => 'color:{{VALUE}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'at', 'label' => 'תשובה', 'selector' => '{{WRAPPER}} .dtq-a']);
        $this->add_control('ac', ['label' => 'צבע תשובה', 'type' => CM::COLOR, 'default' => '#6E675D', 'selectors' => ['{{WRAPPER}} .dtq-a' => 'color:{{VALUE}}']]);
        $this->add_responsive_control('ap', ['label' => 'רווח מתחת לתשובה', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 20], 'selectors' => ['{{WRAPPER}} .dtq-a' => 'padding-bottom:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $place = $s['place'] ?? 'home';
        if ($place === 'current') {
            $id = get_the_ID();
            if (get_post_type($id) !== DT_CPT) { $q = get_posts(['post_type' => DT_CPT, 'numberposts' => 1, 'fields' => 'ids']); $id = $q ? $q[0] : 0; }
            $items = $id ? dt_faq_items('treatment:' . $id) : [];
            if (!$items && $id) foreach (dt_get($id, 'faq') as $r) $items[] = ['q' => $r['q'] ?? '', 'a' => wpautop(esc_html($r['a'] ?? ''))];
        } else {
            $items = dt_faq_items($place);
        }
        if (!$items) { if (\Elementor\Plugin::$instance->editor->is_edit_mode()) echo '<p>אין עדיין שאלות למקום הזה — מוסיפים ב"שאלות ותשובות" בוורדפרס.</p>'; return; }
        echo '<div class="dtq">';
        $ld = [];
        foreach ($items as $it) {
            echo '<details class="dtq-item"><summary><span class="dtq-q">' . esc_html($it['q']) . '</span><span class="dtq-ic" aria-hidden="true">+</span></summary><div class="dtq-a">' . wp_kses_post($it['a']) . '</div></details>';
            $ld[] = ['@type' => 'Question', 'name' => $it['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => wp_strip_all_tags($it['a'])]];
        }
        echo '</div>';
        if (($s['schema'] ?? '') === 'yes') echo '<script type="application/ld+json">' . wp_json_encode(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $ld], JSON_UNESCAPED_UNICODE) . '</script>';
    }
}

/* ═══════════════════════════ 7 · כרטיסי טיפולים (זכוכית) ═══════════════════════════ */
class DT_Treatment_Cards_Widget extends DT_W_Base {
    public function get_name()  { return 'dt_treatment_cards'; }
    public function get_title() { return 'Dafna · כרטיסי טיפולים'; }
    public function get_icon()  { return 'eicon-posts-grid'; }

    protected function register_controls() {
        $opts = [];
        foreach (get_posts(['post_type' => DT_CPT, 'numberposts' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC']) as $p) $opts[$p->ID] = get_the_title($p);

        $this->start_controls_section('c', ['label' => 'תוכן']);
        $this->add_control('note', ['type' => CM::RAW_HTML, 'content_classes' => 'elementor-descriptor',
            'raw' => 'בוחרים טיפולים מהרשימה — הסדר כאן הוא הסדר באתר (הראשון מימין). העינית, השם והתיאור נמשכים מהטיפול עצמו.']);
        $this->add_control('ids', ['label' => 'טיפולים', 'type' => CM::SELECT2, 'multiple' => true, 'label_block' => true, 'options' => $opts]);
        $this->add_control('words', ['label' => 'אורך התיאור (מילים)', 'type' => CM::NUMBER, 'min' => 0, 'max' => 60, 'default' => 12]);
        $this->add_responsive_control('cols', ['label' => 'עמודות', 'type' => CM::NUMBER, 'min' => 1, 'max' => 4, 'default' => 3, 'tablet_default' => 3, 'mobile_default' => 1,
            'selectors' => ['{{WRAPPER}} .dtk' => '--dtk-cols:{{VALUE}}']]);
        $this->add_responsive_control('gap', ['label' => 'רווח בין כרטיסים', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 42], 'tablet_default' => ['unit' => 'px', 'size' => 16], 'mobile_default' => ['unit' => 'px', 'size' => 16],
            'selectors' => ['{{WRAPPER}} .dtk' => 'gap:{{SIZE}}{{UNIT}}']]);
        $this->add_control('ratio', ['label' => 'צורת התמונה', 'type' => CM::SELECT, 'default' => '3/2',
            'options' => ['3/2' => 'רוחב (3:2)', '4/3' => '4:3', '1/1' => 'ריבוע', '4/5' => 'לאורך (4:5)'], 'selectors' => ['{{WRAPPER}} .dtk-img' => 'aspect-ratio:{{VALUE}}']]);
        $this->end_controls_section();

        $this->start_controls_section('s_card', ['label' => 'כרטיס', 'tab' => CM::TAB_STYLE]);
        $this->add_control('cbg', ['label' => 'רקע הכרטיס', 'type' => CM::COLOR, 'default' => '#FFFFFF', 'selectors' => ['{{WRAPPER}} .dtk-card' => 'background-color:{{VALUE}}']]);
        $this->add_responsive_control('r', ['label' => 'פינות', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 12], 'selectors' => ['{{WRAPPER}} .dtk-card' => 'border-radius:{{SIZE}}{{UNIT}}']]);
        $this->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(), ['name' => 'sh', 'label' => 'צל (הבלטה)', 'selector' => '{{WRAPPER}} .dtk-card',
            'fields_options' => ['box_shadow_type' => ['default' => 'yes'], 'box_shadow' => ['default' => ['horizontal' => 0, 'vertical' => 16, 'blur' => 36, 'spread' => -8, 'color' => 'rgba(46,42,36,0.14)']]]]);
        $this->add_control('sh_h', ['label' => 'צל חזק יותר במעבר עכבר', 'type' => CM::SWITCHER, 'default' => 'yes']);
        $this->add_control('zoom', ['label' => 'הגדלת התמונה במעבר עכבר', 'type' => CM::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();

        $this->start_controls_section('s_panel', ['label' => 'לוח הטקסט (זכוכית)', 'tab' => CM::TAB_STYLE]);
        $this->add_control('bg', ['label' => 'רקע', 'type' => CM::COLOR, 'default' => 'rgba(255,255,255,0.72)', 'selectors' => ['{{WRAPPER}} .dtk-panel' => 'background-color:{{VALUE}}']]);
        $this->add_control('blur', ['label' => 'טשטוש הזכוכית', 'type' => CM::SLIDER, 'range' => ['px' => ['min' => 0, 'max' => 60]], 'default' => ['unit' => 'px', 'size' => 24],
            'selectors' => ['{{WRAPPER}} .dtk-panel' => '-webkit-backdrop-filter:blur({{SIZE}}px);backdrop-filter:blur({{SIZE}}px)']]);
        $this->add_control('overlap', ['label' => 'חפיפה על התמונה (כדי שהזכוכית תיראה)', 'type' => CM::SLIDER, 'range' => ['px' => ['min' => 0, 'max' => 80]], 'default' => ['unit' => 'px', 'size' => 0],
            'selectors' => ['{{WRAPPER}} .dtk-panel' => 'margin-top:calc(-1 * {{SIZE}}{{UNIT}})']]);
        $this->add_control('bt', ['label' => 'קו עליון', 'type' => CM::COLOR, 'default' => 'rgba(255,255,255,0.6)', 'selectors' => ['{{WRAPPER}} .dtk-panel' => 'border-top:1px solid {{VALUE}}']]);
        $this->add_responsive_control('pp', ['label' => 'ריפוד', 'type' => CM::DIMENSIONS, 'size_units' => ['px'],
            'default' => ['top' => '16', 'right' => '16', 'bottom' => '14', 'left' => '16', 'unit' => 'px', 'isLinked' => false],
            'mobile_default' => ['top' => '14', 'right' => '14', 'bottom' => '14', 'left' => '14', 'unit' => 'px', 'isLinked' => false],
            'selectors' => ['{{WRAPPER}} .dtk-panel' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']]);
        $this->add_responsive_control('ph', ['label' => 'גובה מינימלי (כדי שכל הלוחות יהיו שווים)', 'type' => CM::SLIDER, 'range' => ['px' => ['min' => 0, 'max' => 260]],
            'default' => ['unit' => 'px', 'size' => 108], 'tablet_default' => ['unit' => 'px', 'size' => 70], 'mobile_default' => ['unit' => 'px', 'size' => 96],
            'selectors' => ['{{WRAPPER}} .dtk-panel' => 'min-height:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();

        $this->start_controls_section('s_tx', ['label' => 'טקסטים', 'tab' => CM::TAB_STYLE]);
        $this->add_group_control(GT::get_type(), ['name' => 'eb_t', 'label' => 'עינית', 'selector' => '{{WRAPPER}} .dtk-eb']);
        $this->add_control('eb_c', ['label' => 'צבע עינית', 'type' => CM::COLOR, 'default' => '#6E675D', 'selectors' => ['{{WRAPPER}} .dtk-eb' => 'color:{{VALUE}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'ti_t', 'label' => 'שם הטיפול', 'selector' => '{{WRAPPER}} .dtk-title']);
        $this->add_control('ti_c', ['label' => 'צבע השם', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}} .dtk-title' => 'color:{{VALUE}}']]);
        $this->add_group_control(GT::get_type(), ['name' => 'de_t', 'label' => 'תיאור', 'selector' => '{{WRAPPER}} .dtk-desc']);
        $this->add_control('de_c', ['label' => 'צבע התיאור', 'type' => CM::COLOR, 'default' => '#6E675D', 'selectors' => ['{{WRAPPER}} .dtk-desc' => 'color:{{VALUE}}']]);
        $this->add_responsive_control('desc_show', ['label' => 'תיאור', 'type' => CM::SELECT, 'default' => '-webkit-box', 'tablet_default' => 'none', 'mobile_default' => '-webkit-box',
            'options' => ['-webkit-box' => 'מוצג', 'none' => 'מוסתר'], 'selectors' => ['{{WRAPPER}} .dtk-desc' => 'display:{{VALUE}}']]);
        $this->add_responsive_control('lines', ['label' => 'שורות תיאור (ואז "…")', 'type' => CM::NUMBER, 'min' => 1, 'max' => 8, 'default' => 2,
            'selectors' => ['{{WRAPPER}} .dtk-desc' => '-webkit-line-clamp:{{VALUE}}']]);
        $this->add_responsive_control('tx_gap', ['label' => 'רווח בין הטקסטים', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 6], 'selectors' => ['{{WRAPPER}} .dtk-panel' => 'gap:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $ids = array_filter(array_map('intval', (array) ($s['ids'] ?? [])));
        if (!$ids) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) echo '<p>בחרי טיפולים בהגדרות הווידג׳ט.</p>';
            return;
        }
        $cls = 'dtk' . (($s['zoom'] ?? '') === 'yes' ? ' is-zoom' : '') . (($s['sh_h'] ?? '') === 'yes' ? ' is-lift' : '');
        echo '<div class="' . esc_attr($cls) . '">';
        foreach ($ids as $id) {
            if (get_post_type($id) !== DT_CPT || get_post_status($id) !== 'publish') continue;
            $eb = dt_get($id, 'eyebrow');
            $desc = (int) $s['words'] ? wp_trim_words(dt_get($id, 'summary'), (int) $s['words'], '…') : '';
            $img = has_post_thumbnail($id)
                ? get_the_post_thumbnail($id, 'large', ['loading' => 'lazy', 'alt' => esc_attr(get_the_title($id))])
                : '';
            echo '<a class="dtk-card" href="' . esc_url(get_permalink($id)) . '"><span class="dtk-img">' . $img . '</span><span class="dtk-panel">'
               . ($eb ? '<span class="dtk-eb">' . esc_html($eb) . '</span>' : '')
               . '<span class="dtk-title">' . esc_html(get_the_title($id)) . '</span>'
               . ($desc ? '<span class="dtk-desc">' . esc_html($desc) . '</span>' : '')
               . '</span></a>';
        }
        echo '</div>';
    }
}

function dt_register_home_widgets($wm) {
    foreach (['DT_Treatment_Tabs_Widget', 'DT_Before_After_Widget', 'DT_Brands_Widget', 'DT_Certs_Widget', 'DT_Reviews_Widget', 'DT_Faq_Widget', 'DT_Treatment_Cards_Widget'] as $c) $wm->register(new $c());
}
