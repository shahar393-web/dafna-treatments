/**
 * Dafna · ווידג'טים לאלמנטור (קטגוריה "Dafna" בעורך)
 * טאבים של טיפולים · לפני ואחרי · מותגים · פס תעודות · המלצות · שאלות ותשובות · כרטיסי טיפולים
 *
 * עורכים כאן ושומרים — אין צורך להעלות תוסף. הנתונים (טיפולים, פרטי העסק, שאלות) נשארים בתוסף Dafna Treatments.
 * כשהסניפט פעיל התוסף לא טוען את העותק שלו. כיבוי הסניפט = התוסף חוזר לעותק שלו.
 * חלק 1: CSS ו-JS. חלק 2: הווידג'טים.
 */
use Elementor\Controls_Manager as CM;
use Elementor\Group_Control_Typography as GT;
use Elementor\Group_Control_Border as GB;
use Elementor\Repeater;

if (!defined('DT_WIDGETS_SNIPPET')) {
define('DT_WIDGETS_SNIPPET', '1.0');
if (!defined('DT_VER')) define('DT_VER', '2.6.0');

/* ═════ חלק 1 · CSS ו-JS ═════ */
/**
 * CSS ו-JS של הווידג'טים החדשים (2.4.0). קטן, ונטען רק בעמודים שבהם יש ווידג'ט שלנו
 * (אלמנטור מבקש אותו דרך get_style_depends / get_script_depends).
 * כל הצבעים, הגדלים והמרווחים מגיעים מהקונטרולים של הווידג'ט — כאן רק המבנה.
 */

add_action('wp_enqueue_scripts', 'dts_register_widget_assets', 5);
add_action('elementor/frontend/after_register_scripts', 'dts_register_widget_assets');
// נטען בכל עמוד (קטן): גם עמודים בלי ווידג'ט שלנו צריכים את הגלילה לעוגן אחרי טעינה (למשל החנות מהתפריט)
add_action('wp_enqueue_scripts', function () { wp_enqueue_script('dt-widgets'); wp_enqueue_style('dt-widgets'); }, 20);
function dts_register_widget_assets() {
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
/* סינון קטגוריות (חנות) */
.dtf{display:flex;flex-wrap:wrap;justify-content:center;gap:28px}
.dtf-b{background:none!important;border:0;border-bottom:1px solid transparent;border-radius:0;padding:0 0 8px;cursor:pointer;font:inherit;color:#6E675D;white-space:nowrap;transition:color .25s,border-color .25s}
.dtf-b:focus{outline:none}.dtf-b:focus-visible{outline:1px solid currentColor;outline-offset:4px}
@media (max-width:767px){.dtf-scroll-yes .dtf{flex-wrap:nowrap;overflow-x:auto;justify-content:flex-start;scrollbar-width:none;padding-bottom:2px}.dtf-scroll-yes .dtf::-webkit-scrollbar{display:none}}
/* זכוכית מאירה: כתמי אור מאחור, כרטיס שקוף-למחצה עם טשטוש, ופס אור שעובר מדי פעם */
.dtk-glass-yes .dtk{position:relative;isolation:isolate}
.dtk-glass-yes .dtk::before{content:"";position:absolute;inset:-12% -6%;z-index:-1;pointer-events:none;filter:blur(36px);
  background:radial-gradient(38% 52% at 18% 35%,var(--dtk-glow,rgba(184,169,146,.55)),transparent 70%),radial-gradient(34% 48% at 82% 65%,var(--dtk-glow,rgba(184,169,146,.55)),transparent 70%),radial-gradient(30% 42% at 52% 15%,rgba(255,255,255,.9),transparent 70%);
  animation:dtk-float 16s ease-in-out infinite alternate}
@keyframes dtk-float{to{transform:translate3d(3%,-4%,0) scale(1.06)}}
.dtk-glass-yes .dtk-card{background-color:rgba(255,255,255,.42)!important;-webkit-backdrop-filter:blur(16px) saturate(1.3);backdrop-filter:blur(16px) saturate(1.3);border:1px solid rgba(255,255,255,.75);box-shadow:0 24px 50px -28px rgba(46,42,36,.4),inset 0 1px 0 rgba(255,255,255,.95)}
.dtk-glass-yes .dtk-card::after{content:"";position:absolute;top:-40%;bottom:-40%;left:-70%;width:45%;z-index:2;pointer-events:none;transform:skewX(-18deg);background:linear-gradient(90deg,transparent,rgba(255,255,255,.6),transparent);animation:dtk-shine 7s ease-in-out infinite}
.dtk-glass-yes .dtk-card:nth-child(2)::after{animation-delay:1.2s}.dtk-glass-yes .dtk-card:nth-child(3)::after{animation-delay:2.4s}.dtk-glass-yes .dtk-card:nth-child(4)::after{animation-delay:3.6s}
@keyframes dtk-shine{0%,72%{left:-70%}100%{left:135%}}
@media (prefers-reduced-motion:reduce){.dtk-glass-yes .dtk::before,.dtk-glass-yes .dtk-card::after{animation:none}}
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
  /* קישור לעוגן מעמוד אחר (למשל קטגוריה בחנות מהתפריט): אחרי שהעמוד נטען במלואו גוללים שוב לעוגן, כי התוכן שמעליו גדל בזמן הטעינה */
  window.addEventListener('load',function(){
    if(!location.hash||location.hash.length<2) return;
    var t=null; try{ t=document.getElementById(decodeURIComponent(location.hash.slice(1))); }catch(e){}
    if(t) setTimeout(function(){ t.scrollIntoView({block:'start'}); },60);
  });
  /* סינון קטגוריות: מסתיר/מציג פריטים ברשת לפי המחלקה product_cat-<slug> */
  document.addEventListener('click',function(e){
    var b=e.target.closest&&e.target.closest('.dtf-b'); if(!b) return;
    var bar=b.closest('.dtf'), id=bar.getAttribute('data-target'), w=bar.closest('.elementor-widget');
    var grid=id?document.querySelector('.elementor-element-'+id):(w&&w.parentElement.querySelector('.elementor-widget-loop-grid'));
    if(!grid) return;
    bar.querySelectorAll('.dtf-b').forEach(function(x){var on=x===b;x.classList.toggle('is-on',on);x.setAttribute('aria-pressed',on?'true':'false');});
    var cat=b.getAttribute('data-cat'), tid=b.getAttribute('data-id');
    /* וורדפרס מסמן קטגוריה בעברית לפי המספר שלה (product_cat-46), ובאנגלית לפי השם */
    grid.querySelectorAll('.e-loop-item').forEach(function(it){ var ok=!cat||it.classList.contains('product_cat-'+cat)||(tid&&it.classList.contains('product_cat-'+tid)); it.style.display=ok?'':'none'; });
  });
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

/* ═════ חלק 2 · הווידג'טים ═════ */
add_action('elementor/widgets/register', function ($wm) {
    if (!class_exists('\Elementor\Widget_Base')) return;
    if (!class_exists('DTS_W_Base')) {
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


abstract class DTS_W_Base extends \Elementor\Widget_Base {
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
class DTS_Treatment_Tabs_Widget extends DTS_W_Base {
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
        $this->add_responsive_control('tab_pb', ['label' => 'רווח טקסט ↔ קו', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 12], 'selectors' => ['{{WRAPPER}} .dtt-tab' => 'padding-bottom:{{SIZE}}{{UNIT}}']]);
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
        $this->add_responsive_control('desc_lines', ['label' => 'שורות תיאור (0 = הכול)', 'type' => CM::NUMBER, 'min' => 0, 'max' => 6,
            'selectors' => ['{{WRAPPER}} .dtt-desc' => 'display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;-webkit-line-clamp:{{VALUE}}']]);
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
class DTS_Before_After_Widget extends DTS_W_Base {
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
        $this->add_control('lightbox', ['label' => 'לחיצה פותחת את הזוג בגדול (Lightbox)', 'type' => CM::SWITCHER, 'default' => 'yes']);
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
        $this->add_control('fit', ['label' => 'התאמת התמונה', 'type' => CM::SELECT, 'default' => 'blur',
            'options' => ['blur' => 'כל התמונה + רקע מטושטש', 'cover' => 'ממלאת את המסגרת (נחתכת)'], 'selectors_dictionary' => ['blur' => 'contain', 'cover' => 'cover'],
            'selectors' => ['{{WRAPPER}} .dtba-main' => 'object-fit:{{VALUE}}']]);
        $this->add_responsive_control('frame_h', ['label' => 'גובה התמונות (ריק = לפי היחס)', 'type' => CM::SLIDER, 'size_units' => ['px'], 'range' => ['px' => ['min' => 120, 'max' => 700]],
            'selectors' => ['{{WRAPPER}} .dtba-frame' => 'aspect-ratio:auto;height:{{SIZE}}{{UNIT}}']]);
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

        // לייטבוקס הזוג — הערכים נקראים מהווידג'ט ונכנסים לחלון (משתני CSS)
        $this->start_controls_section('s_lb', ['label' => 'לייטבוקס', 'tab' => CM::TAB_STYLE, 'condition' => ['lightbox' => 'yes']]);
        $this->add_control('lb_bg', ['label' => 'רקע מאחור', 'type' => CM::COLOR, 'default' => 'rgba(46,42,36,.92)', 'selectors' => ['{{WRAPPER}}' => '--dtba-lb-bg:{{VALUE}}']]);
        $this->add_control('lb_fr', ['label' => 'מסגרת', 'type' => CM::COLOR, 'default' => '#F2F1EC', 'selectors' => ['{{WRAPPER}}' => '--dtba-lb-fr:{{VALUE}}']]);
        $this->add_control('lb_tx', ['label' => 'צבע הטקסט', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}}' => '--dtba-lb-tx:{{VALUE}}']]);
        $this->add_control('lb_r', ['label' => 'פינות', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 18], 'selectors' => ['{{WRAPPER}}' => '--dtba-lb-r:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();
    }

    protected function img($m, $rot, $label, $title, $s, $group) {
        $id = (int) ($m['id'] ?? 0); $url = $m['url'] ?? '';
        if (!$id && !$url) return '';
        $full = $id ? wp_get_attachment_image_url($id, 'full') : $url;
        $img  = $id ? wp_get_attachment_image($id, 'large', false, ['alt' => esc_attr($title . ' · ' . $label), 'loading' => 'lazy', 'class' => 'dtba-main']) : '<img class="dtba-main" src="' . esc_url($url) . '" alt="' . esc_attr($title) . '" loading="lazy">';
        // כל התמונה נראית (contain) בתוך מסגרת קבועה; מאחוריה אותה תמונה מטושטשת ממלאת את השוליים
        $bg   = ($s['fit'] ?? 'blur') === 'blur' ? '<img class="dtba-bg" src="' . esc_url($id ? wp_get_attachment_image_url($id, 'medium') : $url) . '" alt="" aria-hidden="true" loading="lazy">' : '';
        $cls  = $rot === 'cw' ? ' is-cw' : ($rot === 'ccw' ? ' is-ccw' : '');
        // לייטבוקס זוגי (2.4.8): כל הכרטיס נפתח — כאן רק שומרים את כתובת התמונה המלאה
        $att  = ($s['lightbox'] ?? '') === 'yes' ? ' data-full="' . esc_url($full) . '"' : '';
        $lbl  = ($s['labels'] ?? '') === 'yes' ? '<span class="dtba-lbl">' . esc_html($label) . '</span>' : '';
        return '<span class="dtba-img"' . $att . '><span class="dtba-frame' . $cls . '" data-rot="' . esc_attr($rot) . '">' . $bg . $img . '</span>' . $lbl . '</span>';
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $group = 'ba-' . $this->get_id();
        $html = '';
        foreach ((array) ($s['items'] ?? []) as $it) {
            $t = $it['title'] ?? '';
            $lb = ($s['lightbox'] ?? '') === 'yes';
            $html .= '<figure class="dtba-card' . ($lb ? ' is-lb' : '') . '"' . ($lb ? ' role="button" tabindex="0" aria-label="' . esc_attr(($t ?: 'לפני ואחרי') . ' · הגדלה') . '"'
                   . ' data-t="' . esc_attr($t) . '" data-lb-b="' . esc_attr($s['lbl_b'] ?? 'לפני') . '" data-lb-a="' . esc_attr($s['lbl_a'] ?? 'אחרי') . '"' : '') . '><div class="dtba-pair">'
                   . $this->img($it['before'] ?? [], $it['rot_b'] ?? 'auto', $s['lbl_b'] ?? 'לפני', $t, $s, $group)
                   . $this->img($it['after'] ?? [], $it['rot_a'] ?? 'auto', $s['lbl_a'] ?? 'אחרי', $t, $s, $group)
                   . '</div>' . ($t !== '' ? '<figcaption class="dtba-cap">' . esc_html($t) . '</figcaption>' : '') . '</figure>';
        }
        if (($s['loop'] ?? '') === 'yes') { $this->marquee($html, $s, 'dtba'); return; }
        echo '<div class="dtba dfm-set" style="flex-wrap:wrap;justify-content:center;padding:0">' . $html . '</div>';
    }
}

/* ═══════════════════════════ 3 · מותגים ═══════════════════════════ */
class DTS_Brands_Widget extends DTS_W_Base {
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
        $this->add_responsive_control('logo_w', ['label' => 'רוחב קופסת לוגו (כל הלוגואים באותו גודל)', 'type' => CM::SLIDER, 'size_units' => ['px'], 'range' => ['px' => ['min' => 40, 'max' => 300]],
            'default' => ['unit' => 'px', 'size' => 140], 'mobile_default' => ['unit' => 'px', 'size' => 100], 'selectors' => ['{{WRAPPER}} .dtb-logo' => '--dtb-w:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();

        $this->start_controls_section('s', ['label' => 'עיצוב', 'tab' => CM::TAB_STYLE]);
        $this->add_control('white', ['label' => 'לוגואים לבנים (על רקע כהה)', 'type' => CM::SWITCHER, 'default' => '',
            'selectors' => ['{{WRAPPER}} .dtb-logo img' => 'filter:brightness(0) invert(1)']]);
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
            $img = $id ? wp_get_attachment_image($id, 'full', false, ['alt' => esc_attr($it['name'] ?? ''), 'loading' => 'eager']) : '<img src="' . esc_url($src) . '" alt="' . esc_attr($it['name'] ?? '') . '">';
            $url = $it['link']['url'] ?? '';
            $html .= $url ? '<a class="dtb-logo" href="' . esc_url($url) . '"' . (!empty($it['link']['is_external']) ? ' target="_blank" rel="noopener"' : '') . ' aria-label="' . esc_attr($it['name'] ?? '') . '">' . $img . '</a>'
                          : '<span class="dtb-logo">' . $img . '</span>';
        }
        $this->marquee($html, $s, 'dtb');
    }
}

/* ═══════════════════════════ 4 · פס תעודות ═══════════════════════════ */
class DTS_Certs_Widget extends DTS_W_Base {
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
function dts_google_reviews() {
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

class DTS_Reviews_Widget extends DTS_W_Base {
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
            foreach (dts_google_reviews() as $r) if ($r['stars'] >= (int) ($s['min_stars'] ?: 4)) $items[] = $r;
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
class DTS_Faq_Widget extends DTS_W_Base {
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
class DTS_Treatment_Cards_Widget extends DTS_W_Base {
    public function get_name()  { return 'dt_treatment_cards'; }
    public function get_title() { return 'Dafna · כרטיסי טיפולים'; }
    public function get_icon()  { return 'eicon-posts-grid'; }

    protected function register_controls() {
        $opts = [];
        foreach (get_posts(['post_type' => DT_CPT, 'numberposts' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC']) as $p) $opts[$p->ID] = get_the_title($p);

        $this->start_controls_section('c', ['label' => 'תוכן']);
        $this->add_control('note', ['type' => CM::RAW_HTML, 'content_classes' => 'elementor-descriptor',
            'raw' => 'בוחרים טיפולים מהרשימה — הסדר כאן הוא הסדר באתר (הראשון מימין). העינית, השם והתיאור נמשכים מהטיפול עצמו.']);
        $this->add_control('related', ['label' => 'טיפולים נוספים (אוטומטי)', 'type' => CM::SWITCHER, 'default' => '',
            'description' => 'לעמוד טיפול: מציג טיפולים אחרים לפי הסדר, בלי הטיפול הנוכחי. הרשימה למטה לא בשימוש.']);
        $this->add_control('count', ['label' => 'כמה טיפולים', 'type' => CM::NUMBER, 'min' => 1, 'max' => 12, 'default' => 4, 'condition' => ['related' => 'yes']]);
        $this->add_control('ids', ['label' => 'טיפולים', 'type' => CM::SELECT2, 'multiple' => true, 'label_block' => true, 'options' => $opts, 'condition' => ['related!' => 'yes']]);
        $this->add_control('words', ['label' => 'אורך התיאור (מילים)', 'type' => CM::NUMBER, 'min' => 0, 'max' => 60, 'default' => 12]);
        $this->add_responsive_control('cols', ['label' => 'עמודות', 'type' => CM::NUMBER, 'min' => 1, 'max' => 4, 'default' => 3, 'tablet_default' => 3, 'mobile_default' => 1,
            'selectors' => ['{{WRAPPER}} .dtk' => '--dtk-cols:{{VALUE}}']]);
        $this->add_responsive_control('gap', ['label' => 'רווח בין כרטיסים', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 42], 'tablet_default' => ['unit' => 'px', 'size' => 16], 'mobile_default' => ['unit' => 'px', 'size' => 16],
            'selectors' => ['{{WRAPPER}} .dtk' => 'gap:{{SIZE}}{{UNIT}}']]);
        $this->add_responsive_control('flow', ['label' => 'פריסה', 'type' => CM::SELECT, 'default' => 'grid',
            'options' => ['grid' => 'רשת', 'scroll' => 'גלילה הצידה'],
            'selectors_dictionary' => [
                'grid'   => 'grid-auto-flow:row;grid-template-columns:repeat(var(--dtk-cols,3),minmax(0,1fr));overflow-x:visible',
                'scroll' => 'grid-auto-flow:column;grid-template-columns:none;grid-auto-columns:var(--dtk-sw,300px);overflow-x:auto;scrollbar-width:none;scroll-snap-type:x mandatory'],
            'selectors' => ['{{WRAPPER}} .dtk' => '{{VALUE}}']]);
        $this->add_responsive_control('scroll_w', ['label' => 'רוחב כרטיס בגלילה', 'type' => CM::SLIDER, 'size_units' => ['px'], 'range' => ['px' => ['min' => 160, 'max' => 600]],
            'default' => ['unit' => 'px', 'size' => 300], 'selectors' => ['{{WRAPPER}} .dtk' => '--dtk-sw:{{SIZE}}{{UNIT}}']]);
        $this->add_control('ratio', ['label' => 'צורת התמונה', 'type' => CM::SELECT, 'default' => '3/2',
            'options' => ['3/2' => 'רוחב (3:2)', '4/3' => '4:3', '1/1' => 'ריבוע', '4/5' => 'לאורך (4:5)'], 'selectors' => ['{{WRAPPER}} .dtk-img' => 'aspect-ratio:{{VALUE}}']]);
        $this->end_controls_section();

        $this->start_controls_section('s_card', ['label' => 'כרטיס', 'tab' => CM::TAB_STYLE]);
        $this->add_control('cbg', ['label' => 'רקע הכרטיס', 'type' => CM::COLOR, 'default' => '#FFFFFF', 'selectors' => ['{{WRAPPER}} .dtk-card' => 'background-color:{{VALUE}}']]);
        $this->add_control('glass', ['label' => 'אפקט זכוכית מאירה', 'type' => CM::SWITCHER, 'default' => '', 'prefix_class' => 'dtk-glass-',
            'description' => 'כרטיס שקוף-למחצה עם טשטוש, כתמי אור רכים מאחור ופס אור שעובר על הכרטיס']);
        $this->add_control('glow', ['label' => 'צבע כתמי האור', 'type' => CM::COLOR, 'default' => 'rgba(184,169,146,0.55)', 'condition' => ['glass' => 'yes'],
            'selectors' => ['{{WRAPPER}}' => '--dtk-glow:{{VALUE}}']]);
        $this->add_responsive_control('r', ['label' => 'פינות', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 12], 'selectors' => ['{{WRAPPER}} .dtk-card' => 'border-radius:{{SIZE}}{{UNIT}}']]);
        $this->add_responsive_control('cp', ['label' => 'ריפוד הכרטיס', 'type' => CM::SLIDER, 'range' => ['px' => ['min' => 0, 'max' => 40]], 'selectors' => ['{{WRAPPER}} .dtk-card' => 'padding:{{SIZE}}{{UNIT}}']]);
        $this->add_responsive_control('ir', ['label' => 'פינות התמונה', 'type' => CM::SLIDER, 'range' => ['px' => ['min' => 0, 'max' => 40]], 'selectors' => ['{{WRAPPER}} .dtk-img' => 'border-radius:{{SIZE}}{{UNIT}}']]);
        $this->add_responsive_control('ih', ['label' => 'גובה התמונה (ריק = לפי הצורה)', 'type' => CM::SLIDER, 'range' => ['px' => ['min' => 80, 'max' => 600]], 'selectors' => ['{{WRAPPER}} .dtk-img' => 'aspect-ratio:auto;height:{{SIZE}}{{UNIT}}']]);
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
        if (($s['related'] ?? '') === 'yes') {
            $ids = get_posts(['post_type' => DT_CPT, 'numberposts' => max(1, (int) ($s['count'] ?? 4)), 'exclude' => [get_the_ID()],
                              'orderby' => 'menu_order title', 'order' => 'ASC', 'fields' => 'ids']);
        }
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

/* ═══════════════════════════ 8 · סינון קטגוריות של מותג (חנות) ═══════════════════════════
   כפתור לכל תת-קטגוריה של המותג שנבחר; לחיצה מסננת את רשת המוצרים (Loop Grid) במקום, בלי טעינה. */
class DTS_Cat_Filter_Widget extends DTS_W_Base {
    public function get_name()  { return 'dt_cat_filter'; }
    public function get_title() { return 'Dafna · סינון קטגוריות'; }
    public function get_icon()  { return 'eicon-filter'; }

    protected function register_controls() {
        $terms = get_terms(['taxonomy' => 'product_cat', 'parent' => 0, 'hide_empty' => false]);
        $opts = [];
        if (!is_wp_error($terms)) foreach ($terms as $t) $opts[$t->term_id] = $t->name;
        $this->start_controls_section('c', ['label' => 'סינון']);
        $this->add_control('parent', ['label' => 'המותג (הקטגוריה הראשית)', 'type' => CM::SELECT, 'options' => $opts, 'default' => (string) array_key_first($opts)]);
        $this->add_control('target', ['label' => 'מזהה רשת המוצרים', 'type' => CM::TEXT, 'description' => 'ה-ID של רשת המוצרים. ריק = הרשת שבאותו קונטיינר.']);
        $this->add_control('all', ['label' => 'כפתור "הכל"', 'type' => CM::TEXT, 'default' => 'הכל']);
        $this->add_control('empty', ['label' => 'להציג קטגוריות ריקות', 'type' => CM::SWITCHER, 'default' => '']);
        $this->add_responsive_control('align', ['label' => 'יישור', 'type' => CM::CHOOSE, 'default' => 'center',
            'options' => ['flex-start' => ['title' => 'התחלה', 'icon' => 'eicon-h-align-right'], 'center' => ['title' => 'מרכז', 'icon' => 'eicon-h-align-center'], 'flex-end' => ['title' => 'סוף', 'icon' => 'eicon-h-align-left']],
            'selectors' => ['{{WRAPPER}} .dtf' => 'justify-content:{{VALUE}}']]);
        $this->add_control('scroll', ['label' => 'בטלפון: שורה אחת עם גלילה הצידה', 'type' => CM::SWITCHER, 'default' => 'yes', 'prefix_class' => 'dtf-scroll-']);
        $this->end_controls_section();

        $this->start_controls_section('s', ['label' => 'עיצוב', 'tab' => CM::TAB_STYLE]);
        $this->add_group_control(GT::get_type(), ['name' => 't', 'selector' => '{{WRAPPER}} .dtf-b']);
        $this->add_responsive_control('gap', ['label' => 'רווח בין כפתורים', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 28], 'selectors' => ['{{WRAPPER}} .dtf' => 'gap:{{SIZE}}{{UNIT}}']]);
        $this->add_control('c', ['label' => 'צבע', 'type' => CM::COLOR, 'default' => '#6E675D', 'selectors' => ['{{WRAPPER}} .dtf-b' => 'color:{{VALUE}}']]);
        $this->add_control('ca', ['label' => 'צבע פעיל / מעבר עכבר', 'type' => CM::COLOR, 'default' => '#2E2A24', 'selectors' => ['{{WRAPPER}} .dtf-b:hover,{{WRAPPER}} .dtf-b.is-on' => 'color:{{VALUE}}', '{{WRAPPER}} .dtf-b.is-on' => 'border-bottom-color:{{VALUE}}']]);
        $this->add_control('pb', ['label' => 'רווח לקו התחתון', 'type' => CM::SLIDER, 'default' => ['unit' => 'px', 'size' => 8], 'selectors' => ['{{WRAPPER}} .dtf-b' => 'padding-bottom:{{SIZE}}{{UNIT}}']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $kids = get_terms(['taxonomy' => 'product_cat', 'parent' => (int) ($s['parent'] ?? 0), 'hide_empty' => ($s['empty'] ?? '') !== 'yes', 'orderby' => 'name']);
        if (is_wp_error($kids) || !$kids) return;
        echo '<div class="dtf" role="toolbar" data-target="' . esc_attr($s['target'] ?? '') . '">';
        echo '<button type="button" class="dtf-b is-on" data-cat="" aria-pressed="true">' . esc_html(($s['all'] ?? '') ?: 'הכל') . '</button>';
        foreach ($kids as $k) echo '<button type="button" class="dtf-b" data-cat="' . esc_attr($k->slug) . '" data-id="' . (int) $k->term_id . '" aria-pressed="false">' . esc_html($k->name) . '</button>';
        echo '</div>';
    }
}

function dts_register_home_widgets($wm) {
    foreach (['DTS_Treatment_Tabs_Widget', 'DTS_Before_After_Widget', 'DTS_Brands_Widget', 'DTS_Certs_Widget', 'DTS_Reviews_Widget', 'DTS_Faq_Widget', 'DTS_Treatment_Cards_Widget', 'DTS_Cat_Filter_Widget'] as $c) $wm->register(new $c());
}

    }
    dts_register_home_widgets($wm);
}, 25);
}
