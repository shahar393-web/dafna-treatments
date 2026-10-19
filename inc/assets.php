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
.dfm{position:relative;overflow:hidden;width:100%;direction:ltr}
.dfm.is-fade{-webkit-mask-image:linear-gradient(90deg,transparent,#000 7%,#000 93%,transparent);mask-image:linear-gradient(90deg,transparent,#000 7%,#000 93%,transparent)}
.dfm-track{display:flex;width:max-content;direction:ltr;animation:dfm-run var(--dfm-dur,40s) linear infinite}
.dfm[data-dir="right"] .dfm-track{animation-direction:reverse}
@media (hover:hover){.dfm.is-pause:hover .dfm-track{animation-play-state:paused}}
.dfm-set{display:flex;align-items:center;gap:var(--dfm-gap,32px);padding-inline-end:var(--dfm-gap,32px)}
.dfm-set>*{direction:rtl;flex:0 0 auto}
@keyframes dfm-run{to{transform:translateX(var(--dfm-to,-50%))}}
@media (prefers-reduced-motion:reduce){.dfm-track{animation:none}}
/* מותגים */
.dtb-logo{display:flex;align-items:center;justify-content:center;transition:opacity .3s;width:var(--dtb-w,auto);height:var(--dtb-h,40px)}
.dtb-logo img{display:block;height:100%;width:100%;max-width:none;object-fit:contain}
/* תעודות */
.dtc-item{display:flex;align-items:center;gap:var(--dfm-gap,32px);white-space:nowrap}
.dtc-sep{display:inline-flex;line-height:1}
.dtc-sep svg{width:1em;height:1em;fill:currentColor}
/* לפני ואחרי */
.dtba-card{margin:0;display:flex;flex-direction:column;width:var(--dtba-w,560px)}
.dtba-pair{display:flex;gap:var(--dtba-gap,8px)}
.dtba-img{position:relative;flex:1 1 0;display:block;min-width:0}
.dtba-frame{display:block;position:relative;aspect-ratio:var(--dtba-ratio,3/4);overflow:hidden;container-type:size}
.dtba-frame{background:#2E2A24}
.dtba-frame img.dtba-main{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;max-width:none;z-index:1}
.dtba-bg{position:absolute;inset:-10%;width:120%;height:120%;max-width:none;object-fit:cover;filter:blur(18px);opacity:.9}
.dtba-frame.is-cw img.dtba-main,.dtba-frame.is-ccw img.dtba-main{inset:auto;top:50%;left:50%;width:100cqh;height:100cqw}
.dtba-frame.is-cw img.dtba-main{transform:translate(-50%,-50%) rotate(90deg)}
.dtba-frame.is-ccw img.dtba-main{transform:translate(-50%,-50%) rotate(-90deg)}
.dtba-lbl{position:absolute;bottom:10px;inset-inline-start:10px;line-height:1.4}
.dtba-cap{display:block}
.dtba-card.is-lb{cursor:zoom-in}
.dtba-card.is-lb:focus-visible{outline:1px solid currentColor;outline-offset:4px}
/* לייטבוקס הזוג: רקע חום, מסגרת שמנת, שתי התמונות זו לצד זו (מובייל: זו מעל זו) */
.dtlb{position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;padding:24px;background:var(--dtba-lb-bg,rgba(46,42,36,.92));opacity:0;transition:opacity .25s;direction:rtl}
.dtlb.is-on{opacity:1}
.dtlb-box{position:relative;background:var(--dtba-lb-fr,#F2F1EC);color:var(--dtba-lb-tx,#2E2A24);border-radius:var(--dtba-lb-r,18px);padding:16px 16px 14px;max-width:min(1100px,100%);max-height:100%;display:flex;flex-direction:column;gap:12px;box-shadow:0 30px 80px -20px rgba(0,0,0,.45)}
.dtlb-pair{display:flex;gap:10px;min-height:0}
.dtlb-fig{position:relative;flex:1 1 0;margin:0;min-width:0;display:flex}
.dtlb-fig img,.dtlb-fig canvas{display:block;width:100%;height:auto;max-height:calc(100vh - 180px);object-fit:contain;border-radius:calc(var(--dtba-lb-r,18px) - 6px);background:rgba(46,42,36,.06)}
.dtlb-tag{position:absolute;top:10px;inset-inline-start:10px;font:600 12px/1 Assistant,sans-serif;letter-spacing:.06em;padding:6px 12px;border-radius:100px;background:rgba(242,241,236,.92);color:#2E2A24}
.dtlb-cap{text-align:center;font:400 16px/1.5 Assistant,sans-serif}
.dtlb-btn{position:absolute;display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:50%;border:1px solid rgba(242,241,236,.5);background:transparent;color:#F2F1EC;cursor:pointer;padding:0;transition:background .3s,color .3s,border-color .3s}
.dtlb-btn svg{width:20px;height:20px;fill:none;stroke:currentColor;stroke-width:1.3;stroke-linecap:round;stroke-linejoin:round}
.dtlb .dtlb-btn,.dtlb .dtlb-btn:focus{background:transparent;color:#F2F1EC;border-color:rgba(242,241,236,.5)}
.dtlb .dtlb-btn:hover{background:#F2F1EC;color:#2E2A24;border-color:#F2F1EC}
.dtlb-btn:focus{outline:none}.dtlb-btn:focus-visible{outline:2px solid #F2F1EC;outline-offset:2px}
.dtlb-x{top:-56px;inset-inline-start:0}
.dtlb-prev,.dtlb-next{top:50%;transform:translateY(-50%)}
.dtlb-prev{inset-inline-start:-60px}.dtlb-next{inset-inline-end:-60px}
@media (max-width:880px){.dtlb{padding:12px}.dtlb-box{padding:10px 10px 6px;gap:6px;width:100%}.dtlb-pair{flex-direction:column;gap:6px}.dtlb-fig img,.dtlb-fig canvas{max-height:calc((100vh - 130px)/2);max-height:calc((100svh - 130px)/2)}.dtlb-cap{min-height:44px;display:flex;align-items:center;justify-content:center;padding:0 52px;font-size:15px}.dtlb .dtlb-x,.dtlb .dtlb-x:focus{top:18px;left:18px;right:auto;z-index:2;background:rgba(46,42,36,.55);width:36px;height:36px}.dtlb .dtlb-prev,.dtlb .dtlb-next,.dtlb .dtlb-prev:focus,.dtlb .dtlb-next:focus{top:auto;bottom:6px;transform:none;color:#2E2A24;border-color:rgba(46,42,36,.35);background:transparent}.dtlb .dtlb-prev{inset-inline-start:10px}.dtlb .dtlb-next{inset-inline-end:10px}}
/* טאבים של טיפולים */
.dtt-tabs{display:flex;flex-wrap:wrap}
.dtt-tab{background:none;border:0;border-bottom:1px solid transparent;padding:0;cursor:pointer;font:inherit;color:inherit;border-radius:0;-webkit-tap-highlight-color:transparent}
/* ערכת Hello צובעת כל button בוורוד (#c36) במעבר/מיקוד — הטאב נשאר שקוף בכל מצב */
.dtt .dtt-tab:hover,.dtt .dtt-tab:focus,.dtt .dtt-tab:active,.dtt .dtt-tab.is-active{background:none;box-shadow:none;outline:0}
.dtt .dtt-tab:focus-visible{outline:1px solid currentColor;outline-offset:4px}
.dtt-panel[hidden]{display:none}
.dtt-row{display:flex;align-items:center;justify-content:space-between;text-decoration:none;color:inherit}
.dtt-txt{display:flex;flex-direction:column;min-width:0;flex:1}
.dtt-ic{flex:0 0 auto;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;border-style:solid;transition:background-color .3s,color .3s}
.dtt-ic svg{width:var(--dtt-ics,16px);height:var(--dtt-ics,16px);stroke:currentColor;fill:none}
/* כרטיסי טיפולים: תמונה למעלה, לוח טקסט מזכוכית מתחת, צל שמבליט את הכרטיס */
.dtk{display:grid;grid-template-columns:repeat(var(--dtk-cols,3),minmax(0,1fr))}
.dtk-card{position:relative;overflow:hidden;display:flex;flex-direction:column;text-decoration:none;color:inherit;min-width:0;background:#fff;transition:transform .4s ease,box-shadow .4s ease}
.dtk.is-lift .dtk-card:hover{transform:translateY(-4px)}
.dtk-img{display:block;position:relative;width:100%;aspect-ratio:3/2;overflow:hidden}
.dtk-img img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;max-width:none;transition:transform .6s ease}
.dtk.is-zoom .dtk-card:hover .dtk-img img{transform:scale(1.04)}
.dtk-panel{position:relative;z-index:1;display:flex;flex-direction:column;gap:8px}
.dtk-eb{letter-spacing:.1em;font-size:11px}
.dtk-title{font-size:20px;font-weight:600;line-height:1.3;display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden}
.dtk-desc{font-size:14px;line-height:1.6;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden}
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
    var f=img.closest('.dtba-frame[data-rot="auto"]'); if(!f||!img.classList.contains('dtba-main')) return;
    var go=function(){ if(img.naturalWidth>img.naturalHeight) f.classList.add('is-cw'); };
    if(img.complete&&img.naturalWidth) go(); else img.addEventListener('load',go,{once:true});
  }
  function init(root){ (root||document).querySelectorAll('.dtba-frame[data-rot="auto"] img').forEach(rot); }
  if(document.readyState!=='loading') init(); else document.addEventListener('DOMContentLoaded',function(){init();});
  /* לייטבוקס הזוג: לחיצה על כרטיס פותחת את לפני+אחרי בגדול, חצים / מקלדת / החלקה עוברים בין הזוגות */
  var lb=null, list=[], idx=0, tx=0;
  function pairs(card){
    var w=card.closest('.elementor-widget-dt_before_after')||document, seen={}, out=[];
    w.querySelectorAll('.dtba-card.is-lb').forEach(function(c){
      var im=c.querySelectorAll('.dtba-img[data-full]'); if(im.length<2) return;
      var k=im[0].getAttribute('data-full'); if(seen[k]) return; seen[k]=1;
      var r=function(i){var f=im[i].querySelector('.dtba-frame');return f&&f.classList.contains('is-cw')?90:(f&&f.classList.contains('is-ccw')?-90:0);};
      out.push({b:k,a:im[1].getAttribute('data-full'),rb:r(0),ra:r(1),t:c.getAttribute('data-t')||'',lb:c.getAttribute('data-lb-b')||'',la:c.getAttribute('data-lb-a')||''});
    });
    return out;
  }
  function fig(src,tag,deg){ return '<figure class="dtlb-fig"><img src="'+src+'" alt="'+tag+'"'+(deg?' data-deg="'+deg+'"':'')+'>'+(tag?'<span class="dtlb-tag">'+tag+'</span>':'')+'</figure>'; }
  /* תמונה שמסובבת בכרטיס — מסובבים גם בחלון (ציור על canvas, כך שהגובה/רוחב מתחלפים נכון) */
  function turn(img){
    var deg=+img.getAttribute('data-deg'); if(!deg) return;
    var go=function(){ var c=document.createElement('canvas'); c.width=img.naturalHeight; c.height=img.naturalWidth;
      var x=c.getContext('2d'); x.translate(c.width/2,c.height/2); x.rotate(deg*Math.PI/180); x.drawImage(img,-img.naturalWidth/2,-img.naturalHeight/2);
      c.setAttribute('role','img'); c.setAttribute('aria-label',img.alt); img.replaceWith(c); };
    img.complete&&img.naturalWidth?go():img.addEventListener('load',go,{once:true});
  }
  function draw(){
    var p=list[idx]; if(!p) return;
    var pr=lb.querySelector('.dtlb-pair'); pr.innerHTML=fig(p.b,p.lb,p.rb)+fig(p.a,p.la,p.ra); pr.querySelectorAll('img[data-deg]').forEach(turn);
    lb.querySelector('.dtlb-cap').textContent=p.t;
    lb.querySelectorAll('.dtlb-prev,.dtlb-next').forEach(function(b){b.hidden=list.length<2;});
  }
  function go(d){ idx=(idx+d+list.length)%list.length; draw(); }
  function close(){ if(!lb) return; lb.classList.remove('is-on'); var el=lb; lb=null; document.documentElement.style.overflow=''; setTimeout(function(){el.remove();},250); }
  function open(card){
    list=pairs(card); var k=(card.querySelector('.dtba-img[data-full]')||{}).getAttribute&&card.querySelector('.dtba-img[data-full]').getAttribute('data-full');
    idx=Math.max(0,list.findIndex(function(p){return p.b===k;}));
    var w=card.closest('.elementor-widget-dt_before_after')||card, cs=getComputedStyle(w);
    lb=document.createElement('div'); lb.className='dtlb'; lb.setAttribute('role','dialog'); lb.setAttribute('aria-modal','true');
    ['--dtba-lb-bg','--dtba-lb-fr','--dtba-lb-tx','--dtba-lb-r'].forEach(function(v){var x=cs.getPropertyValue(v); if(x) lb.style.setProperty(v,x.trim());});
    lb.innerHTML='<div class="dtlb-box"><button type="button" class="dtlb-btn dtlb-x" aria-label="סגירה"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg></button>'
      +'<button type="button" class="dtlb-btn dtlb-prev" aria-label="הקודם"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12h16M14 6l6 6-6 6"/></svg></button><button type="button" class="dtlb-btn dtlb-next" aria-label="הבא"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12H4M10 6l-6 6 6 6"/></svg></button>'
      +'<div class="dtlb-pair"></div><div class="dtlb-cap"></div></div>';
    document.body.appendChild(lb); document.documentElement.style.overflow='hidden'; draw();
    requestAnimationFrame(function(){lb.classList.add('is-on');}); lb.querySelector('.dtlb-x').focus();
    lb.addEventListener('click',function(e){
      if(e.target===lb||e.target.closest('.dtlb-x')) close();
      else if(e.target.closest('.dtlb-prev')) go(-1);
      else if(e.target.closest('.dtlb-next')) go(1);
    });
    lb.addEventListener('touchstart',function(e){tx=e.touches[0].clientX;},{passive:true});
    lb.addEventListener('touchend',function(e){var d=e.changedTouches[0].clientX-tx; if(Math.abs(d)>50) go(d>0?1:-1);});
  }
  document.addEventListener('click',function(e){ var c=e.target.closest&&e.target.closest('.dtba-card.is-lb'); if(c&&!lb){ e.preventDefault(); open(c); } });
  document.addEventListener('keydown',function(e){
    if(!lb){ var c=document.activeElement&&document.activeElement.closest&&document.activeElement.closest('.dtba-card.is-lb'); if(c&&(e.key==='Enter'||e.key===' ')){e.preventDefault();open(c);} return; }
    if(e.key==='Escape') close(); else if(e.key==='ArrowLeft') go(1); else if(e.key==='ArrowRight') go(-1);
  });
  function fill(m){
    var tr=m.querySelector('.dfm-track'), sets=tr&&tr.querySelectorAll('.dfm-set'); if(!sets||!sets.length) return;
    var w=sets[0].getBoundingClientRect().width; if(!w) return;
    var need=Math.ceil(m.getBoundingClientRect().width/w)+1;
    for(var i=sets.length;i<need;i++){ var c=sets[sets.length-1].cloneNode(true); c.setAttribute('aria-hidden','true'); c.querySelectorAll('a').forEach(function(a){a.tabIndex=-1;}); tr.appendChild(c); }
    m.style.setProperty('--dfm-to',(-w)+'px');
  }
  function fillAll(){ document.querySelectorAll('.dfm').forEach(fill); }
  window.addEventListener('load',fillAll); window.addEventListener('resize',fillAll);
  if(document.readyState!=='loading') fillAll(); else document.addEventListener('DOMContentLoaded',fillAll);
  /* בעורך של אלמנטור הווידג'ט נטען מחדש בכל שינוי — מאתחלים שוב */
  if(window.jQuery) jQuery(window).on('elementor/frontend/init',function(){
    if(window.elementorFrontend&&elementorFrontend.hooks) elementorFrontend.hooks.addAction('frontend/element_ready/dt_before_after.default',function(s){init(s[0]||s);});
  });
})();
JS);
}
