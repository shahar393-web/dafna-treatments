<?php
/**
 * CSS ו-JS של הווידג'טים החדשים (2.4.0). קטן, ונטען רק בעמודים שבהם יש ווידג'ט שלנו
 * (אלמנטור מבקש אותו דרך get_style_depends / get_script_depends).
 * כל הצבעים, הגדלים והמרווחים מגיעים מהקונטרולים של הווידג'ט — כאן רק המבנה.
 */
if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts', 'dt_register_widget_assets', 5);
add_action('elementor/frontend/after_register_scripts', 'dt_register_widget_assets');
function dt_register_widget_assets() {
    if (wp_style_is('dt-widgets', 'registered')) return;
    wp_register_style('dt-widgets', false, [], DT_VER);
    wp_add_inline_style('dt-widgets', <<<CSS
/* ── פס רץ אינסופי (מותגים · תעודות · לפני ואחרי) ── */
.dfm{position:relative;overflow:hidden;width:100%}
.dfm.is-fade{-webkit-mask-image:linear-gradient(90deg,transparent,#000 7%,#000 93%,transparent);mask-image:linear-gradient(90deg,transparent,#000 7%,#000 93%,transparent)}
.dfm-track{display:flex;width:max-content;direction:ltr;animation:dfm-run var(--dfm-dur,40s) linear infinite}
.dfm[data-dir="right"] .dfm-track{animation-direction:reverse}
.dfm.is-pause:hover .dfm-track{animation-play-state:paused}
.dfm-set{display:flex;align-items:center;gap:var(--dfm-gap,32px);padding-inline-end:var(--dfm-gap,32px)}
.dfm-set>*{direction:rtl;flex:0 0 auto}
@keyframes dfm-run{to{transform:translateX(-50%)}}
@media (prefers-reduced-motion:reduce){.dfm-track{animation:none}}
/* מותגים */
.dtb-logo{display:flex;align-items:center;justify-content:center;transition:opacity .3s}
.dtb-logo img{display:block;height:var(--dtb-h,40px);width:auto;max-width:none;object-fit:contain}
/* תעודות */
.dtc-item{display:flex;align-items:center;gap:var(--dfm-gap,32px);white-space:nowrap}
.dtc-sep{display:inline-flex;line-height:1}
.dtc-sep svg{width:1em;height:1em;fill:currentColor}
/* לפני ואחרי */
.dtba-card{margin:0;display:flex;flex-direction:column;width:var(--dtba-w,560px)}
.dtba-pair{display:flex;gap:var(--dtba-gap,8px)}
.dtba-img{position:relative;flex:1 1 0;display:block;min-width:0}
.dtba-frame{display:block;position:relative;aspect-ratio:var(--dtba-ratio,3/4);overflow:hidden;container-type:size}
.dtba-frame img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;max-width:none}
.dtba-frame.is-cw img,.dtba-frame.is-ccw img{inset:auto;top:50%;left:50%;width:100cqh;height:100cqw}
.dtba-frame.is-cw img{transform:translate(-50%,-50%) rotate(90deg)}
.dtba-frame.is-ccw img{transform:translate(-50%,-50%) rotate(-90deg)}
.dtba-lbl{position:absolute;bottom:10px;inset-inline-start:10px;line-height:1.4}
.dtba-cap{display:block}
/* טאבים של טיפולים */
.dtt-tabs{display:flex;flex-wrap:wrap}
.dtt-tab{background:none;border:0;border-bottom:1px solid transparent;padding:0;cursor:pointer;font:inherit;color:inherit}
.dtt-panel[hidden]{display:none}
.dtt-row{display:flex;align-items:center;justify-content:space-between;text-decoration:none;color:inherit}
.dtt-txt{display:flex;flex-direction:column;min-width:0;flex:1}
.dtt-ic{flex:0 0 auto;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;border-style:solid;transition:background-color .3s,color .3s}
.dtt-ic svg{width:var(--dtt-ics,16px);height:var(--dtt-ics,16px);stroke:currentColor;fill:none}
/* המלצות */
.dtr{display:grid;grid-template-columns:repeat(var(--dtr-cols,2),minmax(0,1fr))}
.dtr-card{margin:0;display:flex;flex-direction:column}
.dtr-q{margin:0;flex:1}
.dtr-stars{letter-spacing:.1em}
/* שאלות */
.dtq-item summary{list-style:none;display:flex;align-items:center;justify-content:space-between;cursor:pointer}
.dtq-item summary::-webkit-details-marker{display:none}
.dtq-ic{flex:0 0 auto;transition:transform .3s;line-height:1}
.dtq-item[open] .dtq-ic{transform:rotate(45deg)}
.dtq-a>*:last-child{margin-bottom:0}
CSS);

    wp_register_script('dt-widgets', false, ['jquery'], DT_VER, true);
    wp_add_inline_script('dt-widgets', <<<JS
(function(){
  /* טאבים */
  document.addEventListener('click',function(e){
    var b=e.target.closest&&e.target.closest('.dtt-tab'); if(!b) return;
    var w=b.closest('.dtt'), i=b.getAttribute('data-i');
    w.querySelectorAll('.dtt-tab').forEach(function(t){var on=t===b;t.classList.toggle('is-active',on);t.setAttribute('aria-selected',on?'true':'false');});
    w.querySelectorAll('.dtt-panel').forEach(function(p){p.hidden=p.getAttribute('data-i')!==i;});
  });
  /* לפני ואחרי: תמונה שצולמה לרוחב מסתובבת לאורך */
  function rot(img){
    var f=img.closest('.dtba-frame[data-rot="auto"]'); if(!f) return;
    var go=function(){ if(img.naturalWidth>img.naturalHeight) f.classList.add('is-cw'); };
    if(img.complete&&img.naturalWidth) go(); else img.addEventListener('load',go,{once:true});
  }
  function init(root){ (root||document).querySelectorAll('.dtba-frame[data-rot="auto"] img').forEach(rot); }
  if(document.readyState!=='loading') init(); else document.addEventListener('DOMContentLoaded',function(){init();});
  /* בעורך של אלמנטור הווידג'ט נטען מחדש בכל שינוי — מאתחלים שוב */
  if(window.jQuery) jQuery(window).on('elementor/frontend/init',function(){
    if(window.elementorFrontend&&elementorFrontend.hooks) elementorFrontend.hooks.addAction('frontend/element_ready/dt_before_after.default',function(s){init(s[0]||s);});
  });
})();
JS);
}
