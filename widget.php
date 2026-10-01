<?php
/**
 * ווידג'ט אחד לכל שדה מסוג repeater.
 *
 * הוא מקבל את מפתח השדה בבנייה, ומרכיב מעצמו את השם, הכותרת, האייקון
 * והרינדור. שדה חדש בסכימה = ווידג'ט חדש בלי לכתוב מחלקה.
 *
 * השמות נשמרים כמו שהיו — dt_benefits, dt_facts, dt_steps, dt_faq —
 * כדי שתבניות Theme Builder קיימות ימשיכו לעבוד אחרי העדכון.
 */

if (!defined('ABSPATH')) exit;

class DT_Field_Widget extends \Elementor\Widget_Base {

    protected $key;
    protected $f;

    /* שני מסלולי בנייה: ברישום אנחנו מעבירים את מפתח השדה כמחרוזת;
       Elementor עצמו משכפל ווידג'ט מנתוני האלמנט (מערך) — ואז המפתח
       נגזר משם הווידג'ט, dt_<key>. בלי זה — שגיאה קריטית בכל עמוד טיפול. */
    public function __construct($data = [], $args = null) {
        if (is_string($data)) { $this->key = $data; $data = []; }
        else { $wt = is_array($data) ? ($data['widgetType'] ?? '') : ''; $this->key = $wt ? substr($wt, 3) : 'benefits'; }
        $this->f = dt_fields()[$this->key] ?? [];
        parent::__construct(is_array($data) ? $data : [], $args);
    }

    /* Elementor משכפל ווידג'ט דרך הבנאי, אז מפתח השדה חייב לשרוד */
    public function get_name()       { return 'dt_' . $this->key; }
    public function get_title()      { return 'Dafna · ' . ($this->f['widget'] ?? $this->key); }
    public function get_icon()       { return $this->f['icon'] ?? 'eicon-bullet-list'; }
    public function get_categories() { return ['dafna']; }

    /* בעורך אין פוסט טיפול בהקשר, אז מציגים את הראשון כתצוגה מקדימה */
    protected function pid() {
        $id = get_the_ID();
        if (get_post_type($id) !== DT_CPT) {
            $q = get_posts(['post_type' => DT_CPT, 'numberposts' => 1, 'fields' => 'ids']);
            if ($q) $id = $q[0];
        }
        return $id;
    }

    /* מפתח שדה הכותרת של אותו סקשן, אם קיים */
    protected function title_key() {
        $g = $this->f['group'] ?? '';
        return isset(dt_fields()[$g . '_title']) ? $g . '_title' : null;
    }

    protected function register_controls() {
        $this->start_controls_section('dt_c', ['label' => 'תוכן']);
        if ($this->title_key()) {
            $this->add_control('show_title', [
                'label'        => 'הצגת כותרת הסקשן',
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'description'  => 'הכותרת נערכת בעמוד הטיפול עצמו, לא כאן.',
            ]);
            $this->add_control('title_tag', [
                'label'   => 'תגית הכותרת',
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => ['h2'=>'H2','h3'=>'H3','h4'=>'H4','div'=>'div'],
                'condition' => ['show_title' => 'yes'],
            ]);
        }
        $this->add_control('note', [
            'type' => \Elementor\Controls_Manager::RAW_HTML,
            'raw'  => 'התוכן מגיע מהשדה <b>' . esc_html($this->f['label'] ?? $this->key)
                    . '</b> בעמוד הטיפול.',
            'content_classes' => 'elementor-descriptor',
        ]);
        $this->end_controls_section();

        /* ── עיצוב ─────────────────────────────────────────────────── */
        $this->start_controls_section('dt_s', [
            'label' => 'עיצוב', 'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);
        if ($this->title_key()) {
            $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
                'name' => 'ttypo', 'label' => 'כותרת', 'selector' => '{{WRAPPER}} .dt-title',
            ]);
            $this->add_control('tcolor', [
                'label' => 'צבע הכותרת', 'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .dt-title' => 'color:{{VALUE}}'],
            ]);
            $this->add_responsive_control('tgap', [
                'label' => 'רווח מתחת לכותרת', 'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => ['px' => ['min' => 0, 'max' => 80]],
                'selectors' => ['{{WRAPPER}} .dt-title' => 'margin-block-end:{{SIZE}}{{UNIT}}'],
            ]);
        }
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'itypo', 'label' => 'הפריטים', 'selector' => '{{WRAPPER}} .dt-item',
        ]);
        $this->add_control('icolor', [
            'label' => 'צבע הטקסט', 'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .dt-item' => 'color:{{VALUE}}'],
        ]);
        $this->add_control('accent', [
            'label' => 'צבע הדגשה', 'type' => \Elementor\Controls_Manager::COLOR,
            'description' => 'הסימן, המספר או התווית — לפי סוג הרשימה.',
            'selectors' => [
                '{{WRAPPER}} .dt-list--benefits .dt-item::before' => 'background:{{VALUE}}',
                '{{WRAPPER}} .dt-n' => 'color:{{VALUE}}',
                '{{WRAPPER}} .dt-b' => 'color:{{VALUE}}',
            ],
        ]);
        $this->add_responsive_control('gap', [
            'label' => 'רווח בין פריטים', 'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 60]],
            'selectors' => ['{{WRAPPER}} .dt-item + .dt-item' => 'margin-block-start:{{SIZE}}{{UNIT}}'],
        ]);
        $this->add_responsive_control('align', [
            'label' => 'יישור', 'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'right'  => ['title'=>'ימין',  'icon'=>'eicon-text-align-right'],
                'center' => ['title'=>'מרכז',  'icon'=>'eicon-text-align-center'],
                'left'   => ['title'=>'שמאל',  'icon'=>'eicon-text-align-left'],
            ],
            'selectors' => ['{{WRAPPER}} .dt-wrap' => 'text-align:{{VALUE}}'],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $id = $this->pid();
        if (!$id) return;
        $s    = $this->get_settings_for_display();
        $body = dt_render_field($id, $this->key);

        $title = '';
        $tk = $this->title_key();
        if ($tk && ($s['show_title'] ?? 'yes') === 'yes') {
            $t = dt_get($id, $tk);
            if ($t !== '') {
                $tag   = in_array($s['title_tag'] ?? 'h2', ['h2','h3','h4','div'], true) ? $s['title_tag'] : 'h2';
                $title = '<' . $tag . ' class="dt-title">' . esc_html($t) . '</' . $tag . '>';
            }
        }
        if ($title === '' && $body === '') return;   // אין תוכן — אין markup ריק
        echo '<div class="dt-wrap">' . $title . $body . '</div>';
    }
}
