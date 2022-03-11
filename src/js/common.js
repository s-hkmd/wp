/**
 * Description: JavaScript for Global
 */

/*-------------------------------------------
  * Import Modules
-------------------------------------------*/
// import { fixAspectRatio } from './modules/aspect-ratio.module';

/*-------------------------------------------
  * Common
-------------------------------------------*/
/**
 * 外部リンク処理
 */
(() => {
  const ex_links = document.querySelectorAll('a[target="_blank"]');
  for (const ex_link of ex_links) {
    ex_link.setAttribute('rel', 'nofollow noreferrer');
  }
})();

/**
 * ビューポート制御
 */
(() => {
  const ua = navigator.userAgent;
  const head = document.querySelector('head');
  const view_port_meta = document.createElement('meta');
  view_port_meta.setAttribute('name', 'viewport');
  if ((ua.indexOf('iPhone') > 0) || ua.indexOf('iPod') > 0 || (ua.indexOf('Android') > 0 && ua.indexOf('Mobile') > 0)) {
    // スマホの場合
    view_port_meta.setAttribute('content', 'width=device-width, initial-scale=1');
  } else {
    // それ以外の場合
    view_port_meta.setAttribute('content', 'width=1080, initial-scale=1.0');
  }
  head.prepend(view_port_meta);
})();

/*-------------------------------------------
  * Page Loading
-------------------------------------------*/
/**
 * 読み込み時にBodyタグにクラスを付与
 */
window.onload = () => {
  setTimeout(() => document.body.setAttribute('data-page-state', 'entered'), 500);
};

/**
 * リンククリックで遷移エフェクト発火
 */
(() => {
  const transition_links = document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="tel:"])');
  for (const link of transition_links) {
    link.addEventListener('click', event => {
      const url = link.getAttribute('href');
      if(url !== '') {
        document.body.setAttribute('data-page-state', 'leave');
        event.preventDefault();
        setTimeout(() => {
          window.location = url;
        }, 500);
      }
      return false;
    });
  }
})();

/**
 * スマホ時に「戻る」ボタンクリック時に遷移エフェクトが止まってしまうのを解消
 */
window.onpageshow = event => {
  if(event.persisted) window.location.reload();
};

/*-------------------------------------------
  * Screen Height
-------------------------------------------*/
if (window.matchMedia('(max-width: 767px)').matches) {
  const setFillHeight = () => {
    const vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
  }

  // 画面のサイズ変動があった時に高さを再計算する
  window.addEventListener('resize', setFillHeight);

  // 初期化
  setFillHeight();
}